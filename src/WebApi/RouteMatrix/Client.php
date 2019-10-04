<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\RouteMatrix;

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
     * @param string|array $origins
     * @param string|array $destinations
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function driving($origins, $destinations, array $options = [])
    {
        $options = array_merge([
            'origins' => $this->processCoordinate($origins),
            'destinations' => $this->processCoordinate($destinations),
        ], $options);

        return $this->httpGet('routematrix/v2/driving', $options);
    }

    /**
     * @param string|array $origins
     * @param string|array $destinations
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function riding($origins, $destinations, array $options = [])
    {
        $options = array_merge([
            'origins' => $this->processCoordinate($origins),
            'destinations' => $this->processCoordinate($destinations),
        ], $options);

        return $this->httpGet('routematrix/v2/riding', $options);
    }

    /**
     * @param string|array $origins
     * @param string|array $destinations
     * @param array        $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function walking($origins, $destinations, array $options = [])
    {
        $options = array_merge([
            'origins' => $this->processCoordinate($origins),
            'destinations' => $this->processCoordinate($destinations),
        ], $options);

        return $this->httpGet('routematrix/v2/walking', $options);
    }

    /**
     * @param string|array $coordinate
     *
     * @return string
     */
    protected function processCoordinate($coordinate)
    {
        if (is_object($coordinate)) {
            $coordinate = (array) $coordinate;
        } elseif (!is_array($coordinate)) {
            return $coordinate;
        }

        $coordinate = array_map(function ($value) {
            return is_array($value) ? implode(',', $value) : $value;
        }, $coordinate);

        return implode('|', $coordinate);
    }
}
