<?php

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
