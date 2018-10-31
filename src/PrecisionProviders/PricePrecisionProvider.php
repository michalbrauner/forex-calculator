<?php

namespace ForexCalculator\PrecisionProviders;

final class PricePrecisionProvider implements PrecisionProviderInterface
{

    const BASE_PRECISION_WITHOUT_JPY = 4;
    const BASE_PRECISION_WITH_JPY = 2;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var bool
     */
    private $extendedPoint;

    public function __construct(string $symbol, bool $extendedPoint)
    {
        $this->symbol = $symbol;
        $this->extendedPoint = $extendedPoint;
    }

    public function getPrecision(): int
    {
        $basePrecision = $this->containsJpy($this->symbol)
            ? self::BASE_PRECISION_WITH_JPY
            : self::BASE_PRECISION_WITHOUT_JPY;

        return $this->extendedPoint ? $basePrecision + 1 : $basePrecision;
    }

    private function containsJpy(string $symbol): bool
    {
        return stripos($symbol, 'jpy') !== false;
    }

}
