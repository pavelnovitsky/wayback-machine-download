<?php

namespace Downloader;

use Downloader\Validate;

/**
 * Class Options
 * get and validate script input params
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Options
{
    private $data = [
        'host' => null,
        'timestamp' => 0,
    ];

    private $map = [
        'h' => 'host',
        'host' => 'host',
        't' => 'timestamp',
        'timestamp' => 'timestamp',
    ];

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function set($key, $value)
    {
        if (!isset($this->map[$key])) {
            throw new \RuntimeException(sprintf('Key %s is not supported', $key));
        }

        $this->data[$this->map[$key]] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        throw new \RuntimeException(sprintf('Undefined option "%s"', $key));
    }

    /**
     * @throws \UnexpectedValueException
     */
    public function validate()
    {
        foreach ($this->data as $type => $value) {
            $className = __NAMESPACE__ . '\\Validate\\' . ucfirst($type);
            (new $className())->setValue($value)->process();
        }
    }
}
