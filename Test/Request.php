<?php

namespace Downloader\Test;

use Downloader;

/**
 * Class RequestTest
 * @package Downloader\Test
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /* @var Downloader\Request $obj */
    protected $obj = null;

    public function setUp()
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
        ];
    }

    /**
     * @dataProvider getOptionsFailProvider
     * @expectedException \RuntimeException
     * @param $set
     */
    public function testFailGetOptions($set)
    {
        $data = [];
        foreach ($set as $val) {
            $tmp = explode('-', $val);
            $data[$tmp[0]] = $tmp[1];
        }

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
