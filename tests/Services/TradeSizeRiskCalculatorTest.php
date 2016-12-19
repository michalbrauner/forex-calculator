<?php

namespace Tests\ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumber;
use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\PrecisionProviders\PricePrecisionProvider;
use ForexCalculator\Services\TradeSizeRiskCalculator;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class TradeSizeRiskCalculatorTest extends PHPUnit_Framework_TestCase
{

    public function testGetLoss()
    {
        $tradeSizeRiskCalculator = new TradeSizeRiskCalculator();

        $pricePrecisionProvider = $this->getPrecisionProvider(2);
        $tradeSizePrecisionProvider = $this->getPrecisionProvider(1);

        $priceFloatNumberFactory = new FloatNumberFactory($pricePrecisionProvider);
        $tradeSizeFloatNumberFactory = new FloatNumberFactory($tradeSizePrecisionProvider);

        $input = $priceFloatNumberFactory->create('1234.50');
        $stopLoss = $priceFloatNumberFactory->create('1234.20');
        $profitTarget = $priceFloatNumberFactory->create('1234.70');

        $tradeSize = $tradeSizeFloatNumberFactory->create('1');

        $trade = new Trade($input, $stopLoss, $profitTarget);
        $loss = $tradeSizeRiskCalculator->getLoss($trade, $tradeSize);
    }

    /**
     * @param int $precision
     * @return PricePrecisionProvider|PHPUnit_Framework_MockObject_MockObject
     */
    private function getPrecisionProvider(int $precision): PricePrecisionProvider
    {
        $precisionProvider = $this->getMockBuilder(PricePrecisionProvider::class)
            ->setMethods(['getPrecision'])
            ->disableOriginalConstructor()
            ->getMock();

        $precisionProvider->method('getPrecision')->willReturn($precision);

        return $precisionProvider;
    }

}
