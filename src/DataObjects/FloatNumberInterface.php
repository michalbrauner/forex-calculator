<?php

namespace ForexCalculator\DataObjects;

interface FloatNumberInterface
{

    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @return int
     */
    public function getPrecision(): int;

    /**
     * @return float
     */
    public function getNumberAsFloat(): float;

}
