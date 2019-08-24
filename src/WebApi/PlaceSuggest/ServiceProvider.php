<?php

namespace HerCat\BaiduMap\WebApi\PlaceSuggest;

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
        $app['place_suggest'] = function ($app) {
            return new Client($app);
        };

        $app['abroad_place_suggest'] = function ($app) {
            return new AbroadClient($app);
        };
    }
}
