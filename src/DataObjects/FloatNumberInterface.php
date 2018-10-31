<?php

namespace ForexCalculator\DataObjects;

interface FloatNumberInterface
{

    public function getNumber(): int;

    public function getPrecision(): int;

    public function getNumberAsFloat(): float;

}
