<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\BatchRequest;

use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\BatchRequest\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $app = new ServiceContainer([
            'ak' => 'mock-ak',
        ]);

        $client = $this->mockApiClient(Client::class, [], $app);

        $params = [
            [
                'method' => 'get',
                'url' => 'mock-uri-1',
            ],
            [
                'method' => 'get',
                'url' => 'mock-uri-2?ak=mock-ak',
            ],
            [
                'method' => 'post',
                'url' => 'mock-uri-3?output=json&ak=mock-ak',
            ],
        ];

        $client->expects()->httpPostJson('batch', ['reqs' => [
            [
                'method' => 'get',
                'url' => 'mock-uri-1?ak=mock-ak',
            ],
            [
                'method' => 'get',
                'url' => 'mock-uri-2?ak=mock-ak',
            ],
            [
                'method' => 'post',
                'url' => 'mock-uri-3?output=json&ak=mock-ak',
            ],
        ]])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get($params));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The url cannot be empty.');

        $client->get(['foo' => 'bar']);
    }
}
