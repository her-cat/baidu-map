<?php

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
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * @return mixed
     */
    public function last()
    {
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

        $items = new self($this->all());

        $items->forget($keys);

        return $items;
    }

    /**
     * @param $key
     *
     * @param null $default
     *
     * @return array|mixed|null
     */
    public function get($key, $default = null)
    {
        $items = $this->all();

        if (is_null($key)) {
            return $items;
        }

        if ($this->exists($items, $key)) {
            return $items[$key];
        }

        if (false === stripos($key, '.')) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($items) || !$this->exists($items, $segment)) {
                return $default;
            }

            $items = $items[$segment];
        }

        return $items;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set($key, $value)
    {
        $items = &$this->items;

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($items[$key]) || !is_array($items[$key])) {
                $items[$key] = [];
            }

            $items = &$items[$key];
        }

        $items[array_shift($keys)] = $value;
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
        if (is_null($keys)) {
            return false;
        }

        $keys = is_array($keys) ? $keys : func_get_args();

        $items = $this->all();

        if (empty($items) || [] === $keys) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $items;

            if ($this->exists($items, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param string|array $keys
     */
    public function forget($keys)
    {
        $items = &$this->items;
        $original = &$this->items;

        $keys = (array) $keys;

        if (0 === count($keys)) {
            return;
        }

        foreach ($keys as $key) {
            if ($this->exists($items, $key)) {
                unset($items[$key]);

                continue;
            }

            $parts = explode('.', $key);

            $items = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if ($this->exists($items, $part) && is_array($items[$part])) {
                    unset($items[$part]);
                } else {
                    continue 2;
                }
            }

            unset($items[array_shift($parts)]);
        }
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
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->all());
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->get($offset) : null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
         return serialize($this->all());
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        return $this->items = unserialize($serialized);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
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
