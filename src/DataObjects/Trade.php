<?php

namespace ForexCalculator\DataObjects;

class Trade
{

    /**
     * @var string
     */
    private $symbol;

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
     * @param string $symbol
     * @param FloatNumberInterface $input
     * @param FloatNumberInterface $stopLoss
     * @param FloatNumberInterface $profitTarget
     */
    public function __construct(
        string $symbol,
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ) {
        $this->symbol = $symbol;
        $this->input = $input;
        $this->stopLoss = $stopLoss;
        $this->profitTarget = $profitTarget;
    }

}
