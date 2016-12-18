<?php

namespace Tests\ForexCalculator\DataProviders;

use ForexCalculator\DataProviders\DataProviderInterface;
use ForexCalculator\DataProviders\YahooDataProvider;
use ForexCalculator\Exceptions\PriceNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class YahooDataProviderTest extends PHPUnit_Framework_TestCase
{
    public function testGetPriceInvalidPriceType()
    {
        $this->expectException(InvalidArgumentException::class);

        $dataProvider = $this->getYahooDataProvider('');
        $dataProvider->getPrice('EURUSD', 'invalidPriceType');
    }

    public function testGetPriceInvalidSymbol()
    {
        $this->expectException(PriceNotFoundException::class);

        $dataProvider = $this->getYahooDataProvider('');
        $dataProvider->getPrice('unknownSymbol', DataProviderInterface::PRICE_ASK);
    }

    /**
     * @dataProvider getDataForGetTickValueForSymbol
     *
     * @param string $expectedPrice
     * @param string $symbol
     * @param string $priceType
     * @param string $mockResponseFile
     */
    public function testGetPrice($expectedPrice, $symbol, $priceType, $mockResponseFile)
    {
        $response = file_get_contents($mockResponseFile);

        $dataProvider = $this->getYahooDataProvider($response);
        $this->assertEquals($expectedPrice, $dataProvider->getPrice($symbol, $priceType));
    }

    /**
     * @return array
     */
    public function getDataForGetTickValueForSymbol()
    {
        $eurUsdMockResponseFile = __DIR__ . '/YahooDataProviderData/eurusd_response.json';
        $eurJpyMockResponseFile = __DIR__ . '/YahooDataProviderData/eurjpy_response.json';

        return [
            ['1.1234', 'EURUSD', DataProviderInterface::PRICE_BID, $eurUsdMockResponseFile],
            ['1.1238', 'EURUSD', DataProviderInterface::PRICE_ASK, $eurUsdMockResponseFile],
            ['1.1234', 'eurusd', DataProviderInterface::PRICE_BID, $eurUsdMockResponseFile],
            ['1.123', 'EURJPY', DataProviderInterface::PRICE_BID, $eurJpyMockResponseFile],
            ['1.123', 'EuRJpY', DataProviderInterface::PRICE_BID, $eurJpyMockResponseFile],
            ['1.125', 'EURJPY', DataProviderInterface::PRICE_ASK, $eurJpyMockResponseFile],
        ];
    }

    /**
     * @param string $response
     * @return YahooDataProvider
     */
    private function getYahooDataProvider($response)
    {
        return new YahooDataProvider($this->getMockHttpClient($response));
    }

    /**
     * @param string $response
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockHttpClient($response)
    {
        $mockRequest = $this->getMockRequest($this->getMockStream($response), 200);

        $mockClient = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockClient->method('request')->willReturn($mockRequest);

        return $mockClient;
    }

    /**
     * @param string $response
     * @return Stream|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockStream($response)
    {
        $mockStream = $this->getMockBuilder(Stream::class)
            ->setMethods(['getContents'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockStream->method('getContents')->willReturn($response);

        return $mockStream;
    }

    /**
     * @param Stream $mockStream
     * @param int $statusCode
     * @return Request|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockRequest(Stream $mockStream, $statusCode)
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getBody', 'getStatusCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest->method('getBody')->willReturn($mockStream);
        $mockRequest->method('getStatusCode')->willReturn($statusCode);

        return $mockRequest;
    }
}
