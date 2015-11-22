<?php

namespace Downloader;

/**
 * Class Host
 * send line to the console
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Writer
{
    /**
     * Colorize and output string
     * @param string $string
     * @param bool|false $type
     */
    public static function send($string, $type = false)
    {
        if ($type) {
            $string = new Colors($string, $type);
        }

        echo $string . PHP_EOL;
    }
}
