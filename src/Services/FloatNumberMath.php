<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;

class FloatNumberMath
{

    /**
     * @var FloatNumberFactory
     */
    private $floatNumberFactory;

    /**
     * @param FloatNumberFactory $floatNumberFactory
     */
    public function __construct(FloatNumberFactory $floatNumberFactory)
    {
        $this->floatNumberFactory = $floatNumberFactory;
    }

    /**
     * @param FloatNumberInterface[] $floatNumbers
     * @return FloatNumberInterface
     */
    public function sum(array $floatNumbers): FloatNumberInterface
    {
        $numberOfFloatNumbers = count($floatNumbers);

        if ($numberOfFloatNumbers <= 1) {
            return $this->floatNumberFactory->createFromNumberAndPrecision(
                isset($floatNumbers[0]) ? $floatNumbers[0]->getNumber() : 0,
                isset($floatNumbers[0]) ? $floatNumbers[0]->getPrecision() : 0
            );
        }

        $sum = $floatNumbers[0];

        for ($i = 1; $i < $numberOfFloatNumbers; $i++) {
            $sum = $this->add($sum, $floatNumbers[$i]);
        }

        return $this->floatNumberFactory->createFromNumberAndPrecision($sum->getNumber(), $sum->getPrecision());
    }

    /**
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @return FloatNumberInterface
     */
    private function add(FloatNumberInterface $number1, FloatNumberInterface $number2): FloatNumberInterface
    {
        $maxPrecision = max($number1->getPrecision(), $number2->getPrecision());

        $number1_normalized = $this->floatNumberFactory->createWithGivenPrecision(
            $number1->getNumber(),
            $number1->getPrecision(),
            $maxPrecision
        );

        $number2_normalized = $this->floatNumberFactory->createWithGivenPrecision(
            $number2->getNumber(),
            $number2->getPrecision(),
            $maxPrecision
        );

        return $this->floatNumberFactory->createWithGivenPrecision(
            $number1_normalized->getNumber() + $number2_normalized->getNumber(),
            $maxPrecision,
            $maxPrecision
        );
    }

}
