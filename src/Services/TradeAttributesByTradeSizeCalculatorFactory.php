<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\PrecisionProviders\PrecisionProviderInterface;
use ForexCalculator\PrecisionProviders\PricePrecisionProvider;

final class TradeAttributesByTradeSizeCalculatorFactory
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

    public function __construct(
        DataProviderInterface $forexDataProvider,
        PrecisionProviderInterface $moneyPrecisionProvider,
        PrecisionProviderInterface $riskRewardRatioPrecisionProvider
    ) {
        $this->forexDataProvider = $forexDataProvider;
        $this->moneyPrecisionProvider = $moneyPrecisionProvider;
        $this->riskRewardRatioPrecisionProvider = $riskRewardRatioPrecisionProvider;
    }

    public function create(string $symbol, string $outputCurrency, bool $extendedPoint)
    {
        $forexPriceFloatNumberFactory = new FloatNumberFactory(
            new PricePrecisionProvider($symbol, $extendedPoint)
        );

        $tradeAttributesByTradeSizeCalculator = new TradeAttributesByTradeSizeCalculator(
            $symbol,
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
