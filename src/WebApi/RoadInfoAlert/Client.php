<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\RoadInfoAlert;

use GuzzleHttp\Exception\GuzzleException;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client.
 *
 * @author her-cat <i@her-cat.com>
 */
class Client extends BaseClient
{
    /**
     * @param array|string $points
     * @param string $carType
     * @param string $coordinateType
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function determineSpeeding($points, $carType = 'vehicle_type:car', $coordinateType = 'bd09ll')
    {
        $query = [
            'point_list' => is_array($points) ? json_encode($points) : $points,
            'options' => $carType,
            'coord_type_output' => $coordinateType,
        ];

        return $this->httpGet('api_roadinfo/v1/track', $query);
    }
}
