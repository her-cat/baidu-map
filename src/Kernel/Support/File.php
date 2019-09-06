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

use finfo;

/**
 * Class File.
 *
 * @author overtrue <i@overtrue.me>
 */
class File
{
    /**
     * MIME mapping.
     *
     * @var array
     */
    protected static $extensionMap = [
        'image/bmp' => '.bmp',
        'image/gif' => '.gif',
        'image/png' => '.png',
        'image/tiff' => '.tiff',
        'image/jpeg' => '.jpg',
    ];

    /**
     * File header signatures.
     *
     * @var array
     */
    protected static $signatures = [
        'ffd8ff' => '.jpg',
        '424d' => '.bmp',
        '47494638' => '.gif',
        '2f55736572732f6f7665' => '.png',
        '89504e47' => '.png',
    ];

    /**
     * Return steam extension.
     *
     * @param string $stream
     *
     * @return string|false
     */
    public static function getStreamExt($stream)
    {
        $ext = self::getExtBySignature($stream);

        try {
            if (empty($ext) && is_readable($stream)) {
                $stream = file_get_contents($stream);
            }
        } catch (\Exception $e) {
        }

        $fileInfo = new finfo(FILEINFO_MIME);

        $mime = strstr($fileInfo->buffer($stream), ';', true);

        return isset(self::$extensionMap[$mime]) ? self::$extensionMap[$mime] : $ext;
    }

    /**
     * Get file extension by file header signature.
     *
     * @param string $stream
     *
     * @return string
     */
    public static function getExtBySignature($stream)
    {
        $prefix = strval(bin2hex(mb_strcut($stream, 0, 10)));

        foreach (self::$signatures as $signature => $extension) {
            if (0 === strpos($prefix, strval($signature))) {
                return $extension;
            }
        }

        return '';
    }
}
