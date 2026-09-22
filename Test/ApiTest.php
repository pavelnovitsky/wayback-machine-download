<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiTest
 * @package Downloader\Test
 */
class ApiTest extends TestCase
{
    /**
     * @return Downloader\Client A client double that records the last URL.
     */
    private function fakeClient()
    {
        return new class extends Downloader\Client {
            public $lastUrl = null;
            public $canned = 'RESULT';

            public function __construct()
            {
            }

            public function setUrl($url)
            {
                $this->lastUrl = $url;
                return $this;
            }

            public function send()
            {
                return $this;
            }

            public function getResult()
            {
                return $this->canned;
            }
        };
    }

    /**
     * @dataProvider prepareProvider
     * @param string $line
     * @param string $expectedPath
     * @param string $expectedLinkPrefix
     */
    public function testPrepareDownloadInfo($line, $expectedPath, $expectedLinkPrefix)
    {
        $api = new Downloader\Api($this->fakeClient(), new Downloader\Options());
        $info = $api->prepareDownloadInfo($line);

        $this->assertSame($expectedPath, $info['path']);
        $this->assertStringStartsWith($expectedLinkPrefix, $info['link']);
    }

    /**
     * @return array
     */
    public function prepareProvider()
    {
        return [
            'root becomes index.html' => [
                '20180101 http://example.com/ com,example)/',
                '/index.html',
                'https://web.archive.org/web/20180101id_/',
            ],
            'path with a closing paren survives' => [
                '20180716 http://example.com/a(b)c.html com,example)/a(b)c.html',
                '/a(b)c.html',
                'https://web.archive.org/web/20180716id_/',
            ],
            'plain path' => [
                '20200101 http://example.com/page com,example)/page',
                '/page',
                'https://web.archive.org/web/20200101id_/',
            ],
        ];
    }

    public function testGetListBuildsHostUrl()
    {
        $client = $this->fakeClient();
        $options = new Downloader\Options();
        $options->set('host', 'http://example.com');

        $api = new Downloader\Api($client, $options);
        $result = $api->getList();

        $this->assertSame('RESULT', $result);
        $this->assertStringStartsWith('https://web.archive.org/cdx/search/cdx?url=http://example.com', $client->lastUrl);
        $this->assertStringNotContainsString('&from=', $client->lastUrl);
        $this->assertStringNotContainsString('&to=', $client->lastUrl);
    }

    public function testGetListAppendsTimestamp()
    {
        $client = $this->fakeClient();
        $options = new Downloader\Options();
        $options->set('host', 'http://example.com');
        $options->set('timestamp', '20060716');

        $api = new Downloader\Api($client, $options);
        $api->getList();

        $this->assertStringContainsString('&from=20060716', $client->lastUrl);
    }

    public function testGetListAppendsTo()
    {
        $client = $this->fakeClient();
        $options = new Downloader\Options();
        $options->set('host', 'http://example.com');
        $options->set('to', '20160101');

        $api = new Downloader\Api($client, $options);
        $api->getList();

        $this->assertStringContainsString('&to=20160101', $client->lastUrl);
    }

    public function testGetListAppendsBothBounds()
    {
        $client = $this->fakeClient();
        $options = new Downloader\Options();
        $options->set('host', 'http://example.com');
        $options->set('timestamp', '20050101');
        $options->set('to', '20160101');

        $api = new Downloader\Api($client, $options);
        $api->getList();

        $this->assertStringContainsString('&from=20050101', $client->lastUrl);
        $this->assertStringContainsString('&to=20160101', $client->lastUrl);
    }
}
