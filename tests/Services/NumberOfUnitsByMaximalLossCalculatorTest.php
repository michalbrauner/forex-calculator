<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\PrecisionProviders\MoneyPrecisionProvider;
use ForexCalculator\PrecisionProviders\RiskRewardRatioPrecisionProvider;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use ForexCalculator\Services\FloatNumberMath;
use ForexCalculator\Services\NumberOfUnitsByMaximalLossCalculator;
use ForexCalculator\Services\TradeAttributesByTradeSizeCalculator;
use ForexCalculator\Services\TradeAttributesByTradeSizeCalculatorFactory;
use PHPUnit_Framework_MockObject_MockObject;

class NumberOfUnitsByMaximalLossCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForGetNumberOfUnits
     *
     * @param int $expectedNumberOfUnits
     * @param FloatNumber $input
     * @param FloatNumber $stopLoss
     * @param FloatNumber $profitTarget
     * @param FloatNumber $maximalRisk
     * @param string $forexDataProviderPrice
     * @param int $forexDataPrecision
     * @param bool $convertCurrency
     */
    public function testGetNumberOfUnits(
        int $expectedNumberOfUnits,
        FloatNumber $input,
        FloatNumber $stopLoss,
        FloatNumber $profitTarget,
        FloatNumber $maximalRisk,
        string $forexDataProviderPrice,
        int $forexDataPrecision,
        bool $convertCurrency
    ) {

        $Calculator = $this->getCalculator(
            $forexDataProviderPrice,
            $forexDataPrecision,
            $convertCurrency
        );

        $trade = new Trade($input, $stopLoss, $profitTarget);
        $numberOfUnits = $Calculator->getNumberOfUnits($trade, $maximalRisk);

        $this->assertEquals($expectedNumberOfUnits, $numberOfUnits);
    }

    /**
     * @return array
     */
    public function getDataForGetNumberOfUnits()
    {
        $moneyNumberFactory = new FloatNumberFactory(new MoneyPrecisionProvider());
        $priceFloatNumberFactoryJpy = new FloatNumberFactory(new UniversalPrecisionProvider(3));
        $priceFloatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(4));

        return [
            [
                100000,
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                $moneyNumberFactory->create('200'),
                '1',
                3,
                false,
            ],
            [
                10000,
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                $moneyNumberFactory->create('20'),
                '1',
                3,
                false,
            ],
            [
                1000000,
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                $moneyNumberFactory->create('2000'),
                '1',
                3,
                false,
            ],
            [
                100000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                $moneyNumberFactory->create('10'),
                '1',
                5,
                false,
            ],
            [
                10000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                $moneyNumberFactory->create('1'),
                '1',
                5,
                false,
            ],
            [
                1000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                $moneyNumberFactory->create('0.1'),
                '1',
                5,
                false,
            ],
            [
                1000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                $moneyNumberFactory->create('0.10'),
                '1',
                5,
                false,
            ],
            [
                1000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                $moneyNumberFactory->create('11.8'),
                '118.015',
                5,
                true,
            ],
            [
                10000,
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                $moneyNumberFactory->create('118.02'),
                '118.015',
                5,
                true,
            ],
        ];
    }

    /**
     * @param string $forexDataProviderPrice
     * @param int $forexDataPrecision
     * @param bool $convertCurrency
     * @return NumberOfUnitsByMaximalLossCalculator
     */
    private function getCalculator(
        string $forexDataProviderPrice,
        int $forexDataPrecision,
        bool $convertCurrency
    ): NumberOfUnitsByMaximalLossCalculator {

        $symbol = 'eurusd';
        $outputCurrency = $convertCurrency ? 'nzd' : 'usd';

        $extendedPoint = false;

        $moneyFloatNumberFactory = new FloatNumberFactory(new MoneyPrecisionProvider());

        $tradeAttributesCalculatorFactory = $this->getTradeAttributesCalculatorFactory(
            $forexDataProviderPrice,
            $forexDataPrecision,
            $symbol,
            $outputCurrency,
            $moneyFloatNumberFactory
        );

        return new NumberOfUnitsByMaximalLossCalculator(
            $symbol,
            $outputCurrency,
            $extendedPoint,
            $tradeAttributesCalculatorFactory
        );
    }

    /**
     * @param string $price
     * @return DataProviderInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function getForexDataProvider(string $price): DataProviderInterface
    {
        $forexDataProvider = $this->getMockBuilder(YahooDataProvider::class)
            ->setMethods(['getPrice'])
            ->disableOriginalConstructor()
            ->getMock();

        $forexDataProvider->method('getPrice')->willReturn($price);

        return $forexDataProvider;
    }

    /**
     * @param string $forexDataProviderPrice
     * @param int $forexDataPrecision
     * @param string $symbol
     * @param $outputCurrency
     * @param $moneyFloatNumberFactory
     * @return TradeAttributesByTradeSizeCalculatorFactory|PHPUnit_Framework_MockObject_MockObject
     */
    private function getTradeAttributesCalculatorFactory(
        string $forexDataProviderPrice,
        int $forexDataPrecision,
        string $symbol,
        $outputCurrency,
        $moneyFloatNumberFactory
    ): TradeAttributesByTradeSizeCalculatorFactory {

        $tradeAttributesCalculator = new TradeAttributesByTradeSizeCalculator(
            $symbol,
            $outputCurrency,
            $moneyFloatNumberFactory,
            $this->getForexDataProvider($forexDataProviderPrice),
            new FloatNumberFactory(new UniversalPrecisionProvider($forexDataPrecision)),
            new FloatNumberFactory(new RiskRewardRatioPrecisionProvider()),
            new FloatNumberMath(new FloatNumberFactory(new UniversalPrecisionProvider($forexDataPrecision)))
        );

        $tradeAttributesCalculatorFactory = $this->getMockBuilder(TradeAttributesByTradeSizeCalculatorFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $tradeAttributesCalculatorFactory->method('create')->willReturn($tradeAttributesCalculator);

        return $tradeAttributesCalculatorFactory;
    }

}
