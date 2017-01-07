[![Build Status](https://img.shields.io/shippable/5845d4e6307b1f0f004fb54d/master.svg)](https://app.shippable.com/projects/5845d4e6307b1f0f004fb54d/status/dashboard)

## About

Forex-calculator is a library in order to make calculations related to Forex. It provides basic calculators that can be used to improve your money management. Library requires **PHP 7**.

## Installation
```
composer install michalbrauner/forex-calculator:~v1.0.0
```

## Usage

### Currency converter

```php
$currencyConverter = new \ForexCalculator\Services\CurrencyConverter(
  new \ForexCalculator\DataProviders\YahooDataProvider(new \GuzzleHttp\Client())
);

# Convert 100 eur to usd

$convertedValue = $currencyConverter->convertToCurrency('eur', 'usd', 100);
```

### Profit, loss and RRR calculator

```php
# Create calculator factory

$tradeAttributesCalculatorFactory = new \ForexCalculator\Services\TradeAttributesByTradeSizeCalculatorFactory(
    new \ForexCalculator\DataProviders\YahooDataProvider(new \GuzzleHttp\Client()),
    new \ForexCalculator\PrecisionProviders\MoneyPrecisionProvider(),
    new \ForexCalculator\PrecisionProviders\RiskRewardRatioPrecisionProvider()
);

# Calculator settings

$symbol = 'eurusd';
$outputCurrency = 'usd';
$extendedPoint = true;

$tradeAttributesCalculator = $tradeAttributesCalculatorFactory->create('eurusd', 'usd', $extendedPoint);

# Factory to create prices to trade

$priceNumberFactory = new \ForexCalculator\DataObjects\FloatNumberFactory(
    new \ForexCalculator\PrecisionProviders\PricePrecisionProvider($symbol, $extendedPoint)
);

$trade = new \ForexCalculator\DataObjects\Trade(
    $priceNumberFactory->create('1.03953'),
    $priceNumberFactory->create('1.03936'),
    $priceNumberFactory->create('1.04016')
);

# Loss and profit for trade and size 20000 units

$loss = $tradeAttributesCalculator->getLoss($trade, 20000);
$profit = $tradeAttributesCalculator->getProfit($trade, 20000);

$riskRewardRatio = $tradeAttributesCalculator->getRiskRewardRatio($trade);
```

### Number of units by maximal risk calculator

```php
...

$numberOfUnitsCalculator = new \ForexCalculator\Services\NumberOfUnitsByMaximalLossCalculator(
    $symbol,
    $outputCurrency,
    $extendedPoint,
    $tradeAttributesCalculatorFactory
);

$moneyNumberFactory = new \ForexCalculator\DataObjects\FloatNumberFactory(
    new \ForexCalculator\PrecisionProviders\MoneyPrecisionProvider()
);

# Number of units to trade to risk 180 usd per trade

$numberOfUnits = $numberOfUnitsCalculator->getNumberOfUnits($trade, $moneyNumberFactory->create('180'));
```
