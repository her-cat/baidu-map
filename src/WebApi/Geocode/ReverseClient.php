<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\Geocode;

use GuzzleHttp\Exception\GuzzleException;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ReverseClient.
 *
 * @author her-cat <i@her-cat.com>
 */
class ReverseClient extends BaseClient
{
    /**
     * @param string|float $longitude
     * @param string|float $latitude
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($longitude, $latitude, $options = [])
    {
        $options = array_merge([
            'location' => sprintf('%s,%s', $latitude, $longitude),
        ], $options);

        return $this->httpGet('reverse_geocoding/v3', $options);
    }
}
