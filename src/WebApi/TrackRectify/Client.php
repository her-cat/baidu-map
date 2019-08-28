<?php

namespace HerCat\BaiduMap\WebApi\TrackRectify;

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
     * @param string|array $points
     * @param array $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($points, array $options = [])
    {
        $options = array_merge([
            'point_list' => is_array($points) ? json_encode($points) : $points,
            ], $options);

         return $this->httpPost('rectify/v1/track', $options);
    }
}
