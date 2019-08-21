<?php

namespace HerCat\BaiduMap\Tests\WebApi\RoadTraffic;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\RoadTraffic\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()
            ->httpGet('traffic/v1/road', ['city' => 'mock-city', 'road_name' => 'mock-road-name'])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-city', 'mock-road-name'));
    }
}
