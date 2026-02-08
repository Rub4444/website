<?php

use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use App\Listeners\MergeBasketAfterLogin;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

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
    $this->category = Category::create([
        'code' => 'cat1',
        'name' => 'Category 1',
    ]);
    $this->product = Product::create([
        'category_id' => $this->category->id,
        'code' => 'prod1',
        'name' => 'Product 1',
        'unit' => 'pcs',
    ]);
    $this->sku1 = Sku::create([
        'product_id' => $this->product->id,
        'count' => 10,
        'price' => 100,
    ]);
    $this->sku2 = Sku::create([
        'product_id' => $this->product->id,
        'count' => 10,
        'price' => 200,
    ]);
});

it('merges guest basket into user db order and sums counts and keeps price in pivot', function () {
    $user = User::factory()->create();

    $dbOrder = Order::create([
        'user_id' => $user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $dbOrder->skus()->attach($this->sku1->id, ['count' => 1, 'price' => 100]);

    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s1 = new Sku();
    $s1->setRawAttributes($this->sku1->getAttributes());
    $s1->setRelation('pivot', null);
    $s1->countInOrder = 2;
    $s2 = new Sku();
    $s2->setRawAttributes($this->sku2->getAttributes());
    $s2->setRelation('pivot', null);
    $s2->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s1, $s2]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $user, false));

    $order = Order::with('skus')->find($dbOrder->id);
    expect($order->skus->count())->toBe(2);

    $pivot1 = $order->skus->firstWhere('id', $this->sku1->id)->pivot;
    expect((float) $pivot1->count)->toBe(3.0);
    expect((float) $pivot1->price)->toBe(100.0);

    $pivot2 = $order->skus->firstWhere('id', $this->sku2->id)->pivot;
    expect((float) $pivot2->count)->toBe(1.0);
    expect((float) $pivot2->price)->toBe(200.0);

    expect(session('order')->getKey())->toBe($dbOrder->id);
});

it('does not double merge on second login', function () {
    $user = User::factory()->create();

    $dbOrder = Order::create([
        'user_id' => $user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $dbOrder->skus()->attach($this->sku1->id, ['count' => 2, 'price' => 100]);

    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s1 = new Sku();
    $s1->setRawAttributes($this->sku1->getAttributes());
    $s1->setRelation('pivot', null);
    $s1->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s1]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $user, false));

    $afterFirst = Order::with('skus')->find($dbOrder->id);
    $countAfterFirst = (float) $afterFirst->skus->firstWhere('id', $this->sku1->id)->pivot->count;

    session(['order' => $afterFirst]);
    $listener->handle(new Login('web', $user, false));

    $afterSecond = Order::with('skus')->find($dbOrder->id);
    $countAfterSecond = (float) $afterSecond->skus->firstWhere('id', $this->sku1->id)->pivot->count;

    expect($countAfterSecond)->toBe($countAfterFirst);
    expect($countAfterSecond)->toBe(3.0);
    expect($afterSecond->getKey())->toBe($dbOrder->id);
});

it('assigns session order to user when user has no db order', function () {
    $user = User::factory()->create();

    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s1 = new Sku();
    $s1->setRawAttributes($this->sku1->getAttributes());
    $s1->setRelation('pivot', null);
    $s1->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s1]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $user, false));

    expect(session('order')->getKey())->not->toBeNull();
    expect($sessionOrder->user_id)->toBe($user->id);
    expect(session('order')->user_id)->toBe($user->id);
});

it('does nothing when session order is empty', function () {
    $user = User::factory()->create();
    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $sessionOrder->setRelation('skus', collect([]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $user, false));

    expect(Order::where('user_id', $user->id)->count())->toBe(0);
});
