<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\PlaceSearch;

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
class Client extends BaseClient implements PlaceSearchInterface
{
    /**
     * @param string $keyword
     * @param string $region
     * @param array  $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function region($keyword, $region, $options = [])
    {
        $options = array_merge([
            'query' => $keyword,
            'region' => $region,
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('place/v2/search', $options);
    }

    /**
     * @param string       $keyword
     * @param float|string $longitude
     * @param float|string $latitude
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function circle($keyword, $longitude, $latitude, $options = [])
    {
        $options = array_merge([
            'query' => $keyword,
            'location' => sprintf('%s,%s', $latitude, $longitude),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('place/v2/search', $options);
    }

    /**
     * @param string       $keyword
     * @param array|string $bounds
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function rectangle($keyword, $bounds, $options = [])
    {
        $options = array_merge([
            'query' => $keyword,
            'bounds' => implode(',', (array) $bounds),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('place/v2/search', $options);
    }

    /**
     * @param array|string $uid
     * @param int          $scope
     * @param string       $output
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($uid, $scope = 1, $output = 'json')
    {
        $params = [
            'scope' => $scope,
            'output' => $output,
        ];

        $key = (is_array($uid) || false !== stripos($uid, ',')) ? 'uids' : 'uid';

        $params[$key] = implode(',', (array) $uid);

        if ($this->app->config->has('sk')) {
            $params['timestamp'] = time();
        }

        return $this->httpGet('place/v2/detail', $params);
    }
}
