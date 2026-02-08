<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    DB::table('currencies')->insert([
        'id' => 1,
        'code' => 'AMD',
        'symbol' => '֏',
        'is_main' => 1,
        'rate' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $category = Category::create(['code' => 'cat1', 'name' => 'Cat1']);
    $product = Product::create([
        'category_id' => $category->id,
        'code' => 'p1',
        'name' => 'Product 1',
        'unit' => 'pcs',
    ]);
    $this->sku = Sku::create([
        'product_id' => $product->id,
        'count' => 10,
        'price' => 100,
    ]);
    $this->user = User::factory()->create();
});

it('finalizes order on checkout and clears session basket', function () {
    $this->actingAs($this->user);

    Config::set('services.telcell.url', 'https://fake.telcell.test/invoices');
    Config::set('services.telcell.shop_id', 'test');
    Config::set('services.telcell.shop_key', 'test');
    Http::fake();

    $sessionOrder = new Order([
        'user_id' => $this->user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s = new Sku();
    $s->setRawAttributes($this->sku->getAttributes());
    $s->setRelation('pivot', null);
    $s->countInOrder = 2;
    $sessionOrder->setRelation('skus', collect([$s]));

    $this->withSession(['order' => $sessionOrder])
        ->post(route('basket-confirm'), [
            'name' => 'Test User',
            'phone' => '123456789',
            'email' => $this->user->email,
            'delivery_type' => 'pickup',
            '_token' => csrf_token(),
        ]);

    $order = Order::where('user_id', $this->user->id)->latest('id')->first();
    expect($order)->not->toBeNull();
    expect((int) $order->status)->toBe(Order::STATUS_PENDING);
    expect((float) $order->sum)->toBe(200.0);

    $pivot = $order->skus()->first()->pivot;
    expect((float) $pivot->count)->toBe(2.0);
    expect((float) $pivot->price)->toBe(100.0);

    expect(session('order'))->toBeNull();
});

it('does not change order_sku after finalization', function () {
    $this->actingAs($this->user);

    Config::set('services.telcell.url', 'https://fake.telcell.test/invoices');
    Config::set('services.telcell.shop_id', 'test');
    Config::set('services.telcell.shop_key', 'test');
    Http::fake();

    $sessionOrder = new Order([
        'user_id' => $this->user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s = new Sku();
    $s->setRawAttributes($this->sku->getAttributes());
    $s->setRelation('pivot', null);
    $s->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s]));

    $this->withSession(['order' => $sessionOrder])
        ->post(route('basket-confirm'), [
            'name' => 'Test',
            'phone' => '123',
            'email' => $this->user->email,
            'delivery_type' => 'pickup',
            '_token' => csrf_token(),
        ]);

    $order = Order::where('user_id', $this->user->id)->latest('id')->first();
    expect($order)->not->toBeNull();
    $rows = DB::table('order_sku')->where('order_id', $order->id)->get();
    expect($rows->count())->toBe(1);
    expect((float) $rows->first()->count)->toBe(1.0);
    expect((float) $rows->first()->price)->toBe(100.0);
});

it('does not create second order on repeated checkout when session is empty', function () {
    $this->actingAs($this->user);

    Config::set('services.telcell.url', 'https://fake.telcell.test/invoices');
    Config::set('services.telcell.shop_id', 'test');
    Config::set('services.telcell.shop_key', 'test');
    Http::fake();

    $sessionOrder = new Order([
        'user_id' => $this->user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s = new Sku();
    $s->setRawAttributes($this->sku->getAttributes());
    $s->setRelation('pivot', null);
    $s->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s]));

    $this->withSession(['order' => $sessionOrder])
        ->post(route('basket-confirm'), [
            'name' => 'Test',
            'phone' => '123',
            'email' => $this->user->email,
            'delivery_type' => 'pickup',
            '_token' => csrf_token(),
        ]);

    $countBefore = Order::where('user_id', $this->user->id)->count();

    session()->forget('order');
    $response = $this->post(route('basket-confirm'), [
        'name' => 'Test',
        'phone' => '123',
        'email' => $this->user->email,
        'delivery_type' => 'pickup',
        '_token' => csrf_token(),
    ]);

    $countAfter = Order::where('user_id', $this->user->id)->count();
    expect($countAfter)->toBe($countBefore);
    $response->assertRedirect(route('index'));
});
