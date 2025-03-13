<?php
namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class CurrencyRates
{
    public static function getRates()
    {
        $url = "http://api.exchangeratesapi.io/v1/latest";
        $apiKey = config('currency_rates.api_key');

        $client = new Client();
        $response = $client->request('GET', $url, [
            'query' => [
                'access_key' => $apiKey,
            ]
        ]);

        if ($response->getStatusCode() !== 200)
        {
            throw new Exception('There is a problem with currency rate service');
        }

        $rates = json_decode($response->getBody()->getContents(), true)['rates'];
        foreach(CurrencyConversion::getCurrencies() as $currency)
        {
            if(!$currency->isMain())
            {
                if(!isset($rates[$currency->code]))
                {
                    throw new Exception('There is a problem with currency' . $currency->code);
                }
                else
                {
                    $currency->update(['rate' => $rates[$currency->code]]);
                    $currency->touch();
                }
            }
        }
    }
}
