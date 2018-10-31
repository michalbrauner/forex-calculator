<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\PricePrecisionProvider;
use PHPUnit_Framework_TestCase;

final class PricePrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForGetPrecision
     * @param int $expectedPrecision
     * @param string $symbol
     * @param bool $extendedPoint
     */
    public function testGetPrecision(int $expectedPrecision, string $symbol, bool $extendedPoint): void
    {
        $precisionProvider = new PricePrecisionProvider($symbol, $extendedPoint);
        $this->assertEquals($expectedPrecision, $precisionProvider->getPrecision());
    }

    public function getDataForGetPrecision(): array
    {
        return [
            [4, 'eurusd', false],
            [4, 'EURUSD', false],
            [2, 'usdjpy', false],
            [5, 'eurusd', true],
            [5, 'EURUSD', true],
            [3, 'usdJPY', true],
        ];
    }

}
