<?php

namespace HerCat\BaiduMap\Tests;

use HerCat\BaiduMap\Factory;
use HerCat\BaiduMap\Kernel\Exceptions\RuntimeException;

class FactoryTest extends TestCase
{
    public function testStaticCallWithRuntimeException()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('No service named "mockService".');

        Factory::mockService(['foo' => 'bar']);
    }

    public function testMakeWithRuntimeException()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('No service named "mockService".');

        Factory::make('mockService', ['foo' => 'bar']);
    }
}
