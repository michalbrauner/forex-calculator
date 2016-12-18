<?php

namespace ForexCalculator\Services;

class CurrencyProvider implements CurrencyProviderInterface
{
    /**
     * @inheritdoc
     */
    public function currencyExists(string $symbol)
    {
        return in_array(strtoupper($symbol), $this->getCurrencies(), true);
    }

    /**
     * @inheritdoc
     */
    public function getCurrencies()
    {
        return ['USD', 'EUR', 'GBP', 'JPY', 'AUD', 'NZD'];
    }
}
