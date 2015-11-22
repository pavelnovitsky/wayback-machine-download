<?php

namespace Downloader\Validate;

use Downloader;

/**
 * Class Host
 * validate input param host
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Host extends Downloader\Validate
{
    /**
     * @return bool
     * @throws \UnexpectedValueException
     */
    public function process()
    {
        if ($this->isEmpty($this->value)) {
            throw new \UnexpectedValueException('Hostname can not be empty');
        }

        if (!$this->isUrl()) {
            throw new \UnexpectedValueException(sprintf('Hostname required (%s)', $this->value));
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isUrl()
    {
        return (bool) filter_var($this->value, FILTER_VALIDATE_URL);
    }
}
