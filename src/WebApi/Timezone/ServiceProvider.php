<?php

namespace HerCat\BaiduMap\WebApi\Timezone;

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
        $app['timezone'] = function ($app) {
            return new Client($app);
        };
    }
}
