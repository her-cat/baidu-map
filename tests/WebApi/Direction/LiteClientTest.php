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
use HerCat\BaiduMap\WebApi\Direction\LiteClient;

class LiteClientTest extends TestCase
{
    public function testGet()
    {
        $app = new ServiceContainer([
            'ak' => 'mock-ak',
        ]);

        $client = $this->mockApiClient(LiteClient::class, [], $app);

        $client->expects()->httpGet('directionlite/v1/driving', [
            'origin' => 'mock-lat-1,mock-lng-1',
            'destination' => 'mock-lat-2,mock-lng-2',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-lat-1,mock-lng-1', 'mock-lat-2,mock-lng-2'));
        $this->assertSame('mock-result', $client->get(['mock-lat-1', 'mock-lng-1'], ['mock-lat-2', 'mock-lng-2']));
    }
}
