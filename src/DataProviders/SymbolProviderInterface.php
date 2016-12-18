<?php

namespace ForexCalculator\DataProviders;

interface SymbolProviderInterface
{
    /**
     * @param string $symbol
     * @return bool
     */
    public function symbolExists(string $symbol);

    /**
     * @return array
     */
    public function getSymbols();
}
