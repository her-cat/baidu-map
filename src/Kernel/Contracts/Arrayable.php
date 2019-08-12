<?php

namespace HerCat\BaiduMap\Kernel\Contracts;

use ArrayAccess;

/**
 * Interface Arrayable.
 *
 * @author her-cat <i@her-cat.com>
 */
interface Arrayable extends ArrayAccess
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
