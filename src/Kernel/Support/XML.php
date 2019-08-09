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
 * Class XML.
 *
 * @author overtrue <i@overtrue.me>
 */
class XML
{
    /**
     * @param $str
     *
     * @return array|null
     */
    public static function parse($str)
    {
        $backup = libxml_disable_entity_loader(true);

        $result = self::normalize(simplexml_load_string(self::sanitize($str), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOBLANKS));

        libxml_disable_entity_loader($backup);

        return $result;
    }

    /**
     * @param $obj
     *
     * @return array|null
     */
    public static function normalize($obj)
    {
        $result = null;

        if (is_object($obj)) {
            $obj = (array) $obj;
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $result[$key] = self::normalize($value);
            }
        } else {
            $result = $obj;
        }

        return $result;
    }

    /**
     * Delete invalid characters in XML.
     *
     * @see https://www.w3.org/TR/2008/REC-xml-20081126/#charsets - XML charset range
     * @see http://php.net/manual/en/regexp.reference.escape.php - escape in UTF-8 mode
     *
     * @param string $str
     *
     * @return string
     */
    public static function sanitize($str)
    {
        return preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]+/u', '', $str);
    }
}
