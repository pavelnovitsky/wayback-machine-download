<?php
/**
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
namespace Downloader;

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (PHP_SAPI !== 'cli') {
    echo 'WayBack Downloader must be run as a CLI application';
    exit(1);
}

if (!is_file(__DIR__ . '/vendor/autoload.php')) {
    echo 'Dependencies are missing. Run "composer install" first.' . PHP_EOL;
    exit(1);
}

define('PATH', __DIR__);

require __DIR__ . '/vendor/autoload.php';

new Downloader;
