<?php

namespace HerCat\BaiduMap\Tests\Kernel\Support;

use HerCat\BaiduMap\Kernel\Support\Arr;
use HerCat\BaiduMap\Kernel\Support\Collection;
use HerCat\BaiduMap\Tests\TestCase;

class ArrTest extends TestCase
{
    public function testAdd()
    {
        $array = Arr::add(['name' => 'mock-name'], 'age', 18);

        $this->assertSame(['name' => 'mock-name', 'age' => 18], $array);

        $array = Arr::add($array, 'age', 19);

        $this->assertSame(['name' => 'mock-name', 'age' => 18], $array);
    }

    public function testCrossJoin()
    {
        $this->assertSame([[1, 'a'], [1, 'b'], [1, 'c']], Arr::crossJoin([1], ['a', 'b', 'c']));

        $this->assertSame(
            [[1, 'a'], [1, 'b'], [2, 'a'], [2, 'b']],
            Arr::crossJoin([1, 2], ['a', 'b'])
        );

        $this->assertSame(
            [[1, 'a'], [1, 'b'], [1, 'c'], [2, 'a'], [2, 'b'], [2, 'c']],
            Arr::crossJoin([1, 2], ['a', 'b', 'c'])
        );

        $this->assertSame(
            [
                [1, 'a', 'A'], [1, 'a', 'B'], [1, 'a', 'C'],
                [1, 'b', 'A'], [1, 'b', 'B'], [1, 'b', 'C'],
                [2, 'a', 'A'], [2, 'a', 'B'], [2, 'a', 'C'],
                [2, 'b', 'A'], [2, 'b', 'B'], [2, 'b', 'C'],
            ],
            Arr::crossJoin([1, 2], ['a', 'b'], ['A', 'B', 'C'])
        );

        $this->assertSame([], Arr::crossJoin([], ['a', 'b'], ['A', 'B', 'C']));
        $this->assertSame([], Arr::crossJoin([1, 2], [], ['A', 'B', 'C']));
        $this->assertSame([], Arr::crossJoin([1, 2], ['a', 'b'], []));

        $this->assertSame([], Arr::crossJoin([], [], []));
        $this->assertSame([], Arr::crossJoin([], []));
        $this->assertSame([], Arr::crossJoin([]));

        $this->assertSame([[]], Arr::crossJoin());
    }

    public function testDivide()
    {
        list($keys, $values) = Arr::divide(['name' => 'mock-name', 'age' => 18]);

        $this->assertSame(['name', 'age'], $keys);
        $this->assertSame(['mock-name', 18], $values);
    }

    public function testDot()
    {
        $this->assertSame([], Arr::dot([]));

        $this->assertSame(['name' => 'mock-name'], Arr::dot(['name' => 'mock-name']));

        $this->assertSame(
            ['person.name' => 'mock-name', 'person.age' => 18],
            Arr::dot(['person' => ['name' => 'mock-name', 'age' => 18]])
        );
    }

    public function testExcept()
    {
        $array = ['name' => 'mock-name', 'age' => 18, 'foo' => 'bar'];

        $this->assertSame(['name' => 'mock-name', 'age' => 18], Arr::except($array, ['foo']));
        $this->assertSame(['foo' => 'bar'] , Arr::except($array, ['name', 'age']));
        $this->assertSame(['name' => 'mock-name', 'age' => 18, 'foo' => 'bar'], Arr::except($array, ['no-exists']));
    }

    public function testExists()
    {
        $this->assertTrue(Arr::exists(['name' => 'mock-name', 'age' => 18], 'name'));
        $this->assertTrue(Arr::exists(['name' => 'mock-name', 'age' => 18], 'age'));
        $this->assertFalse(Arr::exists(['name' => 'mock-name', 'age' => 18], 'no-exists'));

        $this->assertTrue(Arr::exists(['name', 'age'], 0));
        $this->assertTrue(Arr::exists(['name', 'age'], 1));
        $this->assertFalse(Arr::exists(['name', 'age'], 2));
    }

    public function testFirst()
    {
        $this->assertNull(Arr::first([]));

        $this->assertSame('default-value', Arr::first([], null, 'default-value'));

        $array = ['name' => 'mock-name', 'age' => 18, 'bar' => 20, 'foo' => 'bar'];

        $this->assertSame('mock-name', Arr::first($array));

        $this->assertSame('bar', Arr::first($array, function ($value, $key) {
            return 'foo' == $key;
        }));

        $this->assertSame(18, Arr::first($array, function ($value, $key) {
            return is_numeric($value);
        }));

        $this->assertNull(Arr::first($array, function ($value, $key) {
            return 'no-exists' == $key;
        }));
    }

