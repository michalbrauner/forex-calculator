<?php

namespace ForexCalculator\Services;

use ForexCalculator\DataObjects\FloatNumberFactory;
use ForexCalculator\DataObjects\FloatNumberInterface;
use InvalidArgumentException;

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
     * @param FloatNumberInterface $floatNumber
     * @return FloatNumberInterface
     */
    public function abs(FloatNumberInterface $floatNumber): FloatNumberInterface
    {
        return $this->floatNumberFactory->createFromNumberAndPrecision(
            abs($floatNumber->getNumber()),
            $floatNumber->getPrecision()
        );
    }

    /**
     * @param FloatNumberInterface $floatNumber1
     * @param FloatNumberInterface $floatNumber2
     * @return FloatNumberInterface
     */
    public function mul(FloatNumberInterface $floatNumber1, FloatNumberInterface $floatNumber2): FloatNumberInterface
    {
        return $this->floatNumberFactory->createFromNumberAndPrecision(
            $floatNumber1->getNumber() * $floatNumber2->getNumber(),
            $floatNumber1->getPrecision() + $floatNumber2->getPrecision()
        );
    }

    /**
     * @param FloatNumberInterface $floatNumber1
     * @param FloatNumberInterface $floatNumber2
     * @return FloatNumberInterface
     */
    public function div(FloatNumberInterface $floatNumber1, FloatNumberInterface $floatNumber2): FloatNumberInterface
    {
        if ($floatNumber2->getNumber() === 0) {
            throw new InvalidArgumentException('Invalid division. Second argument can\'t be zero.');
        }

        $maxPrecision = max(
            $floatNumber1->getPrecision(),
            $floatNumber2->getPrecision(),
            $this->floatNumberFactory->getPrecisionProvider()->getPrecision()
        );

        list($number1_normalized, $number2_normalized) = $this->getNormalizedNumbers(
            $floatNumber1,
            $floatNumber2,
            $maxPrecision
        );

        $dividedNumber = $number1_normalized->getNumber() / $number2_normalized->getNumber();
        $addedPrecisionToIntPart = round(
            $dividedNumber * pow(
                10,
                $this->floatNumberFactory->getPrecisionProvider()->getPrecision()
            )
        );

        $finalPrecision = $number1_normalized->getPrecision()
            - $number2_normalized->getPrecision()
            + $this->floatNumberFactory->getPrecisionProvider()->getPrecision();

        return $this->floatNumberFactory->createFromNumberAndPrecision($addedPrecisionToIntPart, $finalPrecision);
    }

    /**
     * @param FloatNumberInterface[] $floatNumbers
     * @return FloatNumberInterface
     */
    public function add(array $floatNumbers): FloatNumberInterface
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
            $sum = $this->addTwoNumbers($sum, $floatNumbers[$i]);
        }

        return $this->floatNumberFactory->createFromNumberAndPrecision($sum->getNumber(), $sum->getPrecision());
    }

    /**
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @return FloatNumberInterface
     */
    public function sub(FloatNumberInterface $number1, FloatNumberInterface $number2): FloatNumberInterface
    {
        $maxPrecision = max(
            $number1->getPrecision(),
            $number2->getPrecision(),
            $this->floatNumberFactory->getPrecisionProvider()->getPrecision()
        );

        list($number1_normalized, $number2_normalized) = $this->getNormalizedNumbers($number1, $number2, $maxPrecision);

        return $this->floatNumberFactory->createFromNumberAndPrecision(
            $number1_normalized->getNumber() - $number2_normalized->getNumber(),
            $maxPrecision
        );
    }

    /**
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @return FloatNumberInterface
     */
    private function addTwoNumbers(FloatNumberInterface $number1, FloatNumberInterface $number2): FloatNumberInterface
    {
        $maxPrecision = max($number1->getPrecision(), $number2->getPrecision());

        list($number1_normalized, $number2_normalized) = $this->getNormalizedNumbers($number1, $number2, $maxPrecision);

        return $this->floatNumberFactory->createWithGivenPrecision(
            $number1_normalized->getNumber() + $number2_normalized->getNumber(),
            $maxPrecision,
            $maxPrecision
        );
    }

    /**
     * @param FloatNumberInterface $number1
     * @param FloatNumberInterface $number2
     * @param $precision
     * @return array
     */
    private function getNormalizedNumbers(
        FloatNumberInterface $number1,
        FloatNumberInterface $number2,
        $precision
    ): array {
        $number1_normalized = $this->floatNumberFactory->createWithGivenPrecision(
            $number1->getNumber(),
            $number1->getPrecision(),
            $precision
        );

        $number2_normalized = $this->floatNumberFactory->createWithGivenPrecision(
            $number2->getNumber(),
            $number2->getPrecision(),
            $precision
        );

        return [$number1_normalized, $number2_normalized];
    }

}
