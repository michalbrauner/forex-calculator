<?php

namespace ForexCalculator\Services;

interface CurrencyProviderInterface
{

    /**
     * @param string $symbol
     * @return bool
     */
    public function currencyExists($symbol);

    /**
     * @return array
     */
    public function getCurrencies();

}
