<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;

class NumberOfUnitsByMaximalLossCalculator
{

    const NUMBER_OF_UNITS_IN_LOT = 100000;

    /**
     * @var string
     */
    private $inputCurrency;

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

    /**
     * @var FloatNumberMath
     */
    private $moneyFloatNumberMath;

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param bool $extendedPoint
     * @param TradeAttributesByTradeSizeCalculatorFactory $tradeAttributesByTradeSizeCalculatorFactory
     * @param FloatNumberMath $moneyFloatNumberMath
     */
    public function __construct(
        string $inputCurrency,
        string $outputCurrency,
        bool $extendedPoint,
        TradeAttributesByTradeSizeCalculatorFactory $tradeAttributesByTradeSizeCalculatorFactory,
        FloatNumberMath $moneyFloatNumberMath
    ) {
        $this->inputCurrency = $inputCurrency;
        $this->outputCurrency = $outputCurrency;
        $this->extendedPoint = $extendedPoint;
        $this->tradeAttributesByTradeSizeCalculatorFactory = $tradeAttributesByTradeSizeCalculatorFactory;
        $this->moneyFloatNumberMath = $moneyFloatNumberMath;
    }

    /**
     * @param Trade $trade
     * @param FloatNumber $maximalRisk
     * @return int
     */
    public function getNumberOfUnits(Trade $trade, FloatNumber $maximalRisk): int
    {
        $tradeAttributesCalculator = $this->tradeAttributesByTradeSizeCalculatorFactory->create(
            $this->inputCurrency,
            $this->outputCurrency,
            $this->extendedPoint
        );

        $lossForOneLot = $tradeAttributesCalculator->getLoss($trade, self::NUMBER_OF_UNITS_IN_LOT);

        $numberOfUnitsNumberFactory = $this->getNumberOfUnitsFloatNumberFactory();

        $numberOfUnitsForMaximalRisk = $this->moneyFloatNumberMath->mul(
            $this->moneyFloatNumberMath->div($maximalRisk, $lossForOneLot),
            $numberOfUnitsNumberFactory->create((string)self::NUMBER_OF_UNITS_IN_LOT)
        );

        return (int)round(
            $numberOfUnitsNumberFactory
                ->createFromNumber($numberOfUnitsForMaximalRisk)
                ->getNumberAsFloat()
        );
    }

    /**
     * @return FloatNumberFactory
     */
    private function getNumberOfUnitsFloatNumberFactory(): FloatNumberFactory
    {
        return new FloatNumberFactory(new UniversalPrecisionProvider(0));
    }

}

