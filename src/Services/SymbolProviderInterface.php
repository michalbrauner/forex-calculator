<?php

namespace ForexCalculator\Services;

interface SymbolProviderInterface
{

    /**
     * @param string $symbol
     * @return bool
     */
    public function symbolExists($symbol);

    /**
     * @return array
     */
    public function getSymbols();

}
