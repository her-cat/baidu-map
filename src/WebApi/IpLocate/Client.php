<?php

namespace HerCat\BaiduMap\WebApi\IpLocate;

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
     * @param string $ipAddress
     * @param string|null $coordinateType
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($ipAddress, $coordinateType = null)
    {
        $params = [
            'ip' => $ipAddress,
        ];

        if (!is_null($coordinateType)) {
            $params['coor'] = $coordinateType;
        }

        return $this->httpGet('location/ip', $params);
    }
}
