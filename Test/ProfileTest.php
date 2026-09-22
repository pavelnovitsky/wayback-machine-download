<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class ProfileTest
 * @package Downloader\Test
 */
class ProfileTest extends TestCase
{
    /**
     * @dataProvider convertMemoryProvider
     * @param int $bytes
     * @param string $expected
     */
    public function testConvertMemory($bytes, $expected)
    {
        $this->assertSame($expected, Downloader\Profile::convertMemory($bytes));
    }

    /**
     * @return array
     */
    public function convertMemoryProvider()
    {
        return [
            [512, '512 b'],
            [2048, '2 Kib'],
            [1572864, '1.5Mib'],
        ];
    }

    public function testGetResultReportsElapsedDuration()
    {
        Downloader\Profile::start();
        $result = Downloader\Profile::getResult();

        $this->assertArrayHasKey('time', $result);
        $this->assertArrayHasKey('memory', $result);

        // elapsed time, not the wall clock: a fresh run is essentially zero
        $this->assertMatchesRegularExpression('/^\d\d:\d\d:\d\d$/', $result['time']);
        $this->assertStringStartsWith('00:00:0', $result['time']);
    }
}
