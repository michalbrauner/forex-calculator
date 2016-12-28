<?php

require_once('vendor/autoload.php');

//$currencyConverter = new \ForexCalculator\Services\CurrencyConverter(
//    new \ForexCalculator\DataProviders\YahooDataProvider(new \GuzzleHttp\Client())
//);
//
//$eurValueInUsd = $currencyConverter->convertToCurrency('eur', 'usd', 100);
//echo sprintf('100 eur is %.2f usd', $currencyConverter->convertToCurrency('eur', 'usd', 100));

$tradeAttributesCalculatorFactory = new \ForexCalculator\Services\TradeAttributesByTradeSizeCalculatorFactory(
    new \ForexCalculator\DataProviders\YahooDataProvider(new \GuzzleHttp\Client()),
    new \ForexCalculator\PrecisionProviders\MoneyPrecisionProvider(),
    new \ForexCalculator\PrecisionProviders\RiskRewardRatioPrecisionProvider()
);

$symbol = 'eurusd';
$outputCurrency = 'usd';
$extendedPoint = true;

$tradeAttributesCalculator = $tradeAttributesCalculatorFactory->create('eurusd', 'usd', $extendedPoint);

$priceNumberFactory = new \ForexCalculator\DataObjects\FloatNumberFactory(
    new \ForexCalculator\PrecisionProviders\PricePrecisionProvider($symbol, $extendedPoint)
);

$trade = new \ForexCalculator\DataObjects\Trade(
    $priceNumberFactory->create('1.03953'),
    $priceNumberFactory->create('1.03936'),
    $priceNumberFactory->create('1.04016')
);

$loss = $tradeAttributesCalculator->getLoss($trade, 20000);
$profit = $tradeAttributesCalculator->getProfit($trade, 20000);
$riskRewardRatio = $tradeAttributesCalculator->getRiskRewardRatio($trade);

printf("loss = %f\n", $loss->getNumberAsFloat());
printf("profit = %f\n", $profit->getNumberAsFloat());
printf("riskRewardRatio = %f\n", $riskRewardRatio->getNumberAsFloat());

$numberOfUnitsCalculator = new \ForexCalculator\Services\NumberOfUnitsByMaximalLossCalculator(
    $symbol,
    $outputCurrency,
    $extendedPoint,
    $tradeAttributesCalculatorFactory
);

$moneyNumberFactory = new \ForexCalculator\DataObjects\FloatNumberFactory(
    new \ForexCalculator\PrecisionProviders\MoneyPrecisionProvider()
);
$numberOfUnits = $numberOfUnitsCalculator->getNumberOfUnits($trade, $moneyNumberFactory->create('180'));

printf("numberOfUnits = %d\n", $numberOfUnits);

