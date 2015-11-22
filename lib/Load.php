<?php

namespace Downloader;

/**
 * Class Load
 * objects wrapper
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Load
{
    private $request = null;
    private $options = null;
    private $fso = null;
    private $api = null;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (is_null($this->request)) {
            $this->request = new Request();
        }

        return $this->request;
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        if (is_null($this->options)) {
            $this->options = new Options();
        }

        return $this->options;
    }

    /**
     * @return Fs
     */
    public function getFs()
    {
        if (is_null($this->fso)) {
            $this->fso = new Fs(PATH);
        }

        return $this->fso;
    }

    /**
     * @return Api
     */
    public function getApi()
    {
        if (is_null($this->api)) {
            $this->api = new Api(
                new Client,
                $this->getOptions()
            );
        }

        return $this->api;
    }
}
