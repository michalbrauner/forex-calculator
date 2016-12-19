<?php
namespace ForexCalculator\PrecisionProviders;

interface PrecisionProviderInterface
{

    /**
     * @return int
     */
    public function getPrecision(): int;

}
