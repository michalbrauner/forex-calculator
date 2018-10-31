<?php

namespace ForexCalculator\DataObjects;

use ForexCalculator\PrecisionProviders\PrecisionProviderInterface;
use InvalidArgumentException;

final class FloatNumberFactory
{

    /**
     * @var PrecisionProviderInterface
     */
    private $precisionProvider;

    public function __construct(PrecisionProviderInterface $precisionProvider)
    {
        $this->precisionProvider = $precisionProvider;
    }

    public function create(string $number): FloatNumberInterface
    {
        $number = trim($number);

        $this->checkNumber($number);

        $precision = $this->precisionProvider->getPrecision();
        $numberFloat = \round(\floatval($number), $precision);

        return new FloatNumber($numberFloat * \pow(10, $precision), $precision);
    }

    private function checkNumber(string $number): void
    {
        if (!\preg_match('/^[0-9]+([.]{1}[0-9]+){0,1}$/', $number)) {
            throw new InvalidArgumentException(
                \sprintf('Float number is in invalid format. \'xx.yy\' expected, \'%s\' given.', $number)
            );
        }
    }

    public function createFromNumber(FloatNumberInterface $number): FloatNumberInterface
    {
        return $this->createFromNumberAndPrecision($number->getNumber(), $number->getPrecision());
    }

    public function createFromNumberAndPrecision(int $number, int $precision): FloatNumberInterface
    {
        return new FloatNumber(
            round($number * \pow(10, $this->precisionProvider->getPrecision() - $precision)),
            $this->precisionProvider->getPrecision()
        );
    }

    public function createWithGivenPrecision(int $number, int $precision, int $outputPrecision): FloatNumberInterface
    {
        return new FloatNumber(\round($number * \pow(10, $outputPrecision - $precision)), $outputPrecision);
    }

    public function getPrecisionProvider(): PrecisionProviderInterface
    {
        return $this->precisionProvider;
    }

}
