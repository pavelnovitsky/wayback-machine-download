<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class OptionsTest
 * @package Downloader\Test
 */
class OptionsTest extends TestCase
{
    /* @var Downloader\Options $obj */
    protected $obj = null;

    protected function setUp(): void
    {
        $this->obj = new Downloader\Options();
    }

    /**
     * @dataProvider setSuccessProvider
     * @param $key
     * @param $val
     */
    public function testSet($key, $val)
    {
        $options = $this->obj->set($key, $val);
        $this->assertInstanceOf('Downloader\Options', $options);
    }

    /**
     * @return array
     */
    public function setSuccessProvider()
    {
        return [
            ['h', 'http://example.com'],
            ['t', '20001204'],
            ['host', 'http://example.com'],
            ['timestamp', '20001204'],
            ['T', '20160101'],
            ['to', '20160101'],
        ];
    }

    /**
     * @dataProvider setFailProvider
     * @param $key
     * @param $val
     */
    public function testFailSet($key, $val)
    {
        $this->expectException(\RuntimeException::class);
        $this->obj->set($key, $val);
    }

    /**
     * @return array
     */
    public function setFailProvider()
    {
        return [
            ['test', 'http://example.com'],
            [null, '20001204'],
            ['r', 'some value'],
        ];
    }

    public function testGetSuccess()
    {
        $this
            ->obj
            ->set('h', 'http://example.com')
            ->set('t', '20001214')
            ->set('host', 'http://test.com')
            ->set('timestamp', '19831214')
            ->set('T', '20160101');

        $this->assertEquals('http://test.com', $this->obj->get('host'));
        $this->assertEquals('19831214', $this->obj->get('timestamp'));
        $this->assertEquals('20160101', $this->obj->get('to'));
    }

    public function testGetFail()
    {
        $this
            ->obj
            ->set('host', 'http://test.com')
            ->set('timestamp', '19831214');

        $this->expectException(\RuntimeException::class);
        $this->obj->get('unsupported-value');
    }
}
