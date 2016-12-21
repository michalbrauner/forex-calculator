<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;

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
     * @var FloatNumberMath
     */
    private $floatNumberMath;

    /**
     * @param string $inputCurrency
     * @param string $outputCurrency
     * @param FloatNumberFactory $outputFloatNumberFactory
     * @param DataProviderInterface $forexDataProvider
     * @param FloatNumberFactory $forexPriceFloatNumberFactory
     * @param FloatNumberMath $floatNumberMath
     */
    public function __construct(
        string $inputCurrency,
        string $outputCurrency,
        FloatNumberFactory $outputFloatNumberFactory,
        DataProviderInterface $forexDataProvider,
        FloatNumberFactory $forexPriceFloatNumberFactory,
        FloatNumberMath $floatNumberMath
    ) {
        $this->inputCurrency = $inputCurrency;
        $this->outputCurrency = $outputCurrency;
        $this->outputFloatNumberFactory = $outputFloatNumberFactory;
        $this->forexDataProvider = $forexDataProvider;
        $this->forexPriceFloatNumberFactory = $forexPriceFloatNumberFactory;
        $this->floatNumberMath = $floatNumberMath;
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getLoss(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        $loss = $this->getLossInInputCurrency($trade, $numberOfUnits);

        if (strcasecmp($this->inputCurrency, $this->outputCurrency) !== 0) {
            $loss = $this->getNumberInOutputCurrency($trade, $loss);
        }

        return $this->outputFloatNumberFactory->createFromNumber($loss);
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

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    private function getLossInInputCurrency(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        return $this->floatNumberMath->mul(
            $this->floatNumberMath->abs($this->floatNumberMath->sub($trade->getInput(), $trade->getStopLoss())),
            $this->getNumberOfUnitsAsNumber($numberOfUnits)
        );
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $loss
     * @return FloatNumberInterface
     */
    private function getNumberInOutputCurrency(Trade $trade, FloatNumberInterface $loss): FloatNumberInterface
    {
        $rateAsString = $this->forexDataProvider->getPrice(
            $this->getSymbolToConvertTo(),
            $this->getPriceTypeToFind($trade)
        );

        return $this->floatNumberMath->mul($loss, $this->forexPriceFloatNumberFactory->create($rateAsString));
    }

    /**
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    private function getNumberOfUnitsAsNumber(int $numberOfUnits): FloatNumberInterface
    {
        return (new FloatNumberFactory(new UniversalPrecisionProvider(0)))->create((string)$numberOfUnits);
    }

}
