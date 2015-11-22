<?php

namespace Downloader\Test;

use Downloader\Validate;

/**
 * Class HostTest
 * @package Downloader\Test
 */
class TimestampTest extends \PHPUnit_Framework_TestCase
{

    /* @var Validate\Host $obj*/
    protected $obj = null;

    public function setUp()
    {
        $this->obj = new Validate\Timestamp();
    }

    /**
     * @dataProvider getSuccessProcessProvider
     * @param $ts
     */
    public function testSuccessProcess($ts)
    {
        $result = $this->obj->setValue($ts)->process();
        $this->assertTrue($result);
    }

    /**
     * @return array
     */
    public function getSuccessProcessProvider()
    {
        return [
            ['2000'],
            ['200011'],
            ['20001113'],
            [0],
            [null],
        ];
    }

    /**
     * @dataProvider getFailProcessProvider
     * @expectedException \UnexpectedValueException
     * @param $url
     */
    public function testFailProcess($url)
    {
        $this->obj->setValue($url)->process();
    }

    /**
     * @return array
     */
    public function getFailProcessProvider()
    {
        return [
            ['abc'],
            [20.45]
        ];
    }
}