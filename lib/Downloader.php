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
                Writer::send(ucfirst($k).' used: '.$v);
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
        $lines = $this->getFs()->getLines();
        $lineNumber = 1;

        foreach ($lines as $line) {

            // check web archive limitations:
            // if website was not blocked by robots.txt rules
            // or any other WBA Exception
            if ($lineNumber == 1 && strpos($line, 'Exception') !== FALSE) {
                $msgArray = explode(':', $line);
                $error = sprintf('Web Archive Error: %s', array_pop($msgArray));
                throw new \RuntimeException($error);
            }

            $linkInfo = $this->getApi()->prepareDownloadInfo($line);
            $content = $this->getApi()->downloadContent($linkInfo['link']);

            Writer::send(sprintf('%s', $linkInfo['link']));

            if ($content === false) {
                Writer::send(sprintf('Can\'t download resource %s', $linkInfo['link']), 'warning');
            }

            $this->getFs()->saveContent($linkInfo['path'], $content);
            $lineNumber++;
        }

        return $this;
    }
}
