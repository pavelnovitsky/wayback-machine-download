<?php
namespace Downloader;

define('PATH', dirname(__FILE__));

set_include_path(
    PATH . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR .
    get_include_path()
);

/** @noinspection PhpIncludeInspection */
require PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Autoload.php';
new Autoload;