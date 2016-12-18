<?php

namespace ForexCalculator\DataProviders;

interface DataProviderInterface
{
    const PRICE_ASK = 'ask';
    const PRICE_BID = 'bid';

    /**
     * @param string $symbol
     * @param string $priceType
     * @return string
     */
    public function getPrice(string $symbol, string $priceType);
}
