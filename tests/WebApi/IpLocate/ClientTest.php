<?php

namespace HerCat\BaiduMap\Tests\WebApi\IpLocate;

use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\IpLocate\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $client = $this->mockApiClient(Client::class);

        $client->expects()
            ->httpGet('location/ip', ['ip' => 'mock-ip', 'coor' => 'mock-type'])
            ->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-ip', 'mock-type'));
    }
}
