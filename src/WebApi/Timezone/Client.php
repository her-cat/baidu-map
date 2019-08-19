<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\Timezone;

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
     * @param string|float $longitude
     * @param string|float $latitude
     * @param int|null     $timestamp
     * @param string       $coordinateType
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($longitude, $latitude, $timestamp = null, $coordinateType = 'bd09ll')
    {
        $params = [
            'location' => sprintf('%s,%s', $latitude, $longitude),
            'timestamp' => !is_null($timestamp) ? $timestamp : time(),
            'coord_type' => $coordinateType,
        ];

        return $this->httpGet('timezone/v1', $params);
    }
}
