<?php

namespace ForexCalculator\DataObjects;

use InvalidArgumentException;

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
     * @internal param string $symbol
     */
    public function __construct(
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ) {
        $this->checkInputData($input, $stopLoss, $profitTarget);

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

    /**
     * @param FloatNumberInterface $input
     * @param FloatNumberInterface $stopLoss
     * @param FloatNumberInterface $profitTarget
     */
    private function checkInputData(
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ) {

        if ($input->getPrecision() !== $stopLoss->getPrecision()
            || $stopLoss->getPrecision() !== $profitTarget->getPrecision()
        ) {
            throw new InvalidArgumentException(
                'Invalid input, stopLoss and profitTarget. The precision has to be the same for all of them.'
            );
        }

        if (
            ($input->getNumber() > $profitTarget->getNumber() && $input->getNumber() > $stopLoss->getNumber())
            || ($input->getNumber() < $profitTarget->getNumber() && $input->getNumber() < $stopLoss->getNumber())
        ) {
            throw new InvalidArgumentException(
                'Invalid input, stopLoss and profitTarget. Input has to be between stopLoss and profitTarget.'
            );
        }
    }

}
