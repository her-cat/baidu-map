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

use HerCat\BaiduMap\Kernel\Support\File;
use HerCat\BaiduMap\Tests\TestCase;

class FileTest extends TestCase
{
    public function testGetStreamExt()
    {
        $this->assertSame('.png', File::getStreamExt(STUBS_ROOT.'/files/image.png'));
        $this->assertSame('.jpg', File::getStreamExt(STUBS_ROOT.'/files/image.jpg'));

        $this->assertSame('.png', File::getExtBySignature(file_get_contents(STUBS_ROOT.'/files/image.png')));
        $this->assertSame('.jpg', File::getExtBySignature(file_get_contents(STUBS_ROOT.'/files/image.jpg')));
        $this->assertSame('', File::getExtBySignature(file_get_contents(STUBS_ROOT.'/files/empty.file')));
    }
}
