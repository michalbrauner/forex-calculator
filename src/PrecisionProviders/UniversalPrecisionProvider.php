<?php

namespace ForexCalculator\PrecisionProviders;

final class UniversalPrecisionProvider implements PrecisionProviderInterface
{

    /**
     * @var int
     */
    private $precision;

    public function __construct(int $precision)
    {
        $this->precision = $precision;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

}
