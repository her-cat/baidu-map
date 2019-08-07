<?php

namespace HerCat\BaiduMap\Kernel\Providers;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HttpClientServiceProvider.
 *
 * @author her-cat <i@her-cat.com>
 */
class HttpClientServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $app)
    {
        $app['http_client'] = function ($app) {
            return new Client($app->config->get('http', []));
        };
    }
}
