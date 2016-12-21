<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use ForexCalculator\Services\FloatNumberMath;
use PHPUnit_Framework_TestCase;

class FloatNumberMathTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForMul
     *
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @param int $outputPrecision
     */
    public function testMul(
        FloatNumberInterface $expectedNumber,
        FloatNumberInterface $number1,
        FloatNumberInterface $number2,
        int $outputPrecision
    ) {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->mul($number1, $number2));
    }

    /**
     * @return array
     */
    public function getDataForMul()
    {
        return [
            [new FloatNumber(6, 0), new FloatNumber(2, 0), new FloatNumber(3, 0), 0],
            [new FloatNumber(883, 2), new FloatNumber(256, 2), new FloatNumber(345, 2), 2],
            [new FloatNumber(-883, 2), new FloatNumber(256, 2), new FloatNumber(-345, 2), 2],
            [new FloatNumber(883, 2), new FloatNumber(-256, 2), new FloatNumber(-345, 2), 2],
            [new FloatNumber(9, 0), new FloatNumber(256, 2), new FloatNumber(345, 2), 0],
            [new FloatNumber(0, 1), new FloatNumber(256, 2), new FloatNumber(0, 2), 1],
            [new FloatNumber(117164567950, 6), new FloatNumber(25645, 2), new FloatNumber(456871, 3), 6],
        ];
    }

    public function testAbs()
    {
        $expectedNumber = new FloatNumber(10050, 2);

        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(2));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);

        $this->assertEquals($expectedNumber, $floatNumberMath->abs(new FloatNumber(10050, 2)));
        $this->assertEquals($expectedNumber, $floatNumberMath->abs(new FloatNumber(-10050, 2)));
    }

    /**
     * @dataProvider getDataForSub
     *
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @param int $outputPrecision
     */
    public function testSub(
        FloatNumberInterface $expectedNumber,
        FloatNumberInterface $number1,
        FloatNumberInterface $number2,
        int $outputPrecision
    ) {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->sub($number1, $number2));
    }

    /**
     * @return array
     */
    public function getDataForSub()
    {
        return [
            [
                new FloatNumber(100, 0),
                new FloatNumber(200, 0),
                new FloatNumber(100, 0),
                0,
            ],
            [
                new FloatNumber(1000, 1),
                new FloatNumber(200, 0),
                new FloatNumber(100, 0),
                1,
            ],
            [
                new FloatNumber(10033, 2),
                new FloatNumber(20053, 2),
                new FloatNumber(1002, 1),
                2,
            ],
            [
                new FloatNumber(1003, 1),
                new FloatNumber(20053, 2),
                new FloatNumber(1002, 1),
                1,
            ],
            [
                new FloatNumber(-100330, 3),
                new FloatNumber(1002, 1),
                new FloatNumber(20053, 2),
                3,
            ],
        ];
    }

    /**
     * @dataProvider getDataForAdd
     *
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface[] $floatNumbersToSum
     * @param int $outputPrecision
     */
    public function testAdd(FloatNumberInterface $expectedNumber, array $floatNumbersToSum, int $outputPrecision)
    {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->add($floatNumbersToSum));
    }

    /**
     * @return array
     */
    public function getDataForAdd()
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
