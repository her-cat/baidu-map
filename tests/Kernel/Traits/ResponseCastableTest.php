<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\Kernel\Traits;

use HerCat\BaiduMap\Kernel\Contracts\Arrayable;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use HerCat\BaiduMap\Kernel\Traits\ResponseCastable;
use HerCat\BaiduMap\Tests\TestCase;

class ResponseCastableTest extends TestCase
{
    public function testCastResponseToType()
    {
        $cls = \Mockery::mock(DummyClassForResponseCastableTest::class);

        $response = new Response(200, [], '{"foo": "bar"}');

        // collection
        $collection = $cls->castResponseToType($response, 'collection');
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertSame(['foo' => 'bar'], $collection->all());

        // array
        $this->assertSame(['foo' => 'bar'], $cls->castResponseToType($response, 'array'));

        // object
        $this->assertSame('bar', $cls->castResponseToType($response, 'object')->foo);

        // raw
        $this->assertInstanceOf(Response::class, $cls->castResponseToType($response, 'raw'));

        // custom class
        // 1.exists
        $dummyResponse = $cls->castResponseToType($response, DummyClassForArrayableTest::class);
        $this->assertInstanceOf(DummyClassForArrayableTest::class, $dummyResponse);
        $this->assertSame(['foo' => 'bar'], $dummyResponse->toArray());

        // 2.not exists
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Config key "response_type" classname must be an instanceof HerCat\BaiduMap\Kernel\Contracts\Arrayable');
        $cls->castResponseToType($response, 'Not\Exists\ClassName');
    }

    public function testDetectAndCastResponseToType()
    {
        $cls = \Mockery::mock(DummyClassForResponseCastableTest::class);

        // response
        $response = new Response(200, [], '{"foo": "bar"}');
        $collection = $cls->detectAndCastResponseToType($response, 'collection');
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertSame(['foo' => 'bar'], $collection->all());

        // array
        $response = ['foo' => 'bar'];
        $this->assertInstanceOf(Collection::class, $cls->detectAndCastResponseToType($response, 'collection'));
        $this->assertSame(['foo' => 'bar'], $cls->detectAndCastResponseToType($response, 'collection')->all());

        // object
        $response = json_decode(json_encode(['foo' => 'bar']));
        $this->assertSame(['foo' => 'bar'], $cls->detectAndCastResponseToType($response, 'array'));

        // string
        $this->assertSame([], $cls->detectAndCastResponseToType('foobar', 'array'));
        $this->assertSame('foobar', $cls->detectAndCastResponseToType('foobar', 'raw')->getBody()->getContents());

        // int
        $this->assertSame([123], $cls->detectAndCastResponseToType(123, 'array'));
        $this->assertSame('123', $cls->detectAndCastResponseToType(123, 'raw')->getBody()->getContents());

        // float
        $this->assertSame([123.01], $cls->detectAndCastResponseToType(123.01, 'array'));
        $this->assertSame('123.01', $cls->detectAndCastResponseToType(123.01, 'raw')->getBody()->getContents());

        // custom
        $response = new DummyClassForArrayableTest();
        $this->assertSame(['foo' => 'bar'], $cls->detectAndCastResponseToType($response, 'array'));

        // exception
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported response type "NULL"');
        $cls->detectAndCastResponseToType(null, 'array');
    }
}

class DummyClassForResponseCastableTest
{
    use ResponseCastable;
}

class DummyClassForArrayableTest implements Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return ['foo' => 'bar'];
    }

    /**
     * Whether a offset exists.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return bool true on success or false on failure.
     *              </p>
     *              <p>
     *              The return value will be casted to boolean if non-boolean was returned.
     *
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed can return all value types
     *
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset.
     *
     * @see https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
