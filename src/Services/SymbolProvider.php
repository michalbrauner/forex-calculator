<?php

namespace ForexCalculator\Services;

class SymbolProvider implements SymbolProviderInterface
{

    /**
     * @inheritdoc
     */
    public function symbolExists($symbol)
    {
        return in_array(strtoupper($symbol), $this->getSymbols(), true);
    }

    /**
     * @inheritdoc
     */
    public function getSymbols()
    {
        return [
            'EURUSD',
            'EURJPY',
            'EURGBP',
            'EURAUD',
            'EURCAD',
            'USDJPY',
            'USDCAD',
            'USDCAD',
            'GBPUSD',
            'AUDUSD',
            'NZDUSD',
            'NZDJPY',
            'AUDCAD',
            'AUDNZD',
            'CADJPY',
            'GBPAUD',
            'GBPCAD',
            'GBPJPY',
            'GBPNZD',
            'JPYUSD',
            'USDCZK',
            'GBPCHF',
            'EURCHF',
            'USDCHF',
            'EURCHF',
            'GBPCHF',
            'AUDCHF',
            'CHFJPY',
            'EURNZD',
            'CADCHF',
            'NZDCHF',
            'NZDCHF',
            'USDRUB',
            'EURCZK',
        ];
    }

}
