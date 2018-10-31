<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;

final class NumberOfUnitsByMaximalLossCalculator
{

    const NUMBER_OF_UNITS_IN_LOT = 100000;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $outputCurrency;

    /**
     * @var bool
     */
    private $extendedPoint;

    /**
     * @var TradeAttributesByTradeSizeCalculatorFactory
     */
    private $tradeAttributesByTradeSizeCalculatorFactory;

    public function __construct(
        string $symbol,
        string $outputCurrency,
        bool $extendedPoint,
        TradeAttributesByTradeSizeCalculatorFactory $tradeAttributesByTradeSizeCalculatorFactory
    ) {
        $this->symbol = $symbol;
        $this->outputCurrency = $outputCurrency;
        $this->extendedPoint = $extendedPoint;
        $this->tradeAttributesByTradeSizeCalculatorFactory = $tradeAttributesByTradeSizeCalculatorFactory;
    }

    public function getNumberOfUnits(Trade $trade, FloatNumberInterface $maximalRisk): int
    {
        $tradeAttributesCalculator = $this->tradeAttributesByTradeSizeCalculatorFactory->create(
            $this->symbol,
            $this->outputCurrency,
            $this->extendedPoint
        );

        $lossForOneLot = $tradeAttributesCalculator->getLoss($trade, self::NUMBER_OF_UNITS_IN_LOT);

        $numberOfUnitsNumberMath = $this->getNumberOfUnitsFloatNumberMath();
        $numberOfUnitsRoundedNumberFactory = $this->getNumberOfUnitsRoundedFloatNumberFactory();

        $numberOfUnitsForMaximalRisk = $numberOfUnitsNumberMath->mul(
            $numberOfUnitsNumberMath->div($maximalRisk, $lossForOneLot),
            $numberOfUnitsRoundedNumberFactory->create((string)self::NUMBER_OF_UNITS_IN_LOT)
        );

        return (int)round(
            $numberOfUnitsRoundedNumberFactory
                ->createFromNumber($numberOfUnitsForMaximalRisk)
                ->getNumberAsFloat()
        );
    }

    private function getNumberOfUnitsFloatNumberMath(): FloatNumberMath
    {
        return new FloatNumberMath(
            new FloatNumberFactory(new UniversalPrecisionProvider(log10(self::NUMBER_OF_UNITS_IN_LOT)))
        );
    }

    private function getNumberOfUnitsRoundedFloatNumberFactory(): FloatNumberFactory
    {
        return new FloatNumberFactory(new UniversalPrecisionProvider(0));
    }

}

