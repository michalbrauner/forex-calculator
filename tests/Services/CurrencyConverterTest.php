<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataProviders\SymbolProvider;
use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\Services\CurrencyConverter;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class CurrencyConverterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataForConvertToCurrency
     *
     * @param float $expectedValue
     * @param float $value
     * @param string $priceFromDataProvider
     */
    public function testConvertToCurrency(float $expectedValue, float $value, string $priceFromDataProvider)
    {
        $currencyConverter = new CurrencyConverter($this->getMockYahooDataProvider($priceFromDataProvider));

        $this->assertEquals($expectedValue, $currencyConverter->convertToCurrency('currency1', 'currency2', $value));
    }

    /**
     * @return array
     */
    public function getDataForConvertToCurrency()
    {
        return [
            [1.05, 1, '1.0452'],
            [0.96, 1, '0.9571'],
        ];
    }

    /**
     * @param bool[] $symbolExistsReturnValues
     * @return SymbolProvider|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockSymbolProvider(array $symbolExistsReturnValues)
    {
        $symbolProvider = $this->getMockBuilder(SymbolProvider::class)
            ->setMethods(['symbolExists'])
            ->disableOriginalConstructor()
            ->getMock();

        $symbolProvider->method('symbolExists')->willReturnOnConsecutiveCalls($symbolExistsReturnValues);

        return $symbolProvider;
    }

    /**
     * @param string $priceFromDataProvider
     * @return YahooDataProvider|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockYahooDataProvider(string $priceFromDataProvider)
    {
        $yahooDataProvider = $this->getMockBuilder(YahooDataProvider::class)
            ->setMethods(['getPrice'])
            ->disableOriginalConstructor()
            ->getMock();

        $yahooDataProvider->method('getPrice')->willReturn($priceFromDataProvider);

        return $yahooDataProvider;
    }

}
