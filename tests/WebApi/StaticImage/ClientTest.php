<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\StaticImage;

use HerCat\BaiduMap\Kernel\Http\StreamResponse;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\StaticImage\Client;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class ClientTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('testing'));
    }

    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $body = file_get_contents(STUBS_ROOT.'/files/image.png');
        $response = new StreamResponse(200, ['Content-Type' => 'image/png'], $body);

        $client->expects()
            ->httpGetStream('staticimage/v2', 'GET', ['center' => 'foo,bar'])
            ->andReturn($response);

        $stream = $client->get('foo', 'bar');
        $stream->save(vfsStream::url('testing'), 'test');

        $this->assertInstanceOf(StreamResponse::class, $stream);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('test.png'));
    }
}
