<?php

namespace ForexCalculator\DataProviders;

use ForexCalculator\DataProviderInterface;

class YahooDataProvider implements DataProviderInterface
{
    
    /**
     * @inheritdoc
     */
    public function getTickValueForSymbol($symbol)
    {
    }

    /**
     * @inheritdoc
     */
    public function getEnabledCurrencies()
    {
    }

    /**
     * @inheritdoc
     */
    public function isEnabledCurrency($currency)
    {
    }

    /**
     * @inheritdoc
     */
    public function getPrice($symbol, $priceType)
    {
    }

}
