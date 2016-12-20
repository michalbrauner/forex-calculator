<?php

namespace Tests\ForexCalculator\PrecisionProviders;

use ForexCalculator\PrecisionProviders\MoneyPrecisionProvider;
use PHPUnit_Framework_TestCase;

class MoneyPrecisionProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPrecision()
    {
        $tradeSizePrecision = new MoneyPrecisionProvider();
        $this->assertEquals(2, $tradeSizePrecision->getPrecision());
    }

}
