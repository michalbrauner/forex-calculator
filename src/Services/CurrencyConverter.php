<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\DataProviders\YahooDataProvider;

class CurrencyConverter
{

    const ROUND_TO_PRECISION = 2;

    /**
     * @var YahooDataProvider
     */
    private $yahooDataProvider;

    /**
     * @param YahooDataProvider $yahooDataProvider
     */
    public function __construct(YahooDataProvider $yahooDataProvider)
    {
        $this->yahooDataProvider = $yahooDataProvider;
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $value
     * @return float
     */
    public function convertToCurrency(string $fromCurrency, string $toCurrency, float $value): float
    {
        $symbol = sprintf('%s%s', $fromCurrency, $toCurrency);

        $convertedValue =
            (float)$this->yahooDataProvider->getPrice($symbol, DataProviderInterface::PRICE_ASK) * $value;

        return round($convertedValue, self::ROUND_TO_PRECISION);
    }

}
