<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\CoordsConvert;

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
     * @param string|array $coords
     * @param int          $from
     * @param int          $to
     * @param string       $output
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($coords, $from = 1, $to = 5, $output = 'json')
    {
        $params = compact('coords', 'from', 'to', 'output');

        if (is_array($params['coords'])) {
            $params['coords'] = implode(';', $params['coords']);
        }

        return $this->httpGet('geoconv/v1/', $params);
    }
}
