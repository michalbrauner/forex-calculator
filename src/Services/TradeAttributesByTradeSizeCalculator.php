<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;

class TradeAttributesByTradeSizeCalculator
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
     * @var FloatNumberFactory
     */
    private $riskRewardRatioFloatNumberFactory;

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
     * @param FloatNumberFactory $riskRewardRatioFloatNumberFactory
     * @param FloatNumberMath $floatNumberMath
     */
    public function __construct(
        string $inputCurrency,
        string $outputCurrency,
        FloatNumberFactory $outputFloatNumberFactory,
        DataProviderInterface $forexDataProvider,
        FloatNumberFactory $forexPriceFloatNumberFactory,
        FloatNumberFactory $riskRewardRatioFloatNumberFactory,
        FloatNumberMath $floatNumberMath
    ) {
        $this->inputCurrency = $inputCurrency;
        $this->outputCurrency = $outputCurrency;
        $this->outputFloatNumberFactory = $outputFloatNumberFactory;
        $this->forexDataProvider = $forexDataProvider;
        $this->forexPriceFloatNumberFactory = $forexPriceFloatNumberFactory;
        $this->riskRewardRatioFloatNumberFactory = $riskRewardRatioFloatNumberFactory;
        $this->floatNumberMath = $floatNumberMath;
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getLoss(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        $loss = $this->getDifferenceInInputCurrency($trade->getInput(), $trade->getStopLoss(), $numberOfUnits);

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
        $profit = $this->getDifferenceInInputCurrency($trade->getInput(), $trade->getProfitTarget(), $numberOfUnits);

        if (strcasecmp($this->inputCurrency, $this->outputCurrency) !== 0) {
            $profit = $this->getNumberInOutputCurrency($trade, $profit);
        }

        return $this->outputFloatNumberFactory->createFromNumber($profit);
    }

    /**
     * @param Trade $trade
     * @return FloatNumberInterface
     */
    public function getRiskRewardRatio(Trade $trade): FloatNumberInterface
    {
        $profit = $this->floatNumberMath->abs(
            $this->floatNumberMath->sub($trade->getProfitTarget(), $trade->getInput())
        );

        $loss = $this->floatNumberMath->abs(
            $this->floatNumberMath->sub($trade->getInput(), $trade->getStopLoss())
        );

        return $this->riskRewardRatioFloatNumberFactory->create(
            (string)($profit->getNumber() / $loss->getNumber())
        );
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
     * @param FloatNumberInterface $price1
     * @param FloatNumberInterface $price2
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    private function getDifferenceInInputCurrency(FloatNumberInterface $price1, FloatNumberInterface $price2, int $numberOfUnits): FloatNumberInterface
    {
        return $this->floatNumberMath->mul(
            $this->floatNumberMath->abs($this->floatNumberMath->sub($price1, $price2)),
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
