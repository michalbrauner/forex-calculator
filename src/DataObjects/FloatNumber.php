<?php

namespace ForexCalculator\DataObjects;

final class FloatNumber implements FloatNumberInterface
{

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $precision;

    public function __construct(int $number, int $precision)
    {
        $this->number = $number;
        $this->precision = $precision;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function getNumberAsFloat(): float
    {
        return $this->number / pow(10, $this->getPrecision());
    }

}
