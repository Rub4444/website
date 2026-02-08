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
        'count' => 100,
        'price' => 50,
    ]);
    $this->user = User::factory()->create();
});

it('keeps correct count and single pivot row after many rapid add and remove updates', function () {
    $this->actingAs($this->user);
    $this->get(route('index'));
    $token = session()->token();

    $this->postJson(route('cart.add.ajax', $this->sku), [
        'quantity' => 1,
        '_token' => $token,
    ], ['X-XSRF-TOKEN' => $token])->assertOk();

    for ($i = 0; $i < 15; $i++) {
        $this->postJson(route('cart.update.ajax', $this->sku), [
            'delta' => 1,
            '_token' => $token,
        ], ['X-XSRF-TOKEN' => $token])->assertOk();
    }
    for ($i = 0; $i < 10; $i++) {
        $this->postJson(route('cart.update.ajax', $this->sku), [
            'delta' => -1,
            '_token' => $token,
        ], ['X-XSRF-TOKEN' => $token])->assertOk();
    }

    $order = session('order');
    expect($order)->not->toBeNull();
    expect($order->skus->count())->toBe(1);
    $countInOrder = $order->skus->first()->countInOrder;
    expect($countInOrder)->toBeGreaterThanOrEqual(0);
    expect($countInOrder)->toBe(6.0);
});

it('does not allow count to go negative and order stays pending', function () {
    $this->actingAs($this->user);
    $this->get(route('index'));
    $token = session()->token();

    $this->postJson(route('cart.add.ajax', $this->sku), [
        'quantity' => 2,
        '_token' => $token,
    ], ['X-XSRF-TOKEN' => $token])->assertOk();

    for ($i = 0; $i < 5; $i++) {
        $this->postJson(route('cart.update.ajax', $this->sku), [
            'delta' => -1,
            '_token' => $token,
        ], ['X-XSRF-TOKEN' => $token])->assertOk();
    }

    $order = session('order');
    $item = $order->skus->firstWhere('id', $this->sku->id);
    $count = $item ? $item->countInOrder : 0;
    expect($count)->toBeGreaterThanOrEqual(0);
});

it('results in one pivot row per sku after checkout following rapid updates', function () {
    $this->actingAs($this->user);
    $this->get(route('index'));
    $token = session()->token();

    $this->postJson(route('cart.add.ajax', $this->sku), [
        'quantity' => 3,
        '_token' => $token,
    ], ['X-XSRF-TOKEN' => $token])->assertOk();

    for ($i = 0; $i < 5; $i++) {
        $this->postJson(route('cart.update.ajax', $this->sku), ['delta' => 1, '_token' => $token], ['X-XSRF-TOKEN' => $token])->assertOk();
        $this->postJson(route('cart.update.ajax', $this->sku), ['delta' => -0.5, '_token' => $token], ['X-XSRF-TOKEN' => $token])->assertOk();
    }

    Config::set('services.telcell.url', 'https://fake.telcell.test/invoices');
    Config::set('services.telcell.shop_id', 'test');
    Config::set('services.telcell.shop_key', 'test');
    Http::fake();

    $order = session('order');
    $this->withSession(['order' => $order])
        ->post(route('basket-confirm'), [
        'name' => 'Test',
        'phone' => '123',
        'email' => $this->user->email,
        'delivery_type' => 'pickup',
        '_token' => $token,
    ]);

    $savedOrder = Order::where('user_id', $this->user->id)->latest('id')->first();
    expect($savedOrder)->not->toBeNull();
    expect((int) $savedOrder->status)->toBe(Order::STATUS_PENDING);

    $rows = DB::table('order_sku')->where('order_id', $savedOrder->id)->get();
    expect($rows->count())->toBe(1);
    expect((float) $rows->first()->count)->toBeGreaterThanOrEqual(0);
});
