<?php

namespace ForexCalculator;

interface DataProviderInterface
{

    const PRICE_ASK = 'ask';
    const PRICE_BID = 'bid';

    /**
     * @param string $symbol
     * @return string
     */
    public function getTickValueForSymbol($symbol);

    /**
     * @return array
     */
    public function getEnabledSymbols();

    /**
     * @return array
     */
    public function getEnabledCurrencies();

    /**
     * @param string $symbol
     * @return boolean
     */
    public function isEnabledSymbol($symbol);

    /**
     * @param string $currency
     * @return boolean
     */
    public function isEnabledCurrency($currency);

    /**
     * @param string $symbol Symbol to get a price
     * @param string $priceType
     */
    public function getPrice($symbol, $priceType);

}
