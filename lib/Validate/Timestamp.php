<?php

namespace Downloader\Validate;

use Downloader;

/**
 * Class Timestamp
 * validate input param timestamp
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Timestamp extends Downloader\Validate
{
    /**
     * @return bool
     * @throws \UnexpectedValueException
     */
    public function process()
    {
        if ($this->isEmpty($this->value)) {
            return true;
        }

        if (!$this->isNumber()) {
            throw new \UnexpectedValueException(sprintf('Wrong timestamp format (%s)', $this->value));
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isNumber()
    {
        return ctype_digit($this->value);
    }
}
