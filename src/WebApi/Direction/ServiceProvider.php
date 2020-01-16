<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\WebApi\Direction;

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
        $app['direction'] = function ($app) {
            return new Client($app);
        };

        $app['direction_lite'] = function ($app) {
            return new LiteClient($app);
        };

        $app['direction_abroad'] = function ($app) {
            return new AbroadClient($app);
        };

        $app['direction_logistics'] = function ($app) {
            return new LogisticsClient($app);
        };
    }
}
