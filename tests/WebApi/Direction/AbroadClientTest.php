<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\Direction;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\Direction\AbroadClient;

class AbroadClientTest extends TestCase
{
    public function testTransit()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('direction_abroad/v1/transit', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->transit('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->transit(['mock-lat', 'mock-lng'], ['mock-lat2', 'mock-lng2'], ['foo' => 'bar']));
    }

    public function testWalking()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('direction_abroad/v1/walking', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->walking('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->walking(['mock-lat', 'mock-lng'], ['mock-lat2', 'mock-lng2'], ['foo' => 'bar']));
    }

    public function testDriving()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('direction_abroad/v1/driving', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->driving('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->driving(['mock-lat', 'mock-lng'], ['mock-lat2', 'mock-lng2'], ['foo' => 'bar']));
    }
}