    public function testLast()
    {
        $this->assertNull(Arr::last([]));

        $this->assertSame('default-value', Arr::last([], null, 'default-value'));

        $array = ['name' => 'mock-name', 'age' => 18, 'bar' => 20, 'foo' => 'bar'];

        $this->assertSame('bar', Arr::last($array));

        $this->assertSame('bar', Arr::last($array, function ($value, $key) {
            return 'foo' == $key;
        }));

        $this->assertSame(20, Arr::last($array, function ($value, $key) {
            return is_numeric($value);
        }));

        $this->assertNull(Arr::last($array, function ($value, $key) {
            return 'no-exists' == $key;
        }));
    }

    public function testFlatten()
    {
        $this->assertSame(['a', 'b', 'c'], Arr::flatten(['a', 'b', 'c']));
        $this->assertSame(['a', 'b', 'c'], Arr::flatten([['a', 'b'], 'c']));
        $this->assertSame(['a', 'b', 'c'], Arr::flatten([['a', 'b'], ['c']]));
        $this->assertSame(['a', 'b', 'c'], Arr::flatten([['a', ['b']], ['c']]));
        $this->assertSame(['a', null, 'c', null], Arr::flatten([['a', null], 'c', null]));

        $this->assertSame(['a', 'b', 'c'], Arr::flatten([new Collection(['a', 'b']), 'c']));
        $this->assertSame(['a', 'b', 'c'], Arr::flatten([new Collection(['a', 'b']), ['c']]));
        $this->assertSame(['a', 'b', 'c'], Arr::flatten([new Collection(['a', ['b']]), ['c']]));
        $this->assertSame(['a', null, 'c'], Arr::flatten([new Collection(['a', null]), ['c']]));
    }

    public function testFlattenWithDepth()
    {
        $this->assertSame(['a', 'b', 'c', 'd'], Arr::flatten([[['a', ['b']], 'c'], 'd']));
        $this->assertSame([['a', ['b']], 'c', 'd'], Arr::flatten([[['a', ['b']], 'c'], 'd'], 1));
        $this->assertSame(['a', ['b'], 'c', 'd'], Arr::flatten([[['a', ['b']], 'c'], 'd'], 2));
        $this->assertSame(['a', 'b', 'c', 'd'], Arr::flatten([[['a', ['b']], 'c'], 'd'], 3));
    }

    public function testForget()
    {
        $array = ['name' => 'mock-name', 'email' => 'mock-email', 'foo' => 'bar'];

        Arr::forget($array, 'name');
        $this->assertFalse(isset($array['name']));

        Arr::forget($array, '');
        $this->assertCount(2, $array);

        Arr::forget($array, ['email', 'foo']);
        $this->assertFalse(isset($array['email']));
        $this->assertFalse(isset($array['foo']));

        $this->assertCount(0, $array);
    }

    public function testGet()
    {
        $array = [
            'name' => 'mock-name',
            'email' => 'mock-email',
            'foo' => [
                'bar' => 'foo',
            ],
            'products' => [
                [
                    'name' => 'product-1',
                ],
                [
                    'name' => 'product-2',
                ],
            ],
        ];

        $this->assertSame([], Arr::get([], null));
        $this->assertSame([], Arr::get([], null, 'default-value'));
        $this->assertSame('mock-name', Arr::get($array, 'name'));

        $this->assertSame('foo', Arr::get($array, 'foo.bar'));
        $this->assertSame('product-1', Arr::get($array, 'products.0.name'));
        $this->assertSame('product-2', Arr::get($array, 'products.1.name'));

        $this->assertNull(Arr::get($array, 'no-exists'));
        $this->assertSame('default-value', Arr::get($array, 'no-exists', 'default-value'));
    }

    public function testHas()
    {
        $array = [
            'name' => 'mock-name',
            'email' => 'mock-email',
            'foo' => [
                'bar' => null,
            ],
            'bar.foo' => null,
            'products' => [
                [
                    'name' => 'product-1',
                ],
                [
                    'name' => 'product-2',
                ],
            ],
        ];

        $this->assertFalse(Arr::has($array, []));
        $this->assertFalse(Arr::has($array, null));
        $this->assertFalse(Arr::has($array, [null]));
        $this->assertFalse(Arr::has([], 'name'));

        $this->assertTrue(Arr::has($array, 'name'));
        $this->assertTrue(Arr::has($array, ['name', 'email']));
        $this->assertFalse(Arr::has($array, 'no-exists'));

        $this->assertTrue(Arr::has($array, 'foo.bar'));
        $this->assertTrue(Arr::has($array, 'bar.foo'));
        $this->assertTrue(Arr::has($array, ['products.0.name', 'products.1.name']));
        $this->assertFalse(Arr::has($array, ['products.0.name', 'products.2.name']));
    }

