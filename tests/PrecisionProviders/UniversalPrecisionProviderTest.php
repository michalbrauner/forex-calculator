<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use PHPUnit_Framework_TestCase;

class UniversalPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForGetPrecision
     *
     * @param int $expectedPrecision
     * @param int $givenPrecision
     */
    public function testGetPrecision(int $expectedPrecision, int $givenPrecision)
    {
       $precisionProvider = new UniversalPrecisionProvider($givenPrecision);
       $this->assertEquals($expectedPrecision, $precisionProvider->getPrecision());
    }

    /**
     * @return array
     */
    public function getDataForGetPrecision()
    {
        return [
            [0, 0],
            [2, 2],
        ];
    }

}
