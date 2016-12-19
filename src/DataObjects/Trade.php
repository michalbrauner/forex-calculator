<?php

namespace ForexCalculator\DataObjects;

class Trade
{

    /**
     * @var FloatNumberInterface
     */
    private $input;

    /**
     * @var FloatNumberInterface
     */
    private $stopLoss;

    /**
     * @var FloatNumberInterface
     */
    private $profitTarget;

    /**
     * @param FloatNumberInterface $input
     * @param FloatNumberInterface $stopLoss
     * @param FloatNumberInterface $profitTarget
     */
    public function __construct(
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ) {
        $this->input = $input;
        $this->stopLoss = $stopLoss;
        $this->profitTarget = $profitTarget;
    }

    /**
     * @return FloatNumberInterface
     */
    public function getInput(): FloatNumberInterface
    {
        return $this->input;
    }

    /**
     * @return FloatNumberInterface
     */
    public function getStopLoss(): FloatNumberInterface
    {
        return $this->stopLoss;
    }

    /**
     * @return FloatNumberInterface
     */
    public function getProfitTarget(): FloatNumberInterface
    {
        return $this->profitTarget;
    }

}
