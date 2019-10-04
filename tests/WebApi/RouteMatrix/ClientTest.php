<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\RouteMatrix;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\RouteMatrix\Client;

class ClientTest extends TestCase
{
    public function testDriving()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpGet('routematrix/v2/driving', [
            'origins' => 'mock-origins',
            'destinations' => 'mock-destinations',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->driving('mock-origins', 'mock-destinations', ['foo' => 'bar']));
    }

    public function testRiding()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpGet('routematrix/v2/riding', [
            'origins' => 'mock-origins',
            'destinations' => 'mock-destinations',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->riding('mock-origins', 'mock-destinations', ['foo' => 'bar']));
    }

    public function testWalking()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpGet('routematrix/v2/walking', [
            'origins' => 'mock-origins',
            'destinations' => 'mock-destinations',
            'foo' => 'bar',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->walking('mock-origins', 'mock-destinations', ['foo' => 'bar']));
    }

    public function testProcessCoordinate()
    {
        $class = new \ReflectionClass(Client::class);

        $instance = $class->newInstance(new ServiceContainer());

        $method = $class->getMethod('processCoordinate');
        $method->setAccessible(true);

        $std = new \stdClass();
        $std->foo = 'bar';

        $this->assertSame('foo', $method->invoke($instance, 'foo'));
        $this->assertSame(1234, $method->invoke($instance, 1234));
        $this->assertSame('bar', $method->invoke($instance, $std));
        $this->assertSame('mock-lat1,mock-lng1|mock-lat2,mock-lng2', $method->invoke($instance, ['mock-lat1,mock-lng1', 'mock-lat2,mock-lng2']));
        $this->assertSame('mock-lat1,mock-lng1|mock-lat2,mock-lng2', $method->invoke($instance, [['mock-lat1', 'mock-lng1'], ['mock-lat2', 'mock-lng2']]));
    }
}
