# fusonic-opengraph

[![Build Status](https://travis-ci.org/fusonic/fusonic-opengraph.png)](https://travis-ci.org/fusonic/fusonic-opengraph)
[![Total Downloads](https://poser.pugx.org/fusonic/opengraph/downloads.png)](https://packagist.org/packages/fusonic/opengraph)

fusonic-opengraph is a simple library to read Open Graph data from the web. It also supports an optional fallback mode to provide basic meta data for pages that do not implement the Open Graph protocol.

See [ogp.me](http://ogp.me) for information on the Open Graph protocol.

## Requirements

* PHP 5.3 and up
* [fusonic/linq](https://github.com/fusonic/fusonic-linq)
* [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
* [symfony/css-selector](https://github.com/symfony/CssSelector)
* [symfony/dom-crawler](https://github.com/symfony/DomCrawler)

## Installation

The most flexible installation method is using Composer: Simply create a composer.json file in the root of your project:
``` json
{
    "require": {
        "fusonic/opengraph": "@dev"
    }
}
```

Install composer and run install command:
``` bash
curl -s http://getcomposer.org/installer | php
php composer.phar install
``` 

Once installed, include vendor/autoload.php in your script.

``` php
require "vendor/autoload.php";
```

## Usage

### Retrieve Open Graph data from a URL

``` php
$crawler = new Fusonic\OpenGraph\Crawler();
$object = $crawler->crawlUrl("https://github.com");

echo $object->title . " (" . $object->siteName . ")";
echo $object->images[0]->src;
```

## Running tests

You can run the test suite with the following command:

``` bash
phpunit --bootstrap tests/bootstrap.php .
``` 

## License

This library is licensed under the MIT license.
