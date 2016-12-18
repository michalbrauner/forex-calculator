<?php

namespace ForexCalculator\DataProviders;

interface CurrencyProviderInterface
{

    /**
     * @param string $symbol
     * @return bool
     */
    public function currencyExists(string $symbol);

    /**
     * @return array
     */
    public function getCurrencies();

}
