<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap;

use HerCat\BaiduMap\Kernel\Exceptions\RuntimeException;

/**
 * Class Factory.
 *
 * @author her-cat <i@her-cat.com>
 *
 * @method static WebApi\Application    webApi(array $config)
 */
class Factory
{
    protected static $services = [
        'webApi' => WebApi\Application::class,
    ];

    /**
     * @param string $name
     * @param array  $config
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
     * @param array  $arguments
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
