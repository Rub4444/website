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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;

use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\PaymentController;

Auth::routes([
    'reset'=>true,
    'confirm'=>false,
    'verify'=>true
]);

Route::get('/email/verify', function ()
{
    return view('auth.verify');
})->name('verification.notice');

// Route::get('/locale/{locale}', [MainController::class, 'changeLocale'])->name('locale');
// routes/web.php
Route::get('/locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'hy'])) {
        session(['locale' => $lang]);
    }
    return redirect()->back();
})->name('locale');

Route::get('/currency/{currencyCode}', [MainController::class, 'changeCurrency'])->name('currency');

Route::get('/logout', [LoginController::class, 'logout'])->name('get-logout')->middleware('auth');

Route::get('/search', [SkuController::class, 'search'])->name('products.search');

Route::middleware(['set_locale'])->group(function()
{
    Route::middleware(['auth'])->group(function ()
    {
         Route::prefix('admin')->middleware('is_admin')->group(function ()
        {
            Route::post('/products/import', [ProductImportController::class, 'import'])->name('products.import');
            Route::get('/products/tree', [ProductController::class, 'tree'])->name('products.tree');

            Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('home');
            Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
            Route::post('/orders/{order}/confirm', [App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('admin.orders.confirm');
            Route::put('/orders/{order}/cancel', [App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('admin.orders.cancel');
            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('products/{product}/skus', SkuController::class);
            Route::resource('properties', PropertyController::class);
            Route::resource('merchants', MerchantController::class);
            Route::get('merchant/{merchant}/update_token',  [\App\Http\Controllers\Admin\MerchantController::class, 'updateToken'])->name('merchants.update_token');
            Route::resource('coupons', CouponController::class);
            Route::resource('properties/{property}/property-options', PropertyOptionController::class);
        });

        Route::prefix('person')->as('person.')->group(function ()
        {
            Route::get('/orders', [\App\Http\Controllers\Person\OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [\App\Http\Controllers\Person\OrderController::class, 'show'])->name('orders.show');
        });

        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/toggle/{sku}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

        Route::get('/payment/pay', [PaymentController::class, 'pay']);
        Route::get('/payment/callback', [PaymentController::class, 'callback']);
        Route::post('/payment/cancel', [PaymentController::class, 'cancelPost']);
        Route::post('/payment/refund', [PaymentController::class, 'refundPost']);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/view', [ProfileController::class, 'index'])->name('profile.index');


    });

    Route::post('/email/verification-notification', function (Request $request)
    {
        if ($request->user()->hasVerifiedEmail())
        {
            return redirect()->intended('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Ссылка подтверждения отправлена на ваш email.');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    Route::group([
        'middleware' => ['auth', 'verified', 'basket_not_empty']
    ], function () {

        Route::post('/basket/ajax/add/{sku}', [\App\Http\Controllers\BasketController::class, 'ajaxAdd'])->name('basket.ajax.add');
        Route::post('/basket/ajax/remove/{sku}', [\App\Http\Controllers\BasketController::class, 'ajaxRemove'])->name('basket.ajax.remove');

        Route::delete('/basket/clear', [BasketController::class, 'basketClear'])->name('basket.clear');

        Route::get('/basket', [BasketController::class, 'basket'])->name('basket');
        Route::get('/basket/place', [BasketController::class, 'basketPlace'])->name('basket-place');
        Route::post('/basket/place', [BasketController::class, 'basketConfirm'])->name('basket-confirm');
        Route::post('coupon', [BasketController::class, 'setCoupon'])->name('set-coupon');
    });

    Route::get('/reset', [ResetController::class, 'reset'])->name('reset');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop');

    Route::post('/basket/add/{skus}', [BasketController::class, 'basketAdd'])->name('basket-add');
    Route::post('/basket/remove/{skus}', [BasketController::class, 'basketRemove'])->name('basket-remove');

    Route::get('/how-to-use', [MainController::class, 'howToUse'])->name('howToUse');
    Route::get('/offer', [MainController::class, 'offer'])->name('offer');
    Route::get('/delivery', [MainController::class, 'delivery'])->name('delivery');
    Route::get('/about-us', [MainController::class, 'about'])->name('about-us');


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
