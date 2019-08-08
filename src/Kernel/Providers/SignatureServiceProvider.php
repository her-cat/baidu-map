<?php

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
