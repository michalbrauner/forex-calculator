<?php


namespace Tests\ForexCalculator\Services\CurrencyProvider;

use ForexCalculator\Services\SymbolProvider;
use PHPUnit_Framework_TestCase;

class SymbolProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataForSymbolExists
     *
     * @param bool $expectedExists
     * @param string $testedSymbol
     */
    public function testSymbolExists($expectedExists, $testedSymbol)
    {
        $currencyProvider = new SymbolProvider();
        $this->assertEquals($expectedExists, $currencyProvider->symbolExists($testedSymbol));
    }

    /**
     * @return array
     */
    public function getDataForSymbolExists()
    {
        return [
            [true, 'EURUSD'],
            [true, 'eurusd'],
            [true, 'EuRUsd'],
            [false, 'something'],
        ];
    }
}
