<?php

namespace HerCat\BaiduMap\Tests\WebApi\Geocode;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\Geocode\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()
            ->httpGet('geocoding/v3', ['address' => 'mock-address', 'foo' => 'bar'])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-address', ['foo' => 'bar']));
    }
}
