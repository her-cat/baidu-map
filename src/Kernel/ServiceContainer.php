<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Kernel;

use GuzzleHttp\Client as HttpClient;
use HerCat\BaiduMap\Kernel\Providers\ConfigServiceProvider;
use HerCat\BaiduMap\Kernel\Providers\HttpClientServiceProvider;
use HerCat\BaiduMap\Kernel\Providers\LogServiceProvider;
use Monolog\Logger;
use Pimple\Container;

/**
 * Class ServiceContainer.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @property Config     $config
 * @property Logger     $logger
 * @property HttpClient $http_client
 */
class ServiceContainer extends Container
{
    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var array
     */
    private $defaultProviders = [
        ConfigServiceProvider::class,
        LogServiceProvider::class,
        HttpClientServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $userConfig = [];

    /**
     * @var array
     */
    protected $defaultConfig = [];

    /**
     * ServiceContainer constructor.
     *
     * @param array $config
     * @param array $prepends
     */
    public function __construct(array $config = [], array $prepends = [])
    {
        parent::__construct($prepends);

        $this->userConfig = $config;

        $this->registerProviders($this->getProviders());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            'http' => [
                'timeout' => 10.0,
                'base_uri' => 'http://api.map.baidu.com/',
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    /**
     * @param array $providers
     */
    protected function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return array_merge($this->defaultProviders, $this->providers);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}
