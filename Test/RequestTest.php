<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 * @package Downloader\Test
 */
class RequestTest extends TestCase
{
    /* @var Downloader\Request $obj */
    protected $obj = null;

    protected function setUp(): void
    {
        $this->obj = new Downloader\Request();
    }

    /**
     * @dataProvider getOptionsSuccessProvider
     * @param $set
     */
    public function testGetOptions($set)
    {
        $data = [];
        foreach ($set as $val) {
            $tmp = explode('-', $val);
            $data[$tmp[0]] = $tmp[1];
        }

        $options = $this->obj->setOptions($data)->getOptions();
        $this->assertNotEmpty($options);
    }

    /**
     * @return array
     */
    public function getOptionsSuccessProvider()
    {
        return [
            [['h-http://example.com', 't-20001204']],
            [['host-http://example.com', 'timestamp-20001204']],
            [['h-http://example.com', 'timestamp-20001204']],
            [['host-http://example.com', 't-20001204']],
            [['h-http://example.com']],
            [['host-http://example.com']],
            [['h-http://example.com', 't-20050101', 'T-20160101']],
            [['host-http://example.com', 'to-20160101']],
        ];
    }

    /**
     * @dataProvider getOptionsFailProvider
     * @param $set
     */
    public function testFailGetOptions($set)
    {
        $data = [];
        foreach ($set as $val) {
            $tmp = explode('-', $val);
            $data[$tmp[0]] = $tmp[1];
        }

        $this->expectException(\RuntimeException::class);
        $this->obj->setOptions($data)->getOptions();
    }

    /**
     * @return array
     */
    public function getOptionsFailProvider()
    {
        return [
            [[]],
        ];
    }
}
