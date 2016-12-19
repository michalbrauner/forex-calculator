<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberInterface;
use ForexCalculator\DataObjects\Trade;

class TradeSizeRiskCalculator
{

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     * @return FloatNumberInterface
     */
    public function getLoss(Trade $trade, FloatNumberInterface $tradeSize): FloatNumberInterface
    {
        // @todo
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     * @return FloatNumberInterface
     */
    public function getProfit(Trade $trade, FloatNumberInterface $tradeSize): FloatNumberInterface
    {
        // @todo
    }

    /**
     * @param Trade $trade
     * @param FloatNumberInterface $tradeSize
     * @return FloatNumberInterface
     */
    public function getRiskRewardRatio(Trade $trade, FloatNumberInterface $tradeSize): FloatNumberInterface
    {
        // @todo
    }

}
