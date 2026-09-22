<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @package Downloader\Test
 */
class ClientTest extends TestCase
{
    /* @var Downloader\Client $obj */
    protected $obj = null;

    protected function setUp(): void
    {
        $this->obj = new Downloader\Client();
    }

    public function testGetUrlThrowsWhenUnset()
    {
        $this->expectException(\RuntimeException::class);
        $this->obj->getUrl();
    }

    public function testUrlRoundTrip()
    {
        $this->assertSame($this->obj, $this->obj->setUrl('https://example.com'));
        $this->assertSame('https://example.com', $this->obj->getUrl());
    }

    public function testResultRoundTrip()
    {
        $this->assertSame($this->obj, $this->obj->setResult('body'));
        $this->assertSame('body', $this->obj->getResult());
    }
}
