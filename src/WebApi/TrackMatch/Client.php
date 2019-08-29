<?php

namespace HerCat\BaiduMap\WebApi\TrackMatch;

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
     * @param string|array $target
     * @param array $options
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($origin, $target, array $options = [])
    {
        $options = array_merge([
            'standard_track' => is_array($origin) ? json_encode($origin) : $origin,
            'track' => is_array($target) ? json_encode($target) : $target,
        ], $options);

        return $this->httpPost('trackmatch/v1/track', $options);
    }
}
