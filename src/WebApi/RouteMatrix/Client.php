<?php

namespace HerCat\BaiduMap\WebApi\RouteMatrix;

use GuzzleHttp\Exception\GuzzleException;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @method driving($origins, $destinations, array $options = [])
 * @method riding($origins, $destinations, array $options = [])
 * @method walking($origins, $destinations, array $options = [])
 */
class Client extends BaseClient
{
    /**
     * @param string $name
     * @param array $arguments
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     * @throws InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, ['driving', 'riding', 'walking'])) {
            throw new InvalidArgumentException('Invalid method "%s".', $name);
        }

        if (count($arguments) < 2) {
            throw new InvalidArgumentException('Invalid arguments');
        }

        $options = isset($arguments[2]) ? $arguments[2] : [];

        $options = array_merge([
            'origins' => $this->processCoordinate($arguments[0]),
            'destinations' => $this->processCoordinate($arguments[1]),
        ], $options);

        return $this->httpGet(sprintf('routematrix/v2/%s', $name), $options);
    }

    protected function processCoordinate($coordinate)
    {
        if (is_object($coordinate)) {
            $coordinate = (array) $coordinate;
        } else if (!is_array($coordinate)) {
            return $coordinate;
        }

        $coordinate = array_map(function ($value) {
            return is_array($value) ? implode(',', $value) : $value;
        }, $coordinate);

        return implode('|', $coordinate);
    }
}
