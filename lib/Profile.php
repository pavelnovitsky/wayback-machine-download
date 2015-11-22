<?php

namespace Downloader;

/**
 * Class Profile
 * collect statistics: time of execution and used memory
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Profile
{
    private static $timeStart = 0;

    /**
     * set start time
     */
    public static function start()
    {
        self::$timeStart = self::getTime();
    }

    /**
     * @return float
     */
    private static function getTime()
    {
        return microtime(true);
    }

    /**
     * @return int
     */
    private static function getMemory()
    {
        return memory_get_usage(true);
    }

    /**
     * @return array
     */
    public static function getResult()
    {
        $time = self::getTime();

        return [
            'time' => gmdate('H:i:s', $time),
            'memory' => self::convertMemory(self::getMemory()),
        ];

    }

    /**
     * @param int $size
     * @return string
     */
    public static function convertMemory($size)
    {
        if ($size < 1024) {
            $result = $size . ' b';
        } elseif ($size < 1048576) {
            $result = round($size / 1024, 2) . ' Kib';
        } else {
            $result = round($size / 1048576, 2) . 'Mib';
        }


        return $result;
    }
}
