<?php

namespace HerCat\BaiduMap\WebApi\Direction;

use GuzzleHttp\Exception\GuzzleException;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbroadClient.
 *
 * @author her-cat <i@her-cat.com>
 */
class AbroadClient extends BaseClient
{
    /**
     * @param string|array $origin
     * @param string|array $destination
     * @param array $options
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

        return $this->httpGet('direction_abroad/v1/transit', $options);
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
    public function walking($origin, $destination, array $options = [])
    {
        $options = array_merge([
            'origin' => implode(',', (array) $origin),
            'destination' => implode(',', (array) $destination),
        ], $options);

        if ($this->app->config->has('sk')) {
            $options['timestamp'] = time();
        }

        return $this->httpGet('direction_abroad/v1/walking', $options);
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

        return $this->httpGet('direction_abroad/v1/driving', $options);
    }
}
