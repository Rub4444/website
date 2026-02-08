<?php

namespace App\Classes;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Services\sConversion;
use Illuminate\Support\Facades\Log;

class Basket
{
    protected $order;

    /** @var CartStore|null */
    protected $cartStore;

    public function __construct($createOrder = false)
    {
        $this->cartStore = new CartStore();
        $order = session('order');

        if (is_null($order) && $createOrder) {
            $userId = Auth::check() ? Auth::id() : null;
            $items = $this->cartStore->getItems($userId, session()->getId());
            if ($items !== []) {
                $this->order = $this->buildOrderFromItems($items, $userId);
                session(['order' => $this->order]);
            } else {
                $data = $userId ? ['user_id' => $userId] : [];
                $data['currency_id'] = 1;
                $this->order = new Order($data);
                session(['order' => $this->order]);
            }
        } else {
            $this->order = $order;
        }
    }

    /**
     * @param array<int, float> $items sku_id => count
     */
    protected function buildOrderFromItems(array $items, ?int $userId): Order
    {
        $data = ['currency_id' => 1];
        if ($userId) {
            $data['user_id'] = $userId;
        }
        $order = new Order($data);
        $skuIds = array_keys($items);
        $skus = Sku::whereIn('id', $skuIds)->with('product')->get();
        $collection = collect([]);
        foreach ($skus as $sku) {
            $count = $items[$sku->id] ?? 0;
            if ($count <= 0) {
                continue;
            }
            $sku->countInOrder = $count;
            $sku->unit = $sku->product->unit ?? 'pcs';
            $collection->push($sku);
        }
        $order->setRelation('skus', $collection);
        return $order;
    }

    protected function syncCartToRedis(): void
    {
        $userId = $this->order->user_id ?? (Auth::check() ? Auth::id() : null);
        $items = [];
        foreach ($this->order->skus ?? [] as $sku) {
            $items[$sku->id] = $sku->countInOrder;
        }
        $this->cartStore->setItems($userId, $items, session()->getId());
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function clearCart(): void
    {
        $userId = Auth::check() ? Auth::id() : null;
        $this->cartStore->forget($userId, session()->getId());
        session()->forget('order');
    }

    public function countAvailable($updateCount = false)
    {
        $ids = $this->order->skus->pluck('id');
        if ($ids->isEmpty()) {
            return true;
        }
        $skusById = Sku::whereIn('id', $ids)->get()->keyBy('id');
        $skus = collect([]);
        foreach ($this->order->skus as $orderSku)
        {
            $sku = $skusById->get($orderSku->id);
            if (!$sku || $orderSku->countInOrder > $sku->count)
            {
                return false;
            }
            if ($updateCount)
            {
                $sku->count -= $orderSku->countInOrder;
                $skus->push($sku);
            }
        }
        if ($updateCount)
        {
            $skus->map->save();
        }
        return true;
    }

    public function saveOrder($name, $phone, $email, $deliveryType, $delivery_city = null, $delivery_street = null, $delivery_home = null, $note = null)
    {
        if (!$this->countAvailable(true)) {
            return false;
        }

        $order = $this->order;
        $skus = $order->skus;

        return DB::transaction(function () use ($order, $skus, $name, $phone, $email, $deliveryType, $delivery_city, $delivery_street, $delivery_home, $note) {
            if ($order->getKey()) {
                $locked = Order::where('id', $order->id)->lockForUpdate()->first();
                if (!$locked || (int) $locked->status !== Order::STATUS_PENDING) {
                    return null;
                }
            }

            $order->name = $name;
            $order->phone = $phone;
            $order->email = $email;
            $order->delivery_type = $deliveryType;
            $order->delivery_city = $delivery_city;
            $order->delivery_street = $delivery_street;
            $order->delivery_home = $delivery_home;
            $order->status = Order::STATUS_PENDING;
            $order->note = $note;
            $order->sum = max(0, $order->getFullSum());
            if ($order->delivery_type === 'delivery' && $order->sum < 10000) {
                $order->sum += 500;
            }

            $order->save();

            foreach ($skus as $sku) {
                $order->skus()->attach($sku->id, [
                    'count' => $sku->countInOrder,
                    'price' => $sku->price,
                ]);
            }

            $this->cartStore->forget($order->user_id, session()->getId());
            session()->forget('order');

            return $order;
        });
    }



    public function removeSku(Sku $sku, $quantity = null)
    {
        // Проверяем единицу измерения у продукта
        $unit = $sku->product->unit;

        $quantity = $quantity ?? ($unit === 'kg' ? 0.1 : 1);

        if ($this->order->skus->contains($sku)) {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();

            $pivotRow->countInOrder -= $quantity;
            if ($pivotRow->countInOrder <= 0) {
                $this->order->skus = $this->order->skus->filter(fn($s) => $s->id !== $sku->id);
            }
        }
        $this->syncCartToRedis();
    }



        public function addSku(Sku $sku, $quantity = null)
    {
        Log::info('ADD SKU DEBUG', [
            'sku_id' => $sku->id,
            'quantity' => $quantity,
            'available' => $sku->isAvailable(),
            'count' => $sku->count ?? null,
            'unit' => $sku->unit,
            'price' => $sku->price,
        ]);
        $unit = $sku->product->unit; // берём unit у продукта
        $quantity = $quantity ?? ($unit === 'kg' ? 0.5 : 1); // default 0.5kg или 1шт

        if ($this->order->skus->contains($sku))
        {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();

            // Проверяем, чтобы не превышать доступный count для шт
            if ($unit === 'pcs' && $pivotRow->countInOrder + $quantity > $sku->count)
            {
                return false;
            }

            $pivotRow->countInOrder += $quantity;
        }
        else
        {
            if ($unit === 'pcs' && $quantity > $sku->count)
            {
                return false;
            }

            $sku->countInOrder = $quantity;
            $sku->unit = $unit; // сохраняем единицу для корзины
            $this->order->skus->push($sku);
        }
        $this->syncCartToRedis();
        return true;
    }

    public function setCoupon(Coupon $coupon)
    {
        $this->order->coupon()->associate($coupon);
    }

    public function clearCoupon()
    {
        $this->order->coupon()->dissociate();
    }

    public function setUserId($userId)
    {
        $this->order->user_id = $userId;
    }

    protected function addPackageSku()
    {
        // ID пакета лучше вынести в .env или config
        $packageSkuId = config('app.package_sku_id');

        $sku = Sku::find($packageSkuId);

        if ($sku)
        {
            $this->addSku($sku, 1); // добавляем 1 пакет
        }
    }

}
