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
use HerCat\BaiduMap\Kernel\Exceptions\RuntimeException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class LiteClient.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @method array|Response|Collection|mixed|object|ResponseInterface driving($origin, $destination, $options = [])
 * @method array|Response|Collection|mixed|object|ResponseInterface riding($origin, $destination, $options = [])
 * @method array|Response|Collection|mixed|object|ResponseInterface walking($origin, $destination, $options = [])
 * @method array|Response|Collection|mixed|object|ResponseInterface transit($origin, $destination, $options = [])
 */
class LiteClient extends BaseClient
{
    const ALLOWED_METHODS = ['driving', 'riding', 'walking', 'transit'];

    /**
     * @param string $method
     * @param string|array $origin
     * @param string|array $destination
     * @param array $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws InvalidConfigException
     * @throws RuntimeException
     */
    public function execute($method, $origin, $destination, $options = [])
    {
        if (!$this->isAllowedMethod($method)) {
            throw new RuntimeException(sprintf('Method named "%s" not found.', $method));
        }

        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet(sprintf('directionlite/v1/%s', $method), $options);
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws RuntimeException
     */
    public function __call($name, $arguments)
    {
        return $this->execute($name, ...$arguments);
    }

    public function isAllowedMethod($method)
    {
        return \in_array($method, static::ALLOWED_METHODS);
    }
}
