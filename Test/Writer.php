<?php

namespace Downloader\Test;

use Downloader;

/**
 * Class WriterTest
 * @package Downloader\Test
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider sendProvider
     * @param $string
     * @param $type
     * @param $expected
     */
    public function testSend($string, $type, $expected)
    {
        ob_start();

        Downloader\Writer::send($string, $type);
        $out = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($out, $expected);

    }

    /**
     * @return array
     */
    public function sendProvider()
    {
        return [
            ['test', null, 'test' . PHP_EOL],
            ['test', 'success', chr(27) . '[42mtest' . chr(27) . '[0m' . PHP_EOL],
            ['test', 'warning', chr(27) . '[43mtest' . chr(27) . '[0m' . PHP_EOL],
            ['test', 'note', chr(27) . '[44mtest' . chr(27) . '[0m' . PHP_EOL],
            ['test', 'unexpected_status', chr(27) . '[43mtest' . chr(27) . '[0m' . PHP_EOL],

        ];
    }
}