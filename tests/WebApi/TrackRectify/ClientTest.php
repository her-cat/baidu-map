<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\TrackRectify;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\TrackRectify\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpPost('rectify/v1/track', [
            'point_list' => '["one","two"]',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('["one","two"]', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->get(['one', 'two'], ['foo' => 'bar']));
    }
}
