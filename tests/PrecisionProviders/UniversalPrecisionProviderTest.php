<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use PHPUnit_Framework_TestCase;

final class UniversalPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForGetPrecision
     * @param int $expectedPrecision
     * @param int $givenPrecision
     */
    public function testGetPrecision(int $expectedPrecision, int $givenPrecision): void
    {
       $precisionProvider = new UniversalPrecisionProvider($givenPrecision);
       $this->assertEquals($expectedPrecision, $precisionProvider->getPrecision());
    }

    public function getDataForGetPrecision(): array
    {
        return [
            [0, 0],
            [2, 2],
        ];
    }

}
