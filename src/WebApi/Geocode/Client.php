<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\Geocode;

use HerCat\BaiduMap\Kernel\BaseClient;

/**
 * Class Client.
 *
 * @author her-cat <i@her-cat.com>
 */
class Client extends BaseClient
{
    public function get($address, $options = [])
    {
        $options = array_merge(['address' => $address], $options);

        return $this->httpGet('geocoding/v3', $options);
    }
}
