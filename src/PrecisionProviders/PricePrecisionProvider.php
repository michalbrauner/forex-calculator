<?php

namespace ForexCalculator\PrecisionProviders;

class PricePrecisionProvider implements PrecisionProviderInterface
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

    /**
     * @param string $symbol
     * @param bool $extendedPoint
     */
    public function __construct(string $symbol, bool $extendedPoint)
    {
        $this->symbol = $symbol;
        $this->extendedPoint = $extendedPoint;
    }

    /**
     * @inheritdoc
     */
    public function getPrecision(): int
    {
        $basePrecision = $this->containsJpy($this->symbol)
            ? self::BASE_PRECISION_WITH_JPY
            : self::BASE_PRECISION_WITHOUT_JPY;

        return $this->extendedPoint ? $basePrecision + 1 : $basePrecision;
    }

    /**
     * @param string $symbol
     * @return bool
     */
    private function containsJpy(string $symbol): bool
    {
        return stripos($symbol, 'jpy') !== false;
    }

}
