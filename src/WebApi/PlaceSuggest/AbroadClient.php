<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\PlaceSuggest;

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
     * @param string $keyword
     * @param string $region
     * @param string $coordinateType
     * @param string $output
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get($keyword, $region, $coordinateType = 'bd09ll', $output = 'json')
    {
        $params = [
            'query' => $keyword,
            'region' => $region,
            'ret_coordtype' => $coordinateType,
            'output' => $output,
        ];

        if ($this->app->config->has('sk')) {
            $params['timestamp'] = time();
        }

        return $this->httpGet('place_abroad/v1/suggestion', $params);
    }
}
