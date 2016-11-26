<?php

namespace Tests\ForexCalculator\Services\CurrencyProvider;

use ForexCalculator\Services\CurrencyProvider;
use PHPUnit_Framework_TestCase;

class CurrencyProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getDataForCurrencyExists
     *
     * @param bool $expectedExists
     * @param string $testedCurrency
     */
    public function testCurrencyExists($expectedExists, $testedCurrency)
    {
        $currencyProvider = new CurrencyProvider();
        $this->assertEquals($expectedExists, $currencyProvider->currencyExists($testedCurrency));
    }

    /**
     * @return array
     */
    public function getDataForCurrencyExists()
    {
        return [
            [true, 'EUR'],
            [true, 'eur'],
            [false, 'something'],
        ];
    }
}
