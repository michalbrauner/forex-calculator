<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\Exceptions\PriceNotFoundException;
use GuzzleHttp\Exception\GuzzleException;

final class CurrencyConverter
{

    const ROUND_TO_PRECISION = 2;

    /**
     * @var YahooDataProvider
     */
    private $yahooDataProvider;

    public function __construct(YahooDataProvider $yahooDataProvider)
    {
        $this->yahooDataProvider = $yahooDataProvider;
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $value
     * @return float
     * @throws PriceNotFoundException
     * @throws GuzzleException
     */
    public function convertToCurrency(string $fromCurrency, string $toCurrency, float $value): float
    {
        $symbol = sprintf('%s%s', $fromCurrency, $toCurrency);

        $priceAsk = (float)$this->yahooDataProvider->getPrice($symbol, DataProviderInterface::PRICE_ASK);
        $priceBid = (float)$this->yahooDataProvider->getPrice($symbol, DataProviderInterface::PRICE_BID);

        $convertedValue =
            (($priceAsk + $priceBid) / 2) * $value;

        return round($convertedValue, self::ROUND_TO_PRECISION);
    }

}
