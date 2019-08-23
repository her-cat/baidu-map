<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\IpLocate;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\IpLocate\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()
            ->httpGet('location/ip', ['ip' => 'mock-ip', 'coor' => 'mock-type'])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-ip', 'mock-type'));
    }
}
