<?php

namespace ForexCalculator\DataProviders;

use ForexCalculator\Exceptions\PriceNotLoadedException;
use GuzzleHttp\Client;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

class YahooDataProvider implements DataProviderInterface
{
    const YAHOO_FINANCE_API_URL = 'http://query.yahooapis.com/v1/public/yql';

    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @inheritdoc
     */
    public function getPrice($symbol, $priceType)
    {
        if (!in_array($priceType, array(self::PRICE_ASK, self::PRICE_BID), true)) {
            throw new InvalidArgumentException("Argument priceType has invalid value ('{$priceType}')");
        }

        $yqlQuery = sprintf('select * from yahoo.finance.xchange where pair = \'%s\'', $symbol);
        $data = $this->yahooApiQuery($yqlQuery);

        if ($data === null) {
            throw new PriceNotLoadedException(sprintf('Couldn\'t get price for symbol \'%s\'', $symbol));
        }

        return $priceType === self::PRICE_ASK
            ? $data->query->results->rate->Ask
            : $data->query->results->rate->Bid;
    }

    /**
     * @param string $query
     * @return stdClass|null
     */
    private function yahooApiQuery($query)
    {
        $url = sprintf(
            '%s?q=%s&env=store://datatables.org/alltableswithkeys&format=json',
            self::YAHOO_FINANCE_API_URL,
            urlencode($query)
        );

        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() === 200) {
            $content = $response->getBody()->getContents();
            $data = json_decode($content);

            if ($data !== null && $data->query->results !== null) {
                return $data;
            }

            return null;
        }

        throw new RuntimeException(sprintf('Yahoo API query failed: \'%s\'', $query));
    }
}
