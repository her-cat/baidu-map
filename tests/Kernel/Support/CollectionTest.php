<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Tests\Kernel\Support;

use HerCat\BaiduMap\Kernel\Support\Collection;
use HerCat\BaiduMap\Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testAll()
    {
        $array = ['foo' => 'bar'];

        $collection = new Collection($array);

        $this->assertSame($array, $collection->all());
    }

    public function testFirst()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $this->assertSame('mock-name', $collection->first());
        $this->assertSame(false, (new Collection())->first());
    }

    public function testLast()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $this->assertSame('mock-email', $collection->last());
        $this->assertSame(false, (new Collection())->last());
    }

    public function testOnly()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email', 'foo' => 'bar']);

        $this->assertSame([], $collection->only('mock-key'));
        $this->assertSame(['name' => 'mock-name'], $collection->only('name'));
        $this->assertSame(['name' => 'mock-name', 'foo' => 'bar'], $collection->only(['name', 'foo']));
    }

    public function testExcept()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email', 'foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $collection->except('name', 'email')->all());
        $this->assertSame(['foo' => 'bar'], $collection->except('name', 'email', 'no-exists')->all());
        $this->assertSame(['foo' => 'bar'], $collection->except(['name', 'email'])->all());
        $this->assertSame(['email' => 'mock-email', 'foo' => 'bar'], $collection->except('name')->all());
    }

    public function testHas()
    {
        $collection = new Collection([
            'name' => 'mock-name',
            'email' => 'mock-email',
            'arr' => [
                [
                    'key' => 'value1',
                ],
                [
                    'key' => 'value2',
                ],
            ],
        ]);

        $this->assertTrue($collection->has('name'));
        $this->assertTrue($collection->has('name', 'email'));
        $this->assertTrue($collection->has(['name', 'email']));

        $this->assertFalse($collection->has('no-exists'));
        $this->assertFalse($collection->has(['name', 'no-exists']));
        $this->assertFalse($collection->has('name', 'no-exists'));

        $this->assertTrue($collection->has('arr.0.key', 'arr.1.key'));

        $collection->forget('arr.0.key');

        $this->assertFalse($collection->has('arr.0.key', 'arr.1.key'));
    }

    public function testForget()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $collection->forget('name');
        $this->assertNull($collection->name);

        $collection->forget(['email', 'email']);
        $this->assertNull($collection->email);
        $this->assertNull($collection->email);
    }

    public function testToArray()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $this->assertSame(['name' => 'mock-name', 'email' => 'mock-email'], $collection->toArray());
    }

    public function testToJson()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $this->assertSame(json_encode(['name' => 'mock-name', 'email' => 'mock-email']), $collection->toJson());
        $this->assertSame(json_encode(['name' => 'mock-name', 'email' => 'mock-email']), (string) $collection);
        $this->assertSame(json_encode(['name' => 'mock-name', 'email' => 'mock-email']), json_encode($collection));
    }

    public function testCount()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $this->assertSame(2, $collection->count());

        $collection->foo = 'bar';
        $this->assertSame(3, $collection->count());

        unset($collection->foo);
        $this->assertSame(2, $collection->count());
    }

    public function testSerialize()
    {
        $collection = new Collection(['name' => 'mock-name', 'email' => 'mock-email']);

        $serialized = serialize($collection);
        $unserialzed = unserialize($serialized);

        $this->assertSame(['name' => 'mock-name', 'email' => 'mock-email'], $unserialzed->all());
    }

    public function testBasicFeatures()
    {
        $array = [
            'name' => 'mock-name',
            'email' => 'mock-email',
            'foo' => 'bar',
            'arr' => [
                [
                    'key' => 'value1',
                ],
                [
                    'key' => 'value2',
                ],
            ],
        ];

        $collection = new Collection($array);

        $this->assertSame('mock-name', $collection->name);
        $this->assertSame('mock-name', $collection['name']);
        $this->assertSame('mock-name', $collection->get('name'));

        $this->assertTrue(isset($collection['name']), 'isset $collection[\'name\']');
        $this->assertTrue(isset($collection->name), 'isset $collection->name');
        $this->assertFalse(isset($collection['no-exists']), 'isset $collection[\'no-exists\']');
        $this->assertFalse(isset($collection->no_exists), 'isset $collection->no_exists');

        $collection->name = 'new value';
        $this->assertSame('new value', $collection->name);

        $collection->age = 18;
        $this->assertSame(18, $collection->age);

        $collection->set('foo', 'bar');
        $this->assertTrue(isset($collection->foo));
        $this->assertSame('bar', $collection->foo);

        unset($collection->foo);
        $this->assertFalse($collection->has('foo'));
        $this->assertFalse(isset($collection->foo), 'isset $collection->foo');

        $collection->set('key.key1.key2', 'mock-value');
        $this->assertNull($collection->get('key.no-exists'));
        $this->assertSame(['key2' => 'mock-value'], $collection->get('key.key1'));
        $this->assertSame('mock-value', $collection->get('key.key1.key2'));
        $this->assertSame('default-value', $collection->get('no-exists', 'default-value'));
        $this->assertTrue($collection->exists($array, 'foo'));

        $this->assertSame('value1', $collection->get('arr.0.key'));
        $this->assertSame('value2', $collection->get('arr.1.key'));
        $this->assertSame([
            [
                'key' => 'value1',
            ],
            [
                'key' => 'value2',
            ],
        ], $collection->get('arr'));
    }
}
