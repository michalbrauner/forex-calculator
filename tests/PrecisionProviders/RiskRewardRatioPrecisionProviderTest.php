<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\RiskRewardRatioPrecisionProvider;
use PHPUnit_Framework_TestCase;

final class RiskRewardRatioPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPrecision(): void
    {
        $tradeSizePrecision = new RiskRewardRatioPrecisionProvider();
        $this->assertEquals(2, $tradeSizePrecision->getPrecision());
    }

}
