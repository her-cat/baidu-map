<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\Kernel;

use Closure;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;

class BaseClientTest extends TestCase
{
    public function mockClient($methods = [], ServiceContainer $app = null)
    {
        $methods = implode(',', (array) $methods);

        return \Mockery::mock(BaseClient::class."[{$methods}]", [
            !is_null($app) ? $app : \Mockery::mock(ServiceContainer::class),
        ]);
    }

    public function testHttpGet()
    {
        $client = $this->mockClient('request');

        $client->expects()->request('mock-uri', 'GET', ['query' => ['foo' => 'bar']])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->httpGet('mock-uri', ['foo' => 'bar']));
    }

    public function testHttpPost()
    {
        $client = $this->mockClient('request');

        $client->expects()->request('mock-uri', 'POST', ['form_params' => ['foo' => 'bar']])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->httpPost('mock-uri', ['foo' => 'bar']));
    }

    public function testHttpPostJson()
    {
        $client = $this->mockClient('request');

        $client->expects()
            ->request('mock-uri', 'POST', ['json' => ['foo' => 'bar'], 'query' => ['bar' => 'foo']])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->httpPostJson('mock-uri', ['foo' => 'bar'], ['bar' => 'foo']));
    }

    public function testHttpGetStreamWithNonStreamResponse()
    {
        $app = new ServiceContainer(['response_type' => 'array']);

        $client = $this->mockClient('request', $app);

        $response = new Response(200, [], '{"foo":"bar"}');

        $client->expects()
            ->request('mock-url', 'GET', ['query' => ['name' => 'mock-name']], true)
            ->andReturn($response);

        $response = $client->httpGetStream('mock-url', 'GET', ['name' => 'mock-name']);

        $this->assertSame(['foo' => 'bar'], $response);
    }

    public function testRequest()
    {
        $app = new ServiceContainer(['response_type' => 'array']);

        $client = $this->mockClient(['registerHttpMiddlewares', 'performRequest'], $app)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $response = new Response(200, [], '{"foo": "bar"}');

        // default value
        $client->expects()->registerHttpMiddlewares();
        $client->expects()->performRequest('mock-url', 'GET', [])->andReturn($response);
        $this->assertSame(['foo' => 'bar'], $client->request('mock-url'));

        // custom value with return raw response
        $client->expects()->registerHttpMiddlewares();
        $client->expects()->performRequest('mock-url', 'GET', ['name' => 'mock-name'])->andReturn($response);
        $this->assertSame($response, $client->request('mock-url', 'GET', ['name' => 'mock-name'], true));
    }

    public function testRequestRaw()
    {
        $client = $this->mockClient('request');

        $response = new Response(200, [], '{"foo":"bar"}');

        $client->expects()->request('mock-url', 'GET', [], true)->andReturn($response);

        $response = $client->requestRaw('mock-url', 'GET');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('{"foo":"bar"}', $response->toJson());
    }

    public function testRegisterHttpMiddlewares()
    {
        $app = new ServiceContainer();

        $client = $this->mockClient(['pushMiddleware', 'akMiddleware', 'signatureMiddleware', 'logMiddleware'], $app)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $akMiddleware = function () {
            return 'ak';
        };

        $signatureMiddleware = function () {
            return 'signature';
        };

        $logMiddleware = function () {
            return 'log';
        };

        $client->expects()->akMiddlware()->andReturn($akMiddleware);
        $client->expects()->signatureMiddleware()->andReturn($signatureMiddleware);
        $client->expects()->logMiddleware()->andReturn($logMiddleware);
        $client->expects()->pushMiddleware($akMiddleware, 'ak');
        $client->expects()->pushMiddleware($signatureMiddleware, 'signature');
        $client->expects()->pushMiddleware($logMiddleware, 'log');

        $client->registerHttpMiddlewares();
    }

    public function testAkMiddleware()
    {
        $app = new ServiceContainer(['ak' => 'mock-ak']);

        $client = $this->mockClient(['akMiddleware'], $app)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $func = $client->akMiddleware();

        $options = ['foo' => 'bar'];
        $request = new Request('GET', 'mock-url');

        $middleware = $func(function ($request, $options) {
            return compact('request', 'options');
        });

        $result = $middleware($request, $options);

        $this->assertSame('ak=mock-ak', $result['request']->getUri()->getQuery());
        $this->assertSame(['foo' => 'bar'], $result['options']);
    }

    public function testSignatureMiddleware()
    {
        $app = new ServiceContainer(['ak' => 'mock-ak', 'sk' => 'mock-sk']);

        $client = $this->mockClient(['akMiddleware', 'signatureMiddleware'], $app)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $options = ['foo' => 'bar'];
        $request = new Request('GET', 'mock-url');

        $func = $client->akMiddleware();

        $middleware = $func(function ($request, $options) {
            return compact('request', 'options');
        });

        $result = $middleware($request, $options);

        $func = $client->signatureMiddleware();

        $middleware = $func(function ($request, $options) {
            return compact('request', 'options');
        });

        $result = $middleware($result['request'], $result['options']);

        $signature = md5(urlencode('mock-url?ak=mock-akmock-sk'));

        $this->assertSame(
            "ak=mock-ak&sn={$signature}",
            $result['request']->getUri()->getQuery()
        );

        $this->assertSame(['foo' => 'bar'], $result['options']);
    }

    public function testLogMiddleware()
    {
        $app = new ServiceContainer(['ak' => 'mock-ak', 'sk' => 'mock-sk']);

        $client = $this->mockClient('logMiddleware', $app)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $this->assertInstanceOf(Closure::class, $client->logMiddleware());
    }
}
