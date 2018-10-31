<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\TradeSizePrecisionProvider;
use PHPUnit_Framework_TestCase;

final class TradeSizePrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPrecision(): void
    {
        $tradeSizePrecision = new TradeSizePrecisionProvider();
        $this->assertEquals(2, $tradeSizePrecision->getPrecision());
    }

}
