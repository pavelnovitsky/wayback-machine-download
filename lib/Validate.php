<?php

namespace Downloader;

/**
 * Class Host
 * validate input params
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
abstract class Validate
{
    protected $value = null;
    /**
     * @param $value
     */
    public function __construct($value = null)
    {
        if (!is_null($value)) {
            $this->value = $value;
        }
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     * @return void
     */
    abstract protected function process();

    /**
     * Check if passed variable is empty
     * @param mixed $value
     * @return bool
     */
    protected function isEmpty($value)
    {
        return empty($value);
    }
}
