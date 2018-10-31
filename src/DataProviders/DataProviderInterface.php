<?php

namespace ForexCalculator\DataProviders;

interface DataProviderInterface
{

    public const PRICE_ASK = 'ask';
    public const PRICE_BID = 'bid';

    public function getPrice(string $symbol, string $priceType): string;

}
