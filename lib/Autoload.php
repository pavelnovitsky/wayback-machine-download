<?php

namespace Downloader;

/**
 * Class Autoload
 * try to find and load requested class
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Autoload
{
    /**
     * register autoload
     */
    public function __construct()
    {
        spl_autoload_extensions('.php');
        spl_autoload_register([$this, 'load']);
    }

    /**
     * @param string $className
     * @throws \RuntimeException
     */
    public static function load($className)
    {
        $classFilename = str_replace(
            [__NAMESPACE__ . '\\', '\\'],
            ['', DIRECTORY_SEPARATOR],
            $className
        ) . '.php';

        $filePath = stream_resolve_include_path($classFilename);
        if ($filePath) {
            /** @noinspection PhpIncludeInspection */
            require $filePath;
        }
    }
}
