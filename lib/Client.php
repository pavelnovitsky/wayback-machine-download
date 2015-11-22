<?php

namespace Downloader;

/**
 * Class Clent
 * cURL wrapper
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Client
{
    private $url = null;
    private $userAgent = null;
    private $curlResource = null;
    private $timeout = null;
    private $result = null;

    /**
     * init cURL
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('cURL extension is not loaded');
        }

        $this->userAgent = 'WayBack PHP Downloader';
        $this->timeout = 60;
        $this->curlResource = curl_init();
    }

    /**
     * close cURL
     */
    public function __destruct()
    {
        if ($this->curlResource) {
            curl_close($this->curlResource);
        }
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getUrl()
    {
        if (is_null($this->url)) {
            throw new \RuntimeException('Client URL undefined');
        }

        return $this->url;
    }

    /**
     * @param string $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * set cURL options
     */
    public function setOptions()
    {
        curl_setopt($this->curlResource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlResource, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->curlResource, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->curlResource, CURLOPT_HEADER, false);
        curl_setopt($this->curlResource, CURLOPT_FORBID_REUSE, false);
        curl_setopt($this->curlResource, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * @return $this
     * @throws \RuntimeException
     */
    public function send()
    {
        $this->setOptions();
        curl_setopt($this->curlResource, CURLOPT_URL, $this->getUrl());

        $result = curl_exec($this->curlResource);

        if ($result === false) {
            throw new \RuntimeException(curl_error($this->curlResource));
        }

        $this->setResult($result);
        return $this;
    }
}
