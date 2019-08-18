<?php

namespace HerCat\BaiduMap\WebApi;

use HerCat\BaiduMap\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @property StaticImage\Client     $static_image
 */
class Application extends ServiceContainer
{
    protected $providers = [
        StaticImage\ServiceProvider::class,
    ];
}
