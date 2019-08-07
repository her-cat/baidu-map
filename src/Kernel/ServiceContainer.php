<?php

namespace HerCat\BaiduMap\Kernel;

use GuzzleHttp\Client as HttpClient;
use HerCat\BaiduMap\Kernel\Providers\ConfigServiceProvider;
use HerCat\BaiduMap\Kernel\Providers\HttpClientServiceProvider;
use Pimple\Container;

/**
 * Class ServiceContainer.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @property Config     $config
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
                'timeout' => 30.0,
                'base_uri' => 'http://api.map.baidu.com/',
            ]
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
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }
}
