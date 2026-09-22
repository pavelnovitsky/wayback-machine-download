<?php

namespace Downloader;

/**
 * Class Downloader
 *
 * @package WayBack Downloader
 * @author Pavel Novitsky <mail@pavel-novitsky.com>
 */
class Downloader extends Load
{

    /**
     * run main process
     */
    public function __construct()
    {
        $this->process();
    }

    /**
     * run downloader
     */
    public function process()
    {
        try {
            Writer::send('Start ' . date('d-m-Y H:i'), 'success');

            Profile::start();

            $this
                ->setOptions()
                ->prepareDirs();

            Writer::send('Retrieving list');

            $this
                ->getFs()
                ->saveList($this->getApi()->getList());

            Writer::send('Processing list');

            $this->processList();

            Writer::send('End ' . date('d-m-Y H:i'), 'success');

            $profiler = Profile::getResult();
            foreach ($profiler as $k => $v) {
                Writer::send(ucfirst($k) . ' used: ' . $v);
            }

        } catch (\Exception $e) {
            Writer::send($e->getMessage(), 'failure');
        }
    }

    /**
     * @return $this
     */
    protected function setOptions()
    {
        $options = $this
            ->getRequest()
            ->setOptions()
            ->getOptions();

        foreach ($options as $k => $v) {
            $this->getOptions()->set($k, $v);
        }

        $this->getOptions()->validate();

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepareDirs()
    {
        $this
            ->getFs()
            ->createHostDir($this->getOptions()->get('host'))
            ->createListFile();

        return $this;
    }

    /**
     * @return $this
     */
    protected function processList()
    {
        foreach ($this->getLatestSnapshots() as $line) {

            // a single unreachable resource must not abort the whole crawl
            try {
                $linkInfo = $this->getApi()->prepareDownloadInfo($line);
                $content = $this->getApi()->downloadContent($linkInfo['link']);

                Writer::send(sprintf('%s', $linkInfo['link']));

                $this->getFs()->saveContent($linkInfo['path'], $content);
            } catch (\Exception $e) {
                Writer::send(
                    sprintf('Can\'t download resource from line "%s": %s', $line, $e->getMessage()),
                    'warning'
                );
            }
        }

        return $this;
    }

    /**
     * Reduce the CDX list to the newest capture per URL.
     *
     * The archive lists captures ordered by url key then ascending timestamp,
     * so the last line seen for a given key is its most recent snapshot. Keeping
     * one line per key avoids re-downloading every historical capture.
     *
     * @return array
     * @throws \RuntimeException on a first-line Web Archive error
     */
    protected function getLatestSnapshots()
    {
        $latest = [];
        $firstLine = true;

        foreach ($this->getFs()->getLines() as $line) {

            // check web archive limitations: robots.txt block or other
            // WBA exception is reported as the first line of the response
            if ($firstLine) {
                $firstLine = false;
                if (strpos($line, 'Exception') !== false) {
                    $msgArray = explode(':', $line);
                    $error = sprintf('Web Archive Error: %s', array_pop($msgArray));
                    throw new \RuntimeException($error);
                }
            }

            $fields = explode(' ', $line);
            if (count($fields) < 3) {
                continue;
            }

            // field 2 is the CDX url key; last occurrence wins (newest)
            $latest[$fields[2]] = $line;
        }

        return $latest;
    }
}
