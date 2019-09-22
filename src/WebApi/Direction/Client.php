<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\Direction;

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
     * @param string|array $origin
     * @param string|array $destination
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function transit($origin, $destination, array $options = [])
    {
        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('direction/v2/transit', $options);
    }

    /**
     * @param string|array $origin
     * @param string|array $destination
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function riding($origin, $destination, array $options = [])
    {
        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('direction/v2/riding', $options);
    }

    /**
     * @param string|array $origin
     * @param string|array $destination
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function driving($origin, $destination, array $options = [])
    {
        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('direction/v2/driving', $options);
    }
}
