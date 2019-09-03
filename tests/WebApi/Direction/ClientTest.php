<?php

namespace HerCat\BaiduMap\Tests\WebApi\Direction;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\Direction\Client;

class ClientTest extends TestCase
{
    public function testTransit()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(Client::class, [], $app);

        $client->expects()->httpGet('direction/v2/transit', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->transit('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
    }

    public function testRiding()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(Client::class, [], $app);

        $client->expects()->httpGet('direction/v2/riding', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->riding('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
    }

    public function testDriving()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(Client::class, [], $app);

        $client->expects()->httpGet('direction/v2/driving', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->driving('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
    }
}
