<?php

namespace Tests\ForexCalculator\DataObjects;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

final class FloatNumberFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForCreateWithGivenPrecision
     * @param FloatNumberInterface $expectedNumber
     * @param int $number
     * @param int $numberPrecision
     * @param int $outputPrecision
     */
    public function testCreateWithGivenPrecision(
        FloatNumberInterface $expectedNumber,
        int $number,
        int $numberPrecision,
        int $outputPrecision
    ): void {
        $numberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(0));
        $this->assertEquals(
            $expectedNumber,
            $numberFactory->createWithGivenPrecision($number, $numberPrecision, $outputPrecision)
        );
    }

    public function getDataForCreateWithGivenPrecision(): array
    {
        return [
            [new FloatNumber(12340, 2), 1234, 1, 2],
            [new FloatNumber(123, 0), 1234, 1, 0],
            [new FloatNumber(1235, 1), 12345, 2, 1],
        ];
    }

    /**
     * @dataProvider getDataForCreateFromNumberAndPrecision
     * @param FloatNumber $expectedNumber
     * @param int $number
     * @param int $numberPrecision
     * @param int $outputPrecision
     */
    public function testCreateFromNumberAndPrecision(
        FloatNumber $expectedNumber,
        int $number,
        int $numberPrecision,
        int $outputPrecision
    ): void {
        $numberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($outputPrecision));
        $this->assertEquals($expectedNumber, $numberFactory->createFromNumberAndPrecision($number, $numberPrecision));
        $this->assertEquals(
            $expectedNumber,
            $numberFactory->createFromNumber(new FloatNumber($number, $numberPrecision))
        );
    }

    public function getDataForCreateFromNumberAndPrecision(): array
    {
        return [
            [new FloatNumber(12340, 2), 1234, 1, 2],
            [new FloatNumber(123, 1), 1234, 2, 1],
            [new FloatNumber(1235, 1), 12345, 2, 1],
            [new FloatNumber(123, 0), 12345, 2, 0],
            [new FloatNumber(12300, 2), 123, 0, 2],
        ];
    }

    /**
     * @dataProvider getDataForCreateWithInvalidNumberThrownException
     * @param string $number
     */
    public function testCreateWithInvalidNumberThrownException(string $number): void
    {
        $numberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(1));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Float number is in invalid format. \'xx.yy\' expected, \'%s\' given.', $number)
        );

        $numberFactory->create($number);
    }

    public function getDataForCreateWithInvalidNumberThrownException(): array
    {
        return [
            [''],
            ['125,6'],
            ['someText 125.6'],
            ['125.6 someText'],
        ];
    }

    /**
     * @dataProvider getDataForCreate
     * @param FloatNumber $expectedNumber
     * @param string $number
     * @param int $precision
     */
    public function testCreate(FloatNumber $expectedNumber, string $number, int $precision): void
    {
        $numberFactory = new FloatNumberFactory(new UniversalPrecisionProvider($precision));
        $this->assertEquals($expectedNumber, $numberFactory->create($number));
    }

    public function getDataForCreate(): array
    {
        return [
            [new FloatNumber(1234, 1), '123.4', 1],
            [new FloatNumber(12345, 2), '123.45', 2],
            [new FloatNumber(123, 0), '123.45', 0],
            [new FloatNumber(12346, 2), '123.456', 2],
            [new FloatNumber(12300, 2), '123', 2],
            [new FloatNumber(12345, 2), '  123.45  ', 2],
            [new FloatNumber(12345, 2), "\t123.45\t", 2],
        ];
    }

}
