<?php

namespace HerCat\BaiduMap\WebApi\StaticImage;

use GuzzleHttp\Exception\GuzzleException;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Http\StreamResponse;
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
     * @param array $options
     *
     * @return array|Response|StreamResponse|Collection|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($longitude, $latitude, $options = [])
    {
        $options = array_merge([
            'center' => sprintf('%s,%s', $longitude, $latitude),
        ], $options);

        return $this->httpGetStream('staticimage/v2', 'GET', $options);
    }
}
