<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CurrencyConversion;

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
        View::composer(['layouts.master', 'categories'], 'App\ViewComposers\CategoriesComposer');
        View::composer(['layouts.master'], 'App\ViewComposers\CurrenciesComposer');
        View::composer(['index'], 'App\ViewComposers\BestProductsComposer');

        View::composer('*', function($view)
        {
            $currencySymbol = CurrencyConversion::getCurrencySymbol();
            $view->with('currencySymbol', $currencySymbol);
        });
    }
}
