<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\RiskRewardRatioPrecisionProvider;
use PHPUnit_Framework_TestCase;

class RiskRewardRatioPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPrecision()
    {
        $tradeSizePrecision = new RiskRewardRatioPrecisionProvider();
        $this->assertEquals(2, $tradeSizePrecision->getPrecision());
    }

}
