<?php

namespace Downloader\Test\Validate;

use Downloader\Validate;
use PHPUnit\Framework\TestCase;

/**
 * Class HostTest
 * @package Downloader\Test\Validate
 */
class HostTest extends TestCase
{
    /* @var Validate\Host $obj */
    protected $obj = null;

    protected function setUp(): void
    {
        $this->obj = new Validate\Host();
    }

    /**
     * @dataProvider getSuccessProcessProvider
     * @param $url
     */
    public function testSuccessProcess($url)
    {
        $result = $this->obj->setValue($url)->process();
        $this->assertTrue($result);
    }

    /**
     * @return array
     */
    public function getSuccessProcessProvider()
    {
        return [
            ['http://example.com'],
            ['http://example.com/test.html'],
            ['http://username:password@example.com:8181/test.php?q=test'],
            ['https://www.example.com'],
            ['ftp://mysite.guru'],
        ];
    }

    /**
     * @dataProvider getFailProcessProvider
     * @param $url
     */
    public function testFailProcess($url)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->obj->setValue($url)->process();
    }

    /**
     * @return array
     */
    public function getFailProcessProvider()
    {
        return [
            ['example.com'],
            ['www.example.com'],
            [''],
            [null],
        ];
    }
}
