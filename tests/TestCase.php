<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests;

use HerCat\BaiduMap\Kernel\ServiceContainer;
use Mockery\Mock;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @param string $name
     * @param array|string $methods
     * @param null|ServiceContainer $app
     *
     * @return Mock
     */
    public function mockApiClient($name, $methods = [], $app = null)
    {
        $methods = implode(',', array_merge([
            'httpGet', 'httpPost', 'httpGetStream',
            'request', 'requestRaw', 'registerHttpMiddlewares',
        ], (array) $methods));

        $client = \Mockery::mock($name."[$methods]", [
            !is_null($app) ? $app : \Mockery::mock(ServiceContainer::class),
        ])->shouldAllowMockingProtectedMethods();

        $client->allows()->registerHttpMiddlewares()->andReturnNull();

        return $client;
    }
}
