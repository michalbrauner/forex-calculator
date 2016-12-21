<?php

namespace ForexCalculator\PrecisionProviders;

class UniversalPrecisionProvider implements PrecisionProviderInterface
{

    /**
     * @var int
     */
    private $precision;

    /**
     * @param int $precision
     */
    public function __construct(int $precision)
    {
        $this->precision = $precision;
    }

    /**
     * @inheritdoc
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

}
