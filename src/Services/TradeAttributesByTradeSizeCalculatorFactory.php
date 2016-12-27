<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\PrecisionProviders\PrecisionProviderInterface;
use ForexCalculator\PrecisionProviders\PricePrecisionProvider;

class TradeAttributesByTradeSizeCalculatorFactory
{

    /**
     * @var DataProviderInterface
     */
    private $forexDataProvider;

    /**
     * @var PrecisionProviderInterface
     */
    private $moneyPrecisionProvider;

    /**
     * @var PrecisionProviderInterface
     */
    private $riskRewardRatioPrecisionProvider;

    /**
     * @param DataProviderInterface $forexDataProvider
     * @param PrecisionProviderInterface $moneyPrecisionProvider
     * @param PrecisionProviderInterface $riskRewardRatioPrecisionProvider
     */
    public function __construct(
        DataProviderInterface $forexDataProvider,
        PrecisionProviderInterface $moneyPrecisionProvider,
        PrecisionProviderInterface $riskRewardRatioPrecisionProvider
    ) {
        $this->forexDataProvider = $forexDataProvider;
        $this->moneyPrecisionProvider = $moneyPrecisionProvider;
        $this->riskRewardRatioPrecisionProvider = $riskRewardRatioPrecisionProvider;
    }

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param bool $extendedPoint
     * @return TradeAttributesByTradeSizeCalculator
     */
    public function create(string $inputCurrency, string $outputCurrency, bool $extendedPoint)
    {
        $forexPriceFloatNumberFactory = new FloatNumberFactory(
            new PricePrecisionProvider($inputCurrency . $outputCurrency, $extendedPoint)
        );

        $tradeAttributesByTradeSizeCalculator = new TradeAttributesByTradeSizeCalculator(
            $inputCurrency,
            $outputCurrency,
            new FloatNumberFactory($this->moneyPrecisionProvider),
            $this->forexDataProvider,
            $forexPriceFloatNumberFactory,
            new FloatNumberFactory($this->riskRewardRatioPrecisionProvider),
            new FloatNumberMath($forexPriceFloatNumberFactory)
        );

        return $tradeAttributesByTradeSizeCalculator;
    }

}
