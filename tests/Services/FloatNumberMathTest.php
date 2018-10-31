<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use ForexCalculator\Services\FloatNumberMath;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

final class FloatNumberMathTest extends PHPUnit_Framework_TestCase
{

    public function testDivSecondArgumentIsZeroNumber(): void
    {
        $floatNumber1 = new FloatNumber(100, 0);
        $floatNumber2 = new FloatNumber(0, 0);

        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(0));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid division. Second argument can\'t be zero.');

        $floatNumberMath->div($floatNumber1, $floatNumber2);
    }

    /**
     * @dataProvider getDataForDiv
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @param int $outputPrecision
     */
    public function testDiv(
        FloatNumberInterface $expectedNumber,
        FloatNumberInterface $number1,
        FloatNumberInterface $number2,
        int $outputPrecision
    ): void {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->div($number1, $number2));
    }

    public function getDataForDiv(): array
    {
        return [
            [new FloatNumber(2, 0), new FloatNumber(6, 0), new FloatNumber(3, 0), 0],
            [new FloatNumber(2000, 3), new FloatNumber(60, 1), new FloatNumber(300, 2), 3],
            [new FloatNumber(50, 2), new FloatNumber(30, 1), new FloatNumber(600, 2), 2],
            [new FloatNumber(7143, 4), new FloatNumber(5, 1), new FloatNumber(7, 1), 4],
            [new FloatNumber(22, 1), new FloatNumber(510, 2), new FloatNumber(23, 1), 1],
            [new FloatNumber(-22, 1), new FloatNumber(-510, 2), new FloatNumber(23, 1), 1],
            [new FloatNumber(451, 3), new FloatNumber(23, 1), new FloatNumber(510, 2), 3],
        ];
    }

    /**
     * @dataProvider getDataForMul
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
    ): void {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->mul($number1, $number2));
    }

    public function getDataForMul(): array
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

    public function testAbs(): void
    {
        $expectedNumber = new FloatNumber(10050, 2);

        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(2));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);

        $this->assertEquals($expectedNumber, $floatNumberMath->abs(new FloatNumber(10050, 2)));
        $this->assertEquals($expectedNumber, $floatNumberMath->abs(new FloatNumber(-10050, 2)));
    }

    /**
     * @dataProvider getDataForSub
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
    ): void {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->sub($number1, $number2));
    }

    public function getDataForSub(): array
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
     * @param FloatNumberInterface $expectedNumber
     * @param FloatNumberInterface[] $floatNumbersToSum
     * @param int $outputPrecision
     */
    public function testAdd(FloatNumberInterface $expectedNumber, array $floatNumbersToSum, int $outputPrecision): void
    {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $floatNumberMath = new FloatNumberMath($floatNumberFactory);
        $this->assertEquals($expectedNumber, $floatNumberMath->add($floatNumbersToSum));
    }

    public function getDataForAdd(): array
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
