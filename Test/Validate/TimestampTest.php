<?php

namespace Downloader\Test\Validate;

use Downloader\Validate;
use PHPUnit\Framework\TestCase;

/**
 * Class TimestampTest
 * @package Downloader\Test\Validate
 */
class TimestampTest extends TestCase
{
    /* @var Validate\Timestamp $obj */
    protected $obj = null;

    protected function setUp(): void
    {
        $this->obj = new Validate\Timestamp();
    }

    /**
     * @dataProvider getSuccessProcessProvider
     * @param $timestamp
     */
    public function testSuccessProcess($timestamp)
    {
        $result = $this->obj->setValue($timestamp)->process();
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
     * @param $timestamp
     */
    public function testFailProcess($timestamp)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->obj->setValue($timestamp)->process();
    }

    /**
     * @return array
     */
    public function getFailProcessProvider()
    {
        return [
            ['abc'],
            ['2000-11'],
            ['20.45'],
        ];
    }
}
