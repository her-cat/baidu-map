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

use GuzzleHttp\Psr7\Request;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Kernel\Signature;
use HerCat\BaiduMap\Tests\TestCase;
use Psr\Http\Message\UriInterface;

class SignatureTest extends TestCase
{
    /**
     * @return Signature
     *
     * @throws InvalidArgumentException
     */
    public function getSignature()
    {
        $app = new ServiceContainer([
            'ak' => 'mock-ak',
            'sk' => 'mock-sk',
        ]);

        return new Signature($app);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testBasicFeature()
    {
        $app = new ServiceContainer([]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The \'ak\' not configured.');
        $this->expectExceptionCode(0);

        new Signature($app);

        $signature = $this->getSignature();

        $this->assertSame('mock-ak', $signature->getAk());
        $this->assertSame('mock-sk', $signature->getSk());

        $signature->setAk('mock-new-ak');
        $signature->setSk('mock-new-sk');

        $this->assertSame('mock-new-ak', $signature->getAk());
        $this->assertSame('mock-new-sk', $signature->getSk());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testMake()
    {
        $signature = $this->getSignature();

        $this->assertSame(
            md5(urlencode('mock-uri?name=mock-name&foo=barmock-sk')),
            $signature->make('mock-uri', 'GET', ['name' => 'mock-name', 'foo' => 'bar'])
        );

        $this->assertSame(
            md5(urlencode('mock-uri?foo=bar&name=mock-namemock-sk')),
            $signature->make('mock-uri', 'POST', ['name' => 'mock-name', 'foo' => 'bar'])
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testApplyAkToRequest()
    {
        $signature = $this->getSignature();

        $request = new Request('GET', 'mock-uri?name=mock-name');

        $request = $signature->applyAkToRequest($request);

        $this->assertInstanceOf(UriInterface::class, $request);

        $this->assertSame('name=mock-name&ak=mock-ak', $request->getQuery());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testApplySignatureToRequest()
    {
        $signature = $this->getSignature();

        $request = new Request('POST', 'mock-uri', [], 'name=mock-name&ak=mock-ak');

        $request = $signature->applySignatureToRequest($request);

        $this->assertInstanceOf(Request::class, $request);

        $sn = $signature->make('mock-uri', 'POST', ['name' => 'mock-name', 'ak' => 'mock-ak']);

        $this->assertSame("name=mock-name&ak=mock-ak&sn={$sn}", $request->getBody()->getContents());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testApplyParamsToRequest()
    {
        $signature = $this->getSignature();

        $request = new Request('GET', 'mock-uri?foo=bar');
        $request = $signature->applyParamsToRequest($request, ['foo' => 'bar'], ['name' => 'mock-name']);

        $this->assertInstanceOf(UriInterface::class, $request);
        $this->assertSame('foo=bar&name=mock-name', $request->getQuery());

        $request = new Request('POST', 'mock-uri', [], 'foo=bar');
        $request = $signature->applyParamsToRequest($request, ['foo' => 'bar'], ['name' => 'mock-name']);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame('foo=bar&name=mock-name', $request->getBody()->getContents());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetParams()
    {
        $signature = $this->getSignature();

        $request = new Request('GET', 'mock-uri?foo=bar');
        $this->assertSame(['foo' => 'bar'], $signature->getParams($request));

        $request = new Request('POST', 'mock-uri', [], 'foo=bar');
        $this->assertSame(['foo' => 'bar'], $signature->getParams($request));
    }
}
