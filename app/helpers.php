<?php

if (! function_exists('currency_symbol')) {
    function currency_symbol()
    {
        if (auth()->check() && auth()->user()->restaurant) {
            $code = auth()->user()->restaurant->currency_symbol;

            $currencies = currency()->getCurrencies();

            return $currencies[$code]['symbol'] ?? '$';
        }

        return '$';
    }
}
