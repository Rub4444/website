<?php

use App\Models\Category;
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
        'price' => 150,
    ]);
    $this->user = User::factory()->create();
});

it('does not duplicate skus or double count when login and merge are triggered twice', function () {
    $dbOrder = Order::create([
        'user_id' => $this->user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $dbOrder->skus()->attach($this->sku->id, ['count' => 2, 'price' => 150]);

    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s = new Sku();
    $s->setRawAttributes($this->sku->getAttributes());
    $s->setRelation('pivot', null);
    $s->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $this->user, false));

    $afterFirst = Order::with('skus')->find($dbOrder->id);
    expect($afterFirst->skus->count())->toBe(1);
    $countFirst = (float) $afterFirst->skus->first()->pivot->count;
    $priceFirst = (float) $afterFirst->skus->first()->pivot->price;
    expect($countFirst)->toBe(3.0);
    expect($priceFirst)->toBe(150.0);

    session(['order' => $afterFirst]);
    $listener->handle(new Login('web', $this->user, false));

    $afterSecond = Order::with('skus')->find($dbOrder->id);
    expect($afterSecond->skus->count())->toBe(1);
    $countSecond = (float) $afterSecond->skus->first()->pivot->count;
    $priceSecond = (float) $afterSecond->skus->first()->pivot->price;

    expect($countSecond)->toBe($countFirst);
    expect($countSecond)->toBe(3.0);
    expect($priceSecond)->toBe(150.0);
    expect($afterSecond->getKey())->toBe($dbOrder->id);
});

it('merges only once and keeps single pivot row per sku', function () {
    $dbOrder = Order::create([
        'user_id' => $this->user->id,
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $dbOrder->skus()->attach($this->sku->id, ['count' => 1, 'price' => 100]);

    $sessionOrder = new Order([
        'status' => Order::STATUS_PENDING,
        'currency_id' => 1,
        'sum' => 0,
    ]);
    $s = new Sku();
    $s->setRawAttributes($this->sku->getAttributes());
    $s->setRelation('pivot', null);
    $s->countInOrder = 1;
    $sessionOrder->setRelation('skus', collect([$s]));
    session(['order' => $sessionOrder]);

    $listener = new MergeBasketAfterLogin();
    $listener->handle(new Login('web', $this->user, false));
    $listener->handle(new Login('web', $this->user, false));

    $rows = DB::table('order_sku')->where('order_id', $dbOrder->id)->get();
    expect($rows->count())->toBe(1);
    expect((float) $rows->first()->count)->toBe(2.0);
    expect((float) $rows->first()->price)->toBe(150.0);
});
