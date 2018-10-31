<?php

namespace ForexCalculator\DataObjects;

use InvalidArgumentException;

final class Trade
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

        if ($this->tradeContainsValidNumbers($input, $stopLoss, $profitTarget)) {
            throw new InvalidArgumentException(
                'Invalid input, stopLoss and profitTarget. Input has to be between stopLoss and profitTarget.'
            );
        }
    }

    private function tradeContainsValidNumbers(
        FloatNumberInterface $input,
        FloatNumberInterface $stopLoss,
        FloatNumberInterface $profitTarget
    ): bool {
        return ($input->getNumber() > $profitTarget->getNumber() && $input->getNumber() > $stopLoss->getNumber())
            || ($input->getNumber() < $profitTarget->getNumber() && $input->getNumber() < $stopLoss->getNumber());
    }

    public function getInput(): FloatNumberInterface
    {
        return $this->input;
    }

    public function getStopLoss(): FloatNumberInterface
    {
        return $this->stopLoss;
    }

    public function getProfitTarget(): FloatNumberInterface
    {
        return $this->profitTarget;
    }

}
