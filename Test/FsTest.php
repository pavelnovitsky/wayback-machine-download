<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class FsTest
 * @package Downloader\Test
 */
class FsTest extends TestCase
{
    /* @var string base directory containing a writable websites/ dir */
    private $base;

    protected function setUp(): void
    {
        $this->base = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'wbd_' . uniqid();
        mkdir($this->base . DIRECTORY_SEPARATOR . 'websites', 0777, true);
    }

    protected function tearDown(): void
    {
        $this->rrmdir($this->base);
    }

    private function rrmdir($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $entry;
            is_dir($path) ? $this->rrmdir($path) : unlink($path);
        }
        rmdir($dir);
    }

    private function fs()
    {
        return new Downloader\Fs($this->base);
    }

    private function hostDir()
    {
        return $this->base . DIRECTORY_SEPARATOR . 'websites' . DIRECTORY_SEPARATOR . 'example.com';
    }

    public function testConstructorThrowsWhenWebsitesDirMissing()
    {
        $this->expectException(\RuntimeException::class);
        new Downloader\Fs($this->base . DIRECTORY_SEPARATOR . 'does-not-exist');
    }

    public function testCreateHostDirRejectsUrlWithoutHost()
    {
        $this->expectException(\RuntimeException::class);
        $this->fs()->createHostDir('not-a-url');
    }

    public function testSaveContentRejectsTraversal()
    {
        $fs = $this->fs();
        $fs->createHostDir('http://example.com');

        $this->expectException(\RuntimeException::class);
        $fs->saveContent('/../../evil.txt', 'x');
    }

    public function testSaveContentAppendsIndexForExtensionlessPath()
    {
        $fs = $this->fs();
        $fs->createHostDir('http://example.com');
        $fs->saveContent('/about', 'hello');

        $this->assertFileExists($this->hostDir() . DIRECTORY_SEPARATOR . 'about' . DIRECTORY_SEPARATOR . 'index.html');
    }

    public function testSaveContentKeepsExistingExtension()
    {
        $fs = $this->fs();
        $fs->createHostDir('http://example.com');
        $fs->saveContent('/style.css', 'body{}');

        $this->assertStringEqualsFile($this->hostDir() . DIRECTORY_SEPARATOR . 'style.css', 'body{}');
    }

    public function testSaveContentRewritesIndexPhp()
    {
        $fs = $this->fs();
        $fs->createHostDir('http://example.com');
        $fs->saveContent('/dir/index.php', 'x');

        $this->assertFileExists($this->hostDir() . DIRECTORY_SEPARATOR . 'dir' . DIRECTORY_SEPARATOR . 'index.htm');
    }

    public function testGetLinesTrimsAndSkipsBlanks()
    {
        $fs = $this->fs();
        $fs->createHostDir('http://example.com')->createListFile();
        $fs->saveList("a\n\n b \nc\n");

        $this->assertSame(['a', 'b', 'c'], iterator_to_array($fs->getLines(), false));
    }
}
