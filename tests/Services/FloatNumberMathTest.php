<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use ForexCalculator\Services\FloatNumberMath;

class FloatNumberMathTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForSum
     *
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface[] $floatNumbersToSum
     * @param int $outputPrecision
     */
    public function testSum(FloatNumberInterface $expectedNumber, array $floatNumbersToSum, int $outputPrecision)
    {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->sum($floatNumbersToSum));
    }

    /**
     * @return array
     */
    public function getDataForSum()
    {
        return [
            [
                new FloatNumber(300, 0),
                [new FloatNumber(100, 0), new FloatNumber(200, 0)],
                0,
            ],
            [
                new FloatNumber(3005, 1),
                [new FloatNumber(1002, 1), new FloatNumber(2003, 1)],
                1,
            ],
            [
                new FloatNumber(301, 0),
                [new FloatNumber(1002, 1), new FloatNumber(2003, 1)],
                0,
            ],
            [
                new FloatNumber(30053, 2),
                [new FloatNumber(10023, 2), new FloatNumber(2003, 1)],
                2,
            ],
            [
                new FloatNumber(3005, 1),
                [new FloatNumber(10023, 2), new FloatNumber(2003, 1)],
                1,
            ],
            [
                new FloatNumber(30053, 2),
                [new FloatNumber(2003, 1), new FloatNumber(10023, 2)],
                2,
            ],
            [
                new FloatNumber(351021, 3),
                [new FloatNumber(2003, 1), new FloatNumber(10023, 2), new FloatNumber(50491, 3)],
                3,
            ],
            [
                new FloatNumber(-10007, 2),
                [new FloatNumber(-2003, 1), new FloatNumber(10023, 2)],
                2,
            ],
            [
                new FloatNumber(20030, 2),
                [new FloatNumber(2003, 1)],
                2,
            ],
            [
                new FloatNumber(2003, 1),
                [new FloatNumber(2003, 1)],
                1,
            ],
            [
                new FloatNumber(0, 1),
                [],
                1,
            ],
        ];
    }

}
