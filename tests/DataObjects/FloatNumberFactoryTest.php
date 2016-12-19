<?php

namespace Tests\ForexCalculator\DataObjects;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\PrecisionProviders\PricePrecisionProvider;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class FloatNumberFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForCreateWithInvalidNumberThrownException
     *
     * @param string $number
     */
    public function testCreateWithInvalidNumberThrownException(string $number)
    {
        $numberFactory = new FloatNumberFactory($this->getPrecisionProvider(1));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Float number is in invalid format. \'xx.yy\' expected, \'%s\' given.', $number)
        );

        $numberFactory->create($number);
    }

    /**
     * @return array
     */
    public function getDataForCreateWithInvalidNumberThrownException()
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
     *
     * @param FloatNumber $expectedNumber
     * @param string $number
     * @param int $precision
     */
    public function testCreate(FloatNumber $expectedNumber, string $number, int $precision)
    {
        $numberFactory = new FloatNumberFactory($this->getPrecisionProvider($precision));
        $this->assertEquals($expectedNumber, $numberFactory->create($number));
    }

    /**
     * @return array
     */
    public function getDataForCreate()
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

    /**
     * @param int $precision
     * @return PricePrecisionProvider|PHPUnit_Framework_MockObject_MockObject
     */
    private function getPrecisionProvider(int $precision): PricePrecisionProvider
    {
        $precisionProvider = $this->getMockBuilder(PricePrecisionProvider::class)
            ->setMethods(['getPrecision'])
            ->disableOriginalConstructor()
            ->getMock();

        $precisionProvider->method('getPrecision')->willReturn($precision);

        return $precisionProvider;
    }

}