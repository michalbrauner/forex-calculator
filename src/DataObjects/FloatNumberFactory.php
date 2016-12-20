<?php

namespace ForexCalculator\DataObjects;

use ForexCalculator\PrecisionProviders\PrecisionProviderInterface;
use InvalidArgumentException;

class FloatNumberFactory
{

    /**
     * @var PrecisionProviderInterface
     */
    private $precisionProvider;

    /**
     * @param PrecisionProviderInterface $precisionProvider
     */
    public function __construct(PrecisionProviderInterface $precisionProvider)
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
     * @param int $number
     * @param int $precision
     * @return FloatNumber
     */
    public function createFromNumberAndPrecision(int $number, int $precision): FloatNumber
    {
        return new FloatNumber(
            round($number * pow(10, $this->precisionProvider->getPrecision() - $precision)),
            $this->precisionProvider->getPrecision()
        );
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
