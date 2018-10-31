<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\MoneyPrecisionProvider;
use PHPUnit_Framework_TestCase;

final class MoneyPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPrecision(): void
    {
        $tradeSizePrecision = new MoneyPrecisionProvider();
        $this->assertEquals(2, $tradeSizePrecision->getPrecision());
    }

}
