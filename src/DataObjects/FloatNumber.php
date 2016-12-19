<?php

namespace ForexCalculator\DataObjects;

class FloatNumber implements FloatNumberInterface
{

    /**
     * @var int
     */
    private $number;

    /**
     * @var int
     */
    private $precision;

    /**
     * @param int $number
     * @param int $precision
     */
    public function __construct(int $number, int $precision)
    {
        $this->number = $number;
        $this->precision = $precision;
    }

    /**
     * @inheritdoc
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @inheritdoc
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

}
