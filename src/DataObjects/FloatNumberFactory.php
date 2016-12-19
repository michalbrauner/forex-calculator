<?php

namespace ForexCalculator\DataObjects;

use ForexCalculator\PrecisionProviders\PricePrecisionProvider;
use InvalidArgumentException;

class FloatNumberFactory
{

    /**
     * @var PricePrecisionProvider
     */
    private $precisionProvider;

    /**
     * @param PricePrecisionProvider $precisionProvider
     * @internal param string $symbol
     */
    public function __construct(PricePrecisionProvider $precisionProvider)
    {
        $this->precisionProvider = $precisionProvider;
    }

    /**
     * @param string $number
     * @return FloatNumber
     */
    public function create(string $number): FloatNumber
    {
        $number = trim($number);

        $this->checkNumber($number);

        $precision = $this->precisionProvider->getPrecision();
        $numberFloat = round(floatval($number), $precision);

        return new FloatNumber($numberFloat * pow(10, $precision), $precision);
    }

    /**
     * @param string $number
     */
    private function checkNumber(string $number): void
    {
        if (!preg_match('/^[0-9]+([.]{1}[0-9]+){0,1}$/', $number)) {
            throw new InvalidArgumentException(
                sprintf('Float number is in invalid format. \'xx.yy\' expected, \'%s\' given.', $number)
            );
        }
    }

}
