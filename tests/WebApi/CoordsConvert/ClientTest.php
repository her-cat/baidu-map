<?php

namespace HerCat\BaiduMap\Tests\WebApi\CoordsConvert;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\CoordsConvert\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()->httpGet('geoconv/v1/', [
            'coords' => 'mock-lng-1,mock-lat-1;mock-lng-2,mock-lat-2',
            'from' => 1,
            'to' => 5,
            'output' => 'json',
        ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-lng-1,mock-lat-1;mock-lng-2,mock-lat-2'));
        $this->assertSame('mock-result', $client->get(['mock-lng-1,mock-lat-1', 'mock-lng-2,mock-lat-2']));
    }
}
