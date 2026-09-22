<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class ColorsTest
 * @package Downloader\Test
 */
class ColorsTest extends TestCase
{
    /**
     * @param $status
     * @param $expected
     * @dataProvider getColorProvider
     */
    public function testGetColor($status, $expected)
    {
        $color = new Downloader\Colors('test', $status);
        $this->assertEquals($expected, $color->getColor($status));
    }

    /**
     * @return array
     */
    public function getColorProvider()
    {
        return [
            ['failure', chr(27) . '[41m%s' . chr(27) . '[0m'],
            ['success', chr(27) . '[42m%s' . chr(27) . '[0m'],
            ['warning', chr(27) . '[43m%s' . chr(27) . '[0m'],
            ['note', chr(27) . '[44m%s' . chr(27) . '[0m'],
            ['unknown_status', chr(27) . '[43m%s' . chr(27) . '[0m'],
        ];
    }

    public function testToStringAppliesColor()
    {
        $colors = new Downloader\Colors('hello', 'success');
        $expected = chr(27) . '[42mhello' . chr(27) . '[0m';
        $this->assertEquals($expected, (string) $colors);
    }
}
