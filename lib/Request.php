<?php

namespace Downloader;

/**
 * Class Request
 * define script input params
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Request
{

    protected static $options = [];
    protected $shortOpts = null;
    protected $longOpts = null;

    /**
     * init input options
     * -h, --host — site url for download
     * -t, --timestamp — start download from specific time
     */
    public function __construct()
    {
        $this->shortOpts = 'h:t:';
        $this->longOpts = [
            'host:',
            'timestamp::',
        ];
    }

    /**
     * Receive input options
     * @return array
     */
    public function getOptions()
    {
        if (empty(self::$options)) {
            throw new \RuntimeException('Can\'t proceed without options');
        } else {
            if (!self::$options || !count(self::$options)) {
                throw new \RuntimeException('WayBack script should be run with options!');
            }
        }

        return self::$options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options = [])
    {
        if (!count($options)) {
            $options = $this->getCliOptions();
        }

        self::$options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getCliOptions()
    {
        return getopt($this->shortOpts, $this->longOpts);
    }
}
