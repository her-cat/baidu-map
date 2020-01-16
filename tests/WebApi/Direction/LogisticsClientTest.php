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
use HerCat\BaiduMap\WebApi\Direction\LogisticsClient;

class LogisticsClientTest extends TestCase
{
    public function testGet()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(LogisticsClient::class, [], $app);

        $client->expects()->httpGet('logistics_direction/v1/truck', [
            'origin' => 'mock-lat,mock-lng',
            'destination' => 'mock-lat2,mock-lng2',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-lat,mock-lng', 'mock-lat2,mock-lng2', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->get(['mock-lat', 'mock-lng'], ['mock-lat2', 'mock-lng2'], ['foo' => 'bar']));
    }
}
