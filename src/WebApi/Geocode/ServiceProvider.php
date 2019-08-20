<?php

namespace HerCat\BaiduMap\WebApi\Geocode;

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
        $app['geocode'] = function ($app) {
            return new Client($app);
        };

        $app['reverse_geocode'] = function ($app) {
            return new ReverseClient($app);
        };
    }
}
