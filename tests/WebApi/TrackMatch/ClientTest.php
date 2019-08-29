<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\TrackMatch;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\TrackMatch\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpPost('trackmatch/v1/track', [
            'standard_track' => '["one","two"]',
            'track' => '["one","two"]',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('["one","two"]', '["one","two"]', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->get(['one', 'two'], ['one', 'two'], ['foo' => 'bar']));
    }
}
