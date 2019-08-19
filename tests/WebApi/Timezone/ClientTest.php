<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\Timezone;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\Timezone\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $timestamp = time();

        $client->expects()
            ->httpGet('timezone/v1', [
                'location' => 'mock-lat,mock-long',
                'timestamp' => $timestamp,
                'coord_type' => 'bd09ll',
            ])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-long', 'mock-lat', $timestamp));
    }
}
