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
    private $symbol;

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
     * @var FloatNumberFactory
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
     * @param string $symbol
     * @param string $outputCurrency
     * @param FloatNumberFactory $outputFloatNumberFactory
     * @param DataProviderInterface $forexDataProvider
     * @param FloatNumberFactory $forexPriceFloatNumberFactory
     * @param FloatNumberFactory $riskRewardRatioFloatNumberFactory
     * @param FloatNumberMath $floatNumberMath
     */
    public function __construct(
        string $symbol,
        string $outputCurrency,
        FloatNumberFactory $outputFloatNumberFactory,
        DataProviderInterface $forexDataProvider,
        FloatNumberFactory $forexPriceFloatNumberFactory,
        FloatNumberFactory $riskRewardRatioFloatNumberFactory,
        FloatNumberMath $floatNumberMath
    ) {
        $this->symbol = $symbol;
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
        $loss = $this->getNumberInOutputCurrency(
            $trade,
            $this->getDifferenceInInputCurrency($trade->getInput(), $trade->getStopLoss(), $numberOfUnits)
        );

        return $this->outputFloatNumberFactory->createFromNumber($loss);
    }

    /**
     * @param Trade $trade
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    public function getProfit(Trade $trade, int $numberOfUnits): FloatNumberInterface
    {
        $profit = $this->getNumberInOutputCurrency(
            $trade,
            $this->getDifferenceInInputCurrency($trade->getInput(), $trade->getProfitTarget(), $numberOfUnits)
        );

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
     * @param FloatNumberInterface $price1
     * @param FloatNumberInterface $price2
     * @param int $numberOfUnits
     * @return FloatNumberInterface
     */
    private function getDifferenceInInputCurrency(
        FloatNumberInterface $price1,
        FloatNumberInterface $price2,
        int $numberOfUnits
    ): FloatNumberInterface {

        return $this->floatNumberMath->mul(
            $this->floatNumberMath->abs($this->floatNumberMath->sub($price1, $price2)),
            $this->getIntAsFloatNumber($numberOfUnits)
        );
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $loss
     * @return FloatNumberInterface
     */
    private function getNumberInOutputCurrency(Trade $trade, FloatNumberInterface $loss): FloatNumberInterface
    {
        $inputCurrency = substr($this->symbol, -3);

        if ($inputCurrency === $this->outputCurrency) {
            return $loss;
        }

        $symbol = $inputCurrency . $this->outputCurrency;

        $rateAsString = $this->forexDataProvider->getPrice(
            $symbol,
            $this->getPriceTypeToFind($trade)
        );

        return $this->floatNumberMath->mul($loss, $this->forexPriceFloatNumberFactory->create($rateAsString));
    }

    /**
     * @param int $number
     * @return FloatNumberInterface
     */
    private function getIntAsFloatNumber(int $number): FloatNumberInterface
    {
        return (new FloatNumberFactory(new UniversalPrecisionProvider(0)))->create((string)$number);
    }

}
