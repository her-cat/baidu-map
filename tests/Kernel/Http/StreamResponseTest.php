<?php

namespace HerCat\BaiduMap\Tests\Kernel\Http;

use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\Exceptions\RuntimeException;
use HerCat\BaiduMap\Kernel\Http\StreamResponse;
use HerCat\BaiduMap\Tests\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class StreamResponseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('testing'));
    }

    public function testSave()
    {
        $response = new StreamResponse(200, [], file_get_contents(STUBS_ROOT.'/files/image.jpg'));
        $directory = vfsStream::url('testing');

        // default filename
        $filename = $response->save($directory);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertStringEndsWith('.jpg', $filename);

        // custom filename
        $filename = $response->save($directory, 'custom-filename.jpg');
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertSame('custom-filename.jpg', $filename);

        // custom filename without auto suffix
        $filename = $response->save($directory, 'custom-filename', false);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertSame('custom-filename', $filename);

        // get filename from header
        $header = ['Content-Disposition' => 'attachment; filename="filename.png"'];
        $response = new StreamResponse(200, $header, file_get_contents(STUBS_ROOT.'/files/image.jpg'));
        $filename = $response->save($directory);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertSame('filename.png', $filename);

        // header without filename
        $header = ['Content-Disposition' => 'attachment;'];
        $response = new StreamResponse(200, $header, file_get_contents(STUBS_ROOT.'/files/image.jpg'));
        $filename = $response->save($directory);
        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($filename));
        $this->assertStringEndsWith('.jpg', $filename);

        // not writable
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('\'vfs://usr\' is not writable.');
        vfsStream::setup('usr', 0444);
        $response->save(vfsStream::url('usr'));
    }

    public function testSaveWithEmptyContent()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid media response content.');

        // empty content
        $response = new StreamResponse(200, [], file_get_contents(STUBS_ROOT.'/files/empty.file'));
        $response->save(vfsStream::url('testing'));
    }

    public function testSaveWithJsonString()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid media response content.');

        // json string
        $response = new StreamResponse(200, [], '{"foo": "bar"}');
        $response->save(vfsStream::url('testing'));
    }

    public function testSaveAs()
    {
        $response = \Mockery::mock(StreamResponse::class.'[save]');

        $response->expects()->save('dir', 'filename', true)->andReturn('filename.png');
        $response->expects()->save('dir', 'filename', false)->andReturn('filename');

        $this->assertSame('filename.png', $response->saveAs('dir', 'filename'));
        $this->assertSame('filename', $response->saveAs('dir', 'filename', false));
    }
}
