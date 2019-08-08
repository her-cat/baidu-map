<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Kernel\Providers;

use HerCat\BaiduMap\Kernel\Signature;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class SignatureServiceProvider.
 *
 * @author her-cat <i@her-cat.com>
 */
class SignatureServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['signature'] = function ($app) {
            return new Signature($app);
        };
    }
}
