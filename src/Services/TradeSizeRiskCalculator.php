<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\DataProviders\DataProviderInterface;

class TradeSizeRiskCalculator
{

    /**
     * @var string
     */
    private $inputCurrency;

    /**
     * @var string
     */
    private $outputCurrency;

    /**
     * @var FloatNumberFactory
     */
    private $outputFloatNumberFactory;

    /**
     * @var DataProviderInterface
     */
    private $forexDataProvider;

    /**
     * @var FloatNumberInterface
     */
    private $forexPriceFloatNumberFactory;

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param FloatNumberFactory $outputFloatNumberFactory
     * @param DataProviderInterface $forexDataProvider
     * @param FloatNumberFactory $forexPriceFloatNumberFactory
     */
    public function __construct(
        string $inputCurrency,
        string $outputCurrency,
        FloatNumberFactory $outputFloatNumberFactory,
        DataProviderInterface $forexDataProvider,
        FloatNumberFactory $forexPriceFloatNumberFactory
    ) {
        $this->inputCurrency = $inputCurrency;
        $this->outputCurrency = $outputCurrency;
        $this->outputFloatNumberFactory = $outputFloatNumberFactory;
        $this->forexDataProvider = $forexDataProvider;
        $this->forexPriceFloatNumberFactory = $forexPriceFloatNumberFactory;
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getLoss(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        $precision = $trade->getInput()->getPrecision();
        $lossAsNumber = abs($trade->getInput()->getNumber() - $trade->getStopLoss()->getNumber());

        $loss = $this->outputFloatNumberFactory->createFromNumberAndPrecision(
            $lossAsNumber * $numberOfUnits,
            $precision
        );

        if (strcasecmp($this->inputCurrency, $this->outputCurrency) === 0) {
            return $loss;
        }

        $rateAsString = $this->forexDataProvider->getPrice(
            $this->getSymbolToConvertTo(),
            $this->getPriceTypeToFind($trade)
        );
        $rate = $this->forexPriceFloatNumberFactory->create($rateAsString);

        if ($loss->getPrecision() > $rate->getPrecision()) {
            $lossConverted = $loss;
            $rateConverted = $this->outputFloatNumberFactory->createFromNumberAndPrecision(
                $rate->getNumber(),
                $rate->getPrecision()
            );
        } else {
            $lossConverted = $this->forexPriceFloatNumberFactory->createFromNumberAndPrecision(
                $loss->getNumber(),
                $loss->getPrecision()
            );
            $rateConverted = $rate;
        }

        return $this->outputFloatNumberFactory->createFromNumberAndPrecision(
            $lossConverted->getNumber()
            * $numberOfUnits
            * pow(10, max($loss->getPrecision(), $rate->getPrecision()))
            * $rateConverted->getNumber(),
            max($loss->getPrecision(), $rate->getPrecision())
        );
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getProfit(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        // @todo
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getRiskRewardRatio(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        // @todo
    }

    /**
     * @param Trade $trade
     * @return string
     */
    private function getPriceTypeToFind(Trade $trade): string
    {
        return $trade->getInput()->getNumber() > $trade->getStopLoss()->getNumber()
            ? DataProviderInterface::PRICE_ASK
            : DataProviderInterface::PRICE_BID;
    }

    /**
     * @return string
     */
    private function getSymbolToConvertTo(): string
    {
        return sprintf('%s%s', $this->inputCurrency, $this->outputCurrency);
    }

}
