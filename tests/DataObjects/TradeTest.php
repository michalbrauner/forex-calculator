<?php

namespace Tests\ForexCalculator\DataObjects;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;
use ForexCalculator\PrecisionProviders\UniversalPrecisionProvider;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

final class TradeTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getDataForCreateTrade
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param FloatNumberInterface $input
     * @param FloatNumberInterface $stopLoss
     * @param FloatNumberInterface $profitTarget
     */
    public function testCreateTrade(
        string $expectedException,
        string $expectedExceptionMessage,
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ): void {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        new Trade($input, $stopLoss, $profitTarget);
    }

    public function getDataForCreateTrade(): array
    {
        $floatNumberFactory = new FloatNumberFactory(new UniversalPrecisionProvider(4));
        $floatNumberFactory2 = new FloatNumberFactory(new UniversalPrecisionProvider(5));

        return [
            [
                InvalidArgumentException::class,
                'Invalid input, stopLoss and profitTarget. The precision has to be the same for all of them.',
                $floatNumberFactory->create('1.5'),
                $floatNumberFactory2->create('1.2'),
                $floatNumberFactory->create('1.8'),
            ],
            [
                InvalidArgumentException::class,
                'Invalid input, stopLoss and profitTarget. Input has to be between stopLoss and profitTarget.',
                $floatNumberFactory->create('1.1'),
                $floatNumberFactory->create('1.2'),
                $floatNumberFactory->create('1.8'),
            ],
            [
                InvalidArgumentException::class,
                'Invalid input, stopLoss and profitTarget. Input has to be between stopLoss and profitTarget.',
                $floatNumberFactory->create('1.9'),
                $floatNumberFactory->create('1.2'),
                $floatNumberFactory->create('1.8'),
            ],
        ];
    }

}
