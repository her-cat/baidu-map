<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\WebApi\RoadInfoAlert;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use HerCat\BaiduMap\Tests\TestCase;
use HerCat\BaiduMap\WebApi\RoadInfoAlert\Client;

class ClientTest extends TestCase
{
    public function testDetermineSpeeding()
    {
        $app = new ServiceContainer();

        $client = $this->mockApiClient(Client::class, [], $app);

        $points = [
            [
                'loc_time' => 1556162073,
                'coord_type_input' => 'bd09ll',
                'latitude' => 39.950124,
                'longitude' => 115.799985,
                'speed' => 59.9,
            ],
        ];

        $jsonEncoded = json_encode($points);

        $client->expects()
            ->httpGet('api_roadinfo/v1/track', [
                'point_list' => $jsonEncoded,
                'options' => 'vehicle_type:car',
                'coord_type_output' => 'bd09ll',
            ])->andReturn('mock-result');

        $this->assertSame('mock-result', $client->determineSpeeding($points));
        $this->assertSame('mock-result', $client->determineSpeeding($points, 'vehicle_type:car', 'bd09ll'));
        $this->assertSame('mock-result', $client->determineSpeeding($jsonEncoded, 'vehicle_type:car', 'bd09ll'));
    }
}
