<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\PrecisionProviders\MoneyPrecisionProvider;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use ForexCalculator\Services\FloatNumberMath;
use ForexCalculator\Services\TradeSizeRiskCalculator;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class TradeSizeRiskCalculatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForTestGetLoss
     *
     * @param FloatNumber $expectedLoss
     * @param FloatNumber $input
     * @param FloatNumber $stopLoss
     * @param FloatNumber $profitTarget
     * @param int $numberOfUnits
     * @param string $forexDataProviderPrice
     * @param int $forexDataPrecision
     * @param bool $convertCurrency
     */
    public function testGetLoss(
        FloatNumber $expectedLoss,
        FloatNumber $input,
        FloatNumber $stopLoss,
        FloatNumber $profitTarget,
        int $numberOfUnits,
        string $forexDataProviderPrice,
        int $forexDataPrecision,
        bool $convertCurrency
    ) {
        $tradeSizeRiskCalculator = new TradeSizeRiskCalculator(
            'someCurrency',
            $convertCurrency ? 'someOtherCurrency' : 'someCurrency',
            new FloatNumberFactory(new MoneyPrecisionProvider()),
            $this->getForexDataProvider($forexDataProviderPrice),
            new FloatNumberFactory(new UniversalPrecisionProvider($forexDataPrecision)),
            new FloatNumberMath(new FloatNumberFactory(new UniversalPrecisionProvider($forexDataPrecision)))
        );

        $trade = new Trade($input, $stopLoss, $profitTarget);
        $loss = $tradeSizeRiskCalculator->getLoss($trade, $numberOfUnits);

        $this->assertEquals($expectedLoss, $loss);
    }

    /**
     * @return array
     */
    public function dataForTestGetLoss(): array
    {
        $moneyNumberFactory = new FloatNumberFactory(new MoneyPrecisionProvider());
        $priceFloatNumberFactoryJpy = new FloatNumberFactory(new UniversalPrecisionProvider(3));
        $priceFloatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(4));

        return [
            [
                $moneyNumberFactory->create('200'),
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                100000,
                '1',
                3,
                false,
            ],
            [
                $moneyNumberFactory->create('20'),
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                10000,
                '1',
                3,
                false,
            ],
            [
                $moneyNumberFactory->create('2000'),
                $priceFloatNumberFactoryJpy->create('118.015'),
                $priceFloatNumberFactoryJpy->create('118.017'),
                $priceFloatNumberFactoryJpy->create('118.013'),
                1000000,
                '1',
                3,
                false,
            ],
            [
                $moneyNumberFactory->create('10'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                100000,
                '1',
                5,
                false,
            ],
            [
                $moneyNumberFactory->create('1'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                10000,
                '1',
                5,
                false,
            ],
            [
                $moneyNumberFactory->create('0.1'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03836'),
                $priceFloatNumberFactory->create('1.03856'),
                1000,
                '1',
                5,
                false,
            ],
            [
                $moneyNumberFactory->create('0.10'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                1000,
                '1',
                5,
                false,
            ],
            [
                $moneyNumberFactory->create('11.8'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                1000,
                '118.015',
                5,
                true,
            ],
            [
                $moneyNumberFactory->create('118.02'),
                $priceFloatNumberFactory->create('1.03846'),
                $priceFloatNumberFactory->create('1.03856'),
                $priceFloatNumberFactory->create('1.03836'),
                10000,
                '118.015',
                5,
                true,
            ],
        ];
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

}
