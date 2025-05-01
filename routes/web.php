<?php
use App\Http\Controllers\MainController;
use App\Http\Controllers\BasketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Admin\SkuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyOptionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\MerchantController;

Auth::routes([
    'reset'=>false,
    'confirm'=>false,
    'verify'=>false
]);

Route::get('/locale/{locale}', [MainController::class, 'changeLocale'])->name('locale');

Route::get('/currency/{currencyCode}', [MainController::class, 'changeCurrency'])->name('currency');

Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout');

Route::middleware(['set_locale'])->group(function()
{
    Route::get('/reset', [ResetController::class, 'reset'])->name('reset');

    Route::middleware(['auth'])->group(function ()
    {
        Route::prefix('person')->as('person.')->group(function ()
        {
            Route::get('/orders', [\App\Http\Controllers\Person\OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [\App\Http\Controllers\Person\OrderController::class, 'show'])->name('orders.show');
        });

        Route::prefix('admin')->middleware('is_admin')->group(function ()
        {
            Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('home');
            Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('products/{product}/skus', SkuController::class);
            Route::resource('properties', PropertyController::class);
            Route::resource('merchants', MerchantController::class);
            Route::get('merchant/{merchant}/update_token',  [\App\Http\Controllers\Admin\MerchantController::class, 'updateToken'])->name('merchants.update_token');
            Route::resource('coupons', CouponController::class);
            Route::resource('properties/{property}/property-options', PropertyOptionController::class);
        });
    });

    Route::post('/basket/add/{skus}', [BasketController::class, 'basketAdd'])->name('basket-add');
    Route::post('/basket/remove/{skus}', [BasketController::class, 'basketRemove'])->name('basket-remove');

    Route::group(['middleware' => 'basket_not_empty'], function()
    {
        Route::get('/basket', [BasketController::class, 'basket'])->name('basket');
        Route::get('/basket/place', [BasketController::class, 'basketPlace'])->name('basket-place');
        Route::post('/basket/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
        Route::post('coupon', [BasketController::class, 'setCoupon'])->name('set-coupon');
    });
    Route::get('/how-to-use', [MainController::class, 'howToUse'])->name('howToUse');
    Route::get('/offer', [MainController::class, 'offer'])->name('offer');
    Route::get('/delivery', [MainController::class, 'delivery'])->name('delivery');
    // Route::get('/privacy', [MainController::class, 'privacy'])->name('privacy');

    Route::get('/', [MainController::class, 'index'])->name('index');
    Route::get('/categories', [MainController::class, 'categories'])->name('categories');
    Route::post('/subscription/{sku}', [MainController::class, 'subscribe'])->name('subscription');
    Route::get('/{category}', [MainController::class, 'category'])->name('category');
    Route::get('/{category}/{product?}/{skus}', [MainController::class, 'sku'])->name('sku');
});


Route::prefix('api')->group(function () {
    require base_path('routes/api.php');
});
