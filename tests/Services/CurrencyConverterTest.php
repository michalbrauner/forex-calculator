<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\Services\CurrencyConverter;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

final class CurrencyConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataForConvertToCurrency
     * @param float $expectedValue
     * @param float $value
     * @param string $priceFromDataProvider
     */
    public function testConvertToCurrency(float $expectedValue, float $value, string $priceFromDataProvider): void
    {
        $currencyConverter = new CurrencyConverter($this->getMockYahooDataProvider($priceFromDataProvider));

        $this->assertEquals($expectedValue, $currencyConverter->convertToCurrency('currency1', 'currency2', $value));
    }

    /**
     * @param string $priceFromDataProvider
     * @return YahooDataProvider|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockYahooDataProvider(string $priceFromDataProvider): YahooDataProvider
    {
        $yahooDataProvider = $this->getMockBuilder(YahooDataProvider::class)
            ->setMethods(['getPrice'])
            ->disableOriginalConstructor()
            ->getMock();

        $yahooDataProvider->method('getPrice')->willReturn($priceFromDataProvider);

        return $yahooDataProvider;
    }

    public function getDataForConvertToCurrency(): array
    {
        return [
            [1.05, 1, '1.0452'],
            [0.96, 1, '0.9571'],
            [2.61, 2.5, '1.0452'],
            [2.39, 2.5, '0.9571'],
        ];
    }

}
