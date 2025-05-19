<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CurrencyConversion;
use App\Models\Sku;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', 'App\ViewComposers\CategoriesComposer');
        View::composer(['layouts.master', 'auth.coupons.form'], 'App\ViewComposers\CurrenciesComposer');
        View::composer(['index'], 'App\ViewComposers\BestProductsComposer');

        View::composer('*', function($view)
        {
            $currencySymbol = CurrencyConversion::getCurrencySymbol();
            $view->with('currencySymbol', $currencySymbol);
        });
    }
}
