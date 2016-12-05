<?php

namespace ForexCalculator;

interface DataProviderInterface
{
    const PRICE_ASK = 'ask';
    const PRICE_BID = 'bid';

    /**
     * @param string $symbol
     * @param string $priceType
     * @return string
     */
    public function getPrice($symbol, $priceType);
}
