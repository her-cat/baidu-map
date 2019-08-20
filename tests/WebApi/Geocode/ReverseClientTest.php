<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\Geocode;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\Geocode\ReverseClient;

class ReverseClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(ReverseClient::class);

        $client->expects()
            ->httpGet('reverse_geocoding/v3', ['location' => 'mock-lat,mock-lng', 'foo' => 'bar'])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-lng', 'mock-lat', ['foo' => 'bar']));
    }
}
