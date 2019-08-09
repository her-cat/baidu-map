<?php

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
