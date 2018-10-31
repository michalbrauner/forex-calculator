<?php

namespace ForexCalculator\PrecisionProviders;

final class  MoneyPrecisionProvider implements PrecisionProviderInterface
{

    const BASE_PRECISION = 2;

    public function getPrecision(): int
    {
        return self::BASE_PRECISION;
    }

}
