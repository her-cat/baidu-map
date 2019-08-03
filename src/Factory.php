<?php

namespace HerCat\BaiduMap;

use HerCat\BaiduMap\Kernel\Exceptions\RuntimeException;

/**
 * Class Factory.
 *
 * @author her-cat <i@her-cat.com>
 */
class Factory
{
    protected static $services = [
        // name => service
    ];

    /**
     * @param string $name
     * @param array $config
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function make($name, array $config)
    {
        if (isset(self::$services[$name])) {
            return new self::$services[$name]($config);
        }

        throw new RuntimeException(sprintf('No service named "%s".', $name));
    }

    /**
     * @param string $name
     * @param $arguments
     *
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
