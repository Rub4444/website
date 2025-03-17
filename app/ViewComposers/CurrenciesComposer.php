<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Services\CurrencyConversion;

class CurrenciesComposer
{
    public function compose(View $view)
    {
        $currencies = CurrencyConversion::getCurrencies();

        $view->with('currencies', $currencies);
    }
}
