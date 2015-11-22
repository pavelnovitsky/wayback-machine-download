<?php

namespace Downloader;

/**
 * Class Colors
 * generate CLI background color
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Colors
{
    private $colors = [
        'failure' => '[41m',    // red
        'success' => '[42m',    // green
        'warning' => '[43m',    // yellow
        'note' => '[44m',       // blue
    ];

    private $string = null;

    /**
     * @param string $string
     * @param string $type
     */
    public function __construct($string, $type)
    {
        $this->string = sprintf($this->getColor($type), $string);
        return $this;
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->string;
    }

    /**
     * @param string $type
     * @return string string
     */
    public function getColor($type)
    {
        $type = strtolower($type);
        $color = isset($this->colors[$type])?$this->colors[$type]:$this->colors['warning'];
        return chr(27) . $color . '%s' . chr(27) . '[0m';
    }
}
