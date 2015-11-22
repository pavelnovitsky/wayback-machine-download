<?php

namespace Downloader;

/**
 * Class Api requests handler
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Api
{
    // exact, prefix, host, domain
    const API_URL = 'http://web.archive.org/cdx/search/cdx?url=%s&matchType=host&fl=timestamp,original,urlkey&filter=statuscode:200';
    const DOWNLOAD_URL = 'http://web.archive.org/web/%sid_/%s';

    private $client = null;
    private $options = null;

    /**
     * @param Client $client
     * @param Options $options
     */
    public function __construct(Client $client, Options $options)
    {
        $this->client = $client;
        $this->options = $options;
    }

    /**
     * @return null|string
     */
    public function getList()
    {
        $url = sprintf(self::API_URL, $this->options->get('host'));

        if ($this->options->get('timestamp')) {
            $url .= '&from=' . $this->options->get('timestamp');
        }

        return $this->client->setUrl($url)->send()->getResult();
    }

    /**
     * @param string $line
     * @return array
     */
    public function prepareDownloadInfo($line)
    {
        list($timestamp, $original, $key) = explode(' ', $line);
        list(, $urlKey) = explode(')', $key);

        if ($urlKey == '/') {
            $urlKey = '/index.html';
        }

        return [
            'link' => sprintf(self::DOWNLOAD_URL, $timestamp, $original),
            'path' => $urlKey
        ];
    }

    /**
     * @param string $link
     * @return null|string
     */
    public function downloadContent($link)
    {
        return $this->client->setUrl($link)->send()->getResult();
    }
}
