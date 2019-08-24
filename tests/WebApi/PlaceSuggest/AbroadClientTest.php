<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\PlaceSuggest;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\PlaceSuggest\AbroadClient;

class AbroadClientTest extends TestCase
{
    public function testGet()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()
            ->httpGet('place_abroad/v1/suggestion', [
                'query' => 'mock-keyword',
                'region' => 'mock-region',
                'ret_coordtype' => 'mock-type',
                'output' => 'mock-output',
            ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-keyword', 'mock-region', 'mock-type', 'mock-output'));
    }
}
