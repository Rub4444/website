<?php

namespace App\Services;

use App\Models\Currency;
use Carbon\Carbon;

class CurrencyConversion
{
    public const DEFAULT_CURRENCY_CODE = 'AMD';
    protected static $container;

    public static function loadContainer()
    {
        if (is_null(self::$container)) {
            $currencies = Currency::get();
            foreach ($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }

    public static function getCurrencies()
    {
        self::loadContainer();
        return self::$container;
    }

    public static function getCurrencyFromSession()
    {
        return session('currency', self::DEFAULT_CURRENCY_CODE);
    }

    public static function getCurrentCurrencyFromSession()
    {
        self::loadContainer();
        $currencyCode = self::getCurrencyFromSession();

        foreach (self::$container as $currency) {
            if ($currency->code === $currencyCode) {
                return $currency;
            }
        }

        return null;
    }

    public static function convert($sum, $originCurrencyCode = self::DEFAULT_CURRENCY_CODE, $targetCurrencyCode = null)
    {
        self::loadContainer();

        if (!isset(self::$container[$originCurrencyCode])) {
            return 0;
        }

        $originCurrency = self::$container[$originCurrencyCode];

        if ($originCurrency->code != self::DEFAULT_CURRENCY_CODE) {
            if ($originCurrency->rate != 0 || $originCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
                CurrencyRates::getRates();
                self::loadContainer();
                $originCurrency = self::$container[$originCurrencyCode];
            }
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = self::getCurrencyFromSession();
        }

        if (!isset(self::$container[$targetCurrencyCode])) {
            return 0;
        }

        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($originCurrency->code != self::DEFAULT_CURRENCY_CODE) {
            if ($targetCurrency->rate == 0 || $targetCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
                CurrencyRates::getRates();
                self::loadContainer();
                $targetCurrency = self::$container[$targetCurrencyCode];
            }
        }

        return $sum / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol()
    {
        self::loadContainer();
        $currencyFromSession = self::getCurrencyFromSession();

        if (isset(self::$container[$currencyFromSession])) {
            $currency = self::$container[$currencyFromSession];
            return $currency->symbol;
        } else {
            return '֏';
        }
    }

    public static function getBaseCurrency()
    {
        self::loadContainer();

        foreach (self::$container as $code => $currency) {
            if ($currency->isMain()) {
                return $currency;
            }
        }

        return null;
    }
}