    public function testIsAssoc()
    {
        $this->assertTrue(Arr::isAssoc(['a' => 1, 0 => 'b']));
        $this->assertTrue(Arr::isAssoc([1 => 'a', 0 => 'b']));
        $this->assertTrue(Arr::isAssoc([1 => 'a', 2 => 'b']));
        $this->assertFalse(Arr::isAssoc([0 => 'a', 1 => 'b']));
        $this->assertFalse(Arr::isAssoc(['a', 'b']));
    }

    public function testOnly()
    {
        $array = ['name' => 'mock-name', 'email' => 'mock-email', 'foo' => 'bar'];

        $this->assertSame(['name' => 'mock-name'], Arr::only($array, 'name'));
        $this->assertSame(['name' => 'mock-name', 'foo' => 'bar'], Arr::only($array, ['name', 'foo']));
        $this->assertSame(['name' => 'mock-name', 'foo' => 'bar'], Arr::only($array, ['name', 'foo', 'no-exists']));
    }

    public function testPrepend()
    {
        $this->assertSame(['a', 'b', 'c'], Arr::prepend(['b', 'c'], 'a'));
        $this->assertSame(['a' => 0, 'b' => 1, 'c' => 2], Arr::prepend(['b' => 1, 'c' => 2], 0, 'a'));
    }

    public function testPull()
    {
        $array = [
            'name' => 'mock-name',
            'email' => 'mock-email',
            'products' => [
                'items' => [
                    [
                        'name' => 'product-1',
                    ],
                    [
                        'name' => 'product-2',
                    ],
                ],
            ],
        ];

        $this->assertSame('mock-name', Arr::pull($array, 'name'));
        $this->assertFalse(isset($array['name']));

        $this->assertNull(Arr::pull($array, 'name'));
        $this->assertSame('default-value', Arr::pull($array, 'name', 'default-value'));

        $this->assertSame('product-1', Arr::pull($array, 'products.items.0.name'));

        $this->assertSame([
            [],
            [
                'name' => 'product-2',
            ],
        ], Arr::pull($array, 'products.items'));
    }

    public function testRandom()
    {
        $randomValues = Arr::random(['a', 'b', 'c']);
        $this->assertContains($randomValues, ['a', 'b', 'c']);

        $randomValues = Arr::random(['a', 'b', 'c'], 1);
        $this->assertInternalType('array', $randomValues);
        $this->assertCount(1, $randomValues);
        $this->assertContains($randomValues[0], ['a', 'b', 'c']);

        $randomValues = Arr::random(['a', 'b', 'c'], 2);
        $this->assertInternalType('array', $randomValues);
        $this->assertCount(2, $randomValues);
        $this->assertContains($randomValues[0], ['a', 'b', 'c']);
        $this->assertContains($randomValues[1], ['a', 'b', 'c']);
    }

    public function testSet()
    {
        $array = [
            'products' => [
                'items' => [
                    [
                        'name' => 'product-1',
                    ],
                    [
                        'name' => 'product-2',
                    ],
                ],
            ],
        ];

        Arr::set($array, 'products.page', 1);
        Arr::set($array, 'products.items.0.price', 200);
        Arr::set($array, 'products.items.1.name', 'product-3');

        $this->assertSame([
            'products' => [
                'items' => [
                    [
                        'name' => 'product-1',
                        'price' => 200,
                    ],
                    [
                        'name' => 'product-3',
                    ],
                ],
                'page' => 1,
            ],
        ], $array);
    }

    public function testWhere()
    {
        $array = [100, 200, 300, 400, 500, 600];

        $array = Arr::where($array, function ($value, $key) {
            return $value % 200 === 0;
        });

        $this->assertSame([1 => 200, 3 => 400,  5 => 600], $array);
    }

    public function testWhereKey()
    {
        $array = ['a' => 100, 2 => 200, '3' => 300, 'd' => 400];

        $array = Arr::where($array, function ($value, $key) {
            return is_numeric($key);
        });

        $this->assertSame([2 => 200, '3' => 300], $array);
    }

    public function testWrap()
    {
        $this->assertSame(['a'], Arr::wrap('a'));
        $this->assertSame(['a'], Arr::wrap(['a']));
        $this->assertSame([1], Arr::wrap(1));

        $class = new \stdClass();
        $this->assertSame([$class], Arr::wrap($class));
    }
}
