<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Kernel\Support;

/**
 * Class Collection.
 *
 * @author her-cat <i@her-cat.com>
 */
class Collection implements \Countable, \ArrayAccess, \Serializable, \JsonSerializable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function first($default = null)
    {
        return empty($this->items) ? $default : reset($this->items);
    }

    /**
     * @param null $default
     * @return mixed
     */
    public function last($default = null)
    {
        if (empty($this->items)) {
            return $default;
        }

        $end = end($this->items);

        reset($this->items);

        return $end;
    }

    /**
     * @param string|array $keys
     *
     * @return array
     */
    public function only($keys)
    {
        return array_intersect_key($this->all(), array_flip((array) $keys));
    }

    /**
     * @param string|array $keys
     *
     * @return Collection
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new self(Arr::except($this->all(), $keys));
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->all(), $key, $default);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set($key, $value)
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * @param array $array
     * @param $key
     *
     * @return bool
     */
    public function exists(array $array, $key)
    {
        return array_key_exists($key, $array);
    }

    /**
     * @param string|array $keys
     *
     * @return bool
     */
    public function has($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return Arr::has($this->all(), $keys);
    }

    /**
     * @param string|array $keys
     */
    public function forget($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        Arr::forget($this->items, $keys);
    }

    /**
     * @param string $key
     * @param null|mixed $default
     *
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->items, $key, $default);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * @param int $option
     *
     * @return false|string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE)
    {
        return \json_encode($this->all(), $option);
    }

    /**
     * Count elements of an object.
     *
     * @see https://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             </p>
     *             <p>
     *             The return value is cast to an integer.
     *
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->all());
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
        return $this->has($offset);
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
        return $this->offsetExists($offset) ? $this->get($offset) : null;
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
        $this->set($offset, $value);
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
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * String representation of object.
     *
     * @see https://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize($this->all());
    }

    /**
     * Constructs the object.
     *
     * @see https://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->all();
    }

    /**
     * @param $key
     *
     * @return array|mixed|null
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        $this->forget($key);
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
