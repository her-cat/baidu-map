<?php

namespace HerCat\BaiduMap\WebApi\BatchRequest;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['batch_request'] = function ($app) {
            return new Client($app);
        };
    }
}
