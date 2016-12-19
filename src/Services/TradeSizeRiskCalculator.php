<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;

class TradeSizeRiskCalculator
{

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     */
    public function getLoss(Trade $trade, FloatNumberInterface $tradeSize)
    {
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     */
    public function getProfit(Trade $trade, FloatNumberInterface $tradeSize)
    {
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     */
    public function getRiskRewardRatio(Trade $trade, FloatNumberInterface $tradeSize)
    {
    }

}
