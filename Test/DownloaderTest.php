<?php

namespace Downloader\Test;

use Downloader;
use PHPUnit\Framework\TestCase;

/**
 * Class DownloaderTest
 *
 * Exercises the list-reduction logic in isolation. Downloader's real
 * constructor runs the whole download, so a test double skips it and feeds
 * canned CDX lines through a fake file system.
 *
 * @package Downloader\Test
 */
class DownloaderTest extends TestCase
{
    private function downloaderWithLines(array $lines)
    {
        return new class($lines) extends Downloader\Downloader {
            private $lines;

            public function __construct(array $lines)
            {
                $this->lines = $lines;
            }

            public function getFs()
            {
                $lines = $this->lines;
                return new class($lines) extends Downloader\Fs {
                    private $lines;

                    public function __construct(array $lines)
                    {
                        $this->lines = $lines;
                    }

                    public function getLines()
                    {
                        foreach ($this->lines as $line) {
                            yield $line;
                        }
                    }
                };
            }

            public function latest()
            {
                return array_values($this->getLatestSnapshots());
            }
        };
    }

    public function testKeepsNewestCapturePerUrl()
    {
        $downloader = $this->downloaderWithLines([
            '20150101 http://example.com/p1 com,example)/p1',
            '20180101 http://example.com/p1 com,example)/p1',
            '20170101 http://example.com/p2 com,example)/p2',
            '20190101 http://example.com/p2 com,example)/p2',
        ]);

        $latest = $downloader->latest();

        $this->assertCount(2, $latest);
        $this->assertSame('20180101 http://example.com/p1 com,example)/p1', $latest[0]);
        $this->assertSame('20190101 http://example.com/p2 com,example)/p2', $latest[1]);
    }

    public function testSkipsMalformedLines()
    {
        $downloader = $this->downloaderWithLines([
            'incomplete-line',
            '20190101 http://example.com/p2 com,example)/p2',
        ]);

        $this->assertCount(1, $downloader->latest());
    }

    public function testThrowsOnFirstLineArchiveException()
    {
        $downloader = $this->downloaderWithLines([
            'org.archive.wayback.exception.RobotAccessControlException: blocked',
        ]);

        $this->expectException(\RuntimeException::class);
        $downloader->latest();
    }
}
