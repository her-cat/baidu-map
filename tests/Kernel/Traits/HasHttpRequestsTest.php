<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\Kernel\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HerCat\BaiduMap\Kernel\Traits\HasHttpRequests;
use HerCat\BaiduMap\Tests\TestCase;

class HasHttpRequestsTest extends TestCase
{
    public function testDefaultOptions()
    {
        $this->assertEmpty(HasHttpRequests::getDefaultOptions());

        HasHttpRequests::setDefaultOptions(['timeout' => 5.0]);

        $this->assertSame(['timeout' => 5.0], HasHttpRequests::getDefaultOptions());
    }

    public function testHttpClient()
    {
        $request = \Mockery::mock(HasHttpRequests::class);

        $this->assertInstanceOf(ClientInterface::class, $request->getHttpClient());

        $client = \Mockery::mock(Client::class);

        $request->setHttpClient($client);

        $this->assertSame($client, $request->getHttpClient());
    }

    public function testMiddlewares()
    {
        $request = \Mockery::mock(HasHttpRequests::class);

        $this->assertEmpty($request->getMiddlewares());

        $fn1 = function () {
        };
        $fn2 = function () {
        };
        $fn3 = function () {
        };

        $request->pushMiddleware($fn1, 'fn1');
        $request->pushMiddleware($fn2, 'fn2');
        $request->pushMiddleware($fn3);

        $this->assertSame(['fn1' => $fn1, 'fn2' => $fn2, $fn3], $request->getMiddlewares());
    }

    public function testRequest()
    {
        $request = \Mockery::mock(DummnyForHasHttpRequestsTest::class.'[getHandlerStack]');
        $handlerStack = \Mockery::mock(HandlerStack::class);

        $request->allows()->getHandlerStack()->andReturn($handlerStack);

        $client = \Mockery::mock(Client::class);
        $request->setHttpClient($client);

        $response = new Response(200, [], 'mock-result');

        $client->expects()->request('POST', 'foo/bar', [
            'handler' => $handlerStack,
            'base_uri' => 'mock-url',
        ])->andReturn($response);

        $this->assertSame($response, $request->request('foo/bar', 'POST'));
    }

    public function testHandlerStack()
    {
        $request = \Mockery::mock(HasHttpRequests::class);

        $fn1 = function () {
        };
        $request->pushMiddleware($fn1, 'fn1');

        $handlerStack = $request->getHandlerStack();
        $this->assertInstanceOf(HandlerStack::class, $handlerStack);
        $this->assertContains('Name: \'fn1\', Function: callable', (string) $handlerStack);

        $handlerStack2 = \Mockery::mock(HandlerStack::class);
        $request->setHandlerStack($handlerStack2);
        $this->assertSame($handlerStack2, $request->getHandlerStack());
    }
}

class DummnyForHasHttpRequestsTest
{
    use HasHttpRequests;

    protected $baseUri = 'mock-url';
}
