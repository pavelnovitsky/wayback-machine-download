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

if (version_compare(phpversion(), '5.4.0', '<') === true) {
    echo 'WayBack Downloader supports PHP 5.4.0 or later.';
    exit(1);
}

define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__));

set_include_path(
    PATH . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR .
    get_include_path()
);

/** @noinspection PhpIncludeInspection */
require 'Autoload.php';
new Autoload;
new Downloader;
