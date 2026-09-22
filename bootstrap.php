<?php
/**
 * Test bootstrap: Composer autoloader plus the base path the file system
 * layer expects. Used by phpunit.xml.
 */

require __DIR__ . '/vendor/autoload.php';

if (!defined('PATH')) {
    define('PATH', __DIR__);
}
