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

use HerCat\BaiduMap\Kernel\BaseClient;

class LogisticsClient extends BaseClient
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
    public function get($origin, $destination, array $options = [])
    {
        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('logistics_direction/v1/truck', $options);
    }
}
