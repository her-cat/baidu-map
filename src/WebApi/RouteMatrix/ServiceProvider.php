<?php

namespace HerCat\BaiduMap\WebApi\RouteMatrix;

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
        $app['route_matrix'] = function ($app) {
            return new Client($app);
        };
    }
}
