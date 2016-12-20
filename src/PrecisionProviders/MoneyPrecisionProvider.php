<?php

namespace ForexCalculator\PrecisionProviders;

class  MoneyPrecisionProvider implements PrecisionProviderInterface
{

    const BASE_PRECISION = 2;

    /**
     * @inheritdoc
     */
    public function getPrecision(): int
    {
        return self::BASE_PRECISION;
    }

}
