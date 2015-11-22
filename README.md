# WayBack Downloader
[![Build Status](https://travis-ci.org/pavelnovitsky/wayback-machine-download.svg?branch=master)](https://travis-ci.org/pavelnovitsky/wayback-machine-download)

Download any website from the Internet Archive Wayback Machine.

## Installation

1. Clone repo
    git clone https://github.com/pavelnovitsky/wayback-downloader.git
2. Setup write permissions on the "websites" folder

## Basic Usage

Run WayBack Downloader with the base url of the website you want to retrieve as a parameter (e.g., http://example.com):

    php downloader.php -h http://example.com

Downloaded files are saved to the webdsites/{domain}/* directory. For this example it will be websites/example.com/


## Options

* -h, --host — mandatory parameter, base url of the downloaded website
* -t, --timstamp — optional parameter to set the earliest date of the Web Archive snapshots.  WayBack Downloader won't download files added before the specified date. Timestamp format: *yyyyMMddhhmmss*

## Examples

> http://web.archive.org/web/20060716231334/http://example.com

    php downloader.php -h http://example.com

    php downloader.php --host=http://example.com

    php downloader.php -h http://example.com -t 20060716231334

    php downloader.php --host=http://example.com --timestamp=20060716231334

##TODO

* Add full test coverage
* Add separated timestamp options "from" and "to"
* Add optional url filter (ex.: only directory, *.jpg, etc)
* Add results limiting
* Access Control support

##Resources used
[Wayback CDX Server API](https://github.com/internetarchive/wayback/tree/master/wayback-cdx-server)

##Contributing
You are welcome to contribute with pull requests

##Bug tracking
WayBack Downloader uses [GitHub issues](https://github.com/pavelnovitsky/wayback-downloader/issues). If you have found bug, please create an issue.

##License
This library is released under the terms of the MIT License.