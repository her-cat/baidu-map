<?php

namespace HerCat\BaiduMap\Tests\WebApi\PlaceSuggest;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\PlaceSuggest\Client;

class ClientTest extends TestCase
{
    public function testGet()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(Client::class, [], $app);

        $client->expects()
            ->httpGet('place/v2/suggestion', [
                'query' => 'mock-keyword',
                'region' => 'mock-region',
            ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->get('mock-keyword', 'mock-region'));
    }
}
