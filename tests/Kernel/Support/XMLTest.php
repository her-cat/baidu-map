<?php

namespace HerCat\BaiduMap\Tests\Kernel\Support;

use HerCat\BaiduMap\Kernel\Support\XML;
use HerCat\BaiduMap\Tests\TestCase;

class XMLTest extends TestCase
{
    public function testParse()
    {
        $xml = '<root>
                  <name>mock-name</name>
                  <age>18</age>
                  <foo>1</foo>
                  <foo>2</foo>
                  <foo>3</foo>
                </root>';

        $this->assertSame(['name' => 'mock-name', 'age' => '18', 'foo' => ['1', '2', '3']], XML::parse($xml));
    }

    public function testNormalize()
    {
        $this->assertSame(18, XML::normalize(18));
        $this->assertSame('foo', XML::normalize('foo'));

        $this->assertSame(['name' => 'mock-name'], XML::normalize(['name' => 'mock-name']));

        $man = new \stdClass();
        $man->name = 'mock-name';

        $obj = new \stdClass();
        $obj->foo = 'bar';
        $obj->products = [
            [
                'name' => 'product-1',
            ],
            [
                'name' => 'product-2',
            ],
        ];
        $obj->man = $man;

        $this->assertSame([
            'foo' => 'bar',
            'products' => [
                [
                    'name' => 'product-1',
                ],
                [
                    'name' => 'product-2',
                ],
            ],
            'man' => [
                'name' => 'mock-name'
            ],
        ], XML::normalize($obj));
    }

    public function testSanitize()
    {
        $content_template = '1%s%s%s234%s测试%sabcd?*_^%s@#%s%s%s';

        $valid_chars = preg_replace('/(%s)+/', '', $content_template);
        $invalid_chars = sprintf($content_template, "\x1", "\x02", "\3", "\u{05}", "\xe", "\xF", "\u{00FFFF}", "\xC", "\10");

        $xml_template = '<xml><foo>We shall filter out invalid chars</foo><bar>%s</bar></xml>';

        $element = 'SimpleXMLElement';
        $option = LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOBLANKS;

        $invalid_xml = sprintf($xml_template, $invalid_chars);
        libxml_use_internal_errors(true);
        $this->assertFalse(simplexml_load_string($invalid_xml, $element, $option));
        libxml_use_internal_errors(false);

        $valid_xml = sprintf($xml_template, $valid_chars);

        $this->assertSame(
            (array) simplexml_load_string($valid_xml, $element, $option),
            (array) simplexml_load_string(XML::sanitize($invalid_xml), $element, $option)
        );
    }
}
