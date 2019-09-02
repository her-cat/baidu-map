<?php

namespace HerCat\BaiduMap\WebApi\CoordsConvert;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ServiceProvider.
 *
 * @author her-cat <i@her-cat.com>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['coords_convert'] = function ($app) {
            return new Client($app);
        };
    }
}
