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

final class YahooDataProviderTest extends PHPUnit_Framework_TestCase
{

    public function testGetPriceInvalidPriceType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dataProvider = $this->getYahooDataProvider('');
        $dataProvider->getPrice('EURUSD', 'invalidPriceType');
    }

    public function testGetPriceInvalidSymbol(): void
    {
        $this->expectException(PriceNotFoundException::class);

        $dataProvider = $this->getYahooDataProvider('');
        $dataProvider->getPrice('unknownSymbol', DataProviderInterface::PRICE_ASK);
    }

    /**
     * @dataProvider getDataForGetTickValueForSymbol
     * @param string $expectedPrice
     * @param string $symbol
     * @param string $priceType
     * @param string $mockResponseFile
     */
    public function testGetPrice($expectedPrice, $symbol, $priceType, $mockResponseFile): void
    {
        $response = file_get_contents($mockResponseFile);

        $dataProvider = $this->getYahooDataProvider($response);
        $this->assertEquals($expectedPrice, $dataProvider->getPrice($symbol, $priceType));
    }

    public function getDataForGetTickValueForSymbol(): array
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

    private function getYahooDataProvider(string $response): YahooDataProvider
    {
        return new YahooDataProvider($this->getMockHttpClient($response));
    }

    /**
     * @param string $response
     * @return Client|PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockHttpClient(string $response): Client
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
    private function getMockStream(string $response): Stream
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
    private function getMockRequest(Stream $mockStream, int $statusCode): Request
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
