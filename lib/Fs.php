<?php

namespace Downloader;

/**
 * Class Fs
 * file system operations
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Fs
{
    private $path = null;
    private $websitesDir = null;
    private $hostDir = null;
    private $tmpListFile = null;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        $this->websitesDir = $this->path . DIRECTORY_SEPARATOR . 'websites';
        $this->check();
    }

    /**
     * Check if required directory exists
     * @throws \RuntimeException
     */
    public function check()
    {
        if (!file_exists($this->websitesDir)) {
            throw new \RuntimeException(sprintf('Websites directory "%s" is not available', $this->websitesDir));
        }

        if (!is_writable($this->websitesDir)) {
            throw new \RuntimeException(sprintf('Websites directory "%s" is not writable', $this->websitesDir));
        }
    }

    /**
     * @param string $host
     * @return $this
     */
    public function createHostDir($host)
    {
        $data = parse_url($host);

        if (isset($data['host'])) {
            $path = $this->websitesDir . DIRECTORY_SEPARATOR . $data['host'];
            $this->mkdir($path);
            $this->hostDir = $path;
        } else {
            throw new \RuntimeException('Hostname was not defined');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function createListFile()
    {
        if (is_null($this->hostDir)) {
            throw new \RuntimeException('Host directory was not found');
        }

        if (is_null($this->tmpListFile)) {
            $path = $this->hostDir . DIRECTORY_SEPARATOR . md5($this->hostDir) . '.txt';
            $handle = fopen($path, 'w');
            if (!$handle) {
                throw new \RuntimeException('Can\'t create list temporal file');
            }

            $this->tmpListFile = $path;
            fclose($handle);
        }

        return $this;
    }

    /**
     * @param string $path
     * @throws \RuntimeException
     */
    public function mkdir($path)
    {
        if (file_exists($path)) {
            return;
        }

        $result = @mkdir($path, 0777, true);
        if (!$result) {
            throw new \RuntimeException(sprintf('Can not create directory "%s"', $path));
        }
    }

    /**
     * @param string $data
     */
    public function saveList($data)
    {
        file_put_contents($this->tmpListFile, $data);
    }

    /**
     * remove tmp list file
     * destroy object
     */
    public function __destruct()
    {
        unlink($this->tmpListFile);
    }

    /**
     * @return \Generator
     * @throws \RuntimeException
     */
    public function getLines()
    {
        $handle = fopen($this->tmpListFile, 'r');
        if (!$handle) {
            throw new \RuntimeException('List file was not found');
        }
        while ($line = fgets($handle)) {
            yield trim($line);
        }
        fclose($handle);
    }

    /**
     * @param string $urlKey
     * @param string $content
     * @throws \RuntimeException
     * @return $this
     */
    public function saveContent($urlKey, $content)
    {
        $path = $this->hostDir . str_replace('/', DIRECTORY_SEPARATOR, $urlKey);
        $pathInfo = pathinfo($path);

        if (!isset($pathInfo['extension'])) {
            $path .= DIRECTORY_SEPARATOR . 'index.html';
        }

        $dirPath = dirname($path);

        $this->mkdir($dirPath);
        $result = file_put_contents($path, $content);

        if ($result === false) {
            throw new \RuntimeException(sprintf('Write to "%s" failed', $path));
        }

        return $this;
    }
}
