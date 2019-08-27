<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\PlaceSearch;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\PlaceSearch\AbroadClient;

class AbroadClientTest extends TestCase
{
    public function testRegion()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('place_abroad/v1/search', [
            'query' => 'mock-keyword',
            'region' => 'mock-region',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->region('mock-keyword', 'mock-region', ['foo' => 'bar']));
    }

    public function testCircle()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('place_abroad/v1/search', [
            'query' => 'mock-keyword',
            'location' => 'mock-lat,mock-lng',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->circle('mock-keyword', 'mock-lng', 'mock-lat', ['foo' => 'bar']));
    }

    public function testRectangle()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('place_abroad/v1/search', [
            'query' => 'mock-keyword',
            'bounds' => '1,2,3',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->rectangle('mock-keyword', '1,2,3', ['foo' => 'bar']));
        $this->assertSame('mock-result', $client->rectangle('mock-keyword', [1, 2, 3], ['foo' => 'bar']));
    }

    public function testGetWithUid()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('place_abroad/v1/detail', [
            'uid' => 'mock-uid',
            'scope' => 1,
            'output' => 'json',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-uid'));
    }

    public function testGetWithUids()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(AbroadClient::class, [], $app);

        $client->expects()->httpGet('place_abroad/v1/detail', [
            'uids' => '1,2,3',
            'scope' => 1,
            'output' => 'json',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('1,2,3'));
        $this->assertSame('mock-result', $client->get([1, 2, 3]));
    }
}
