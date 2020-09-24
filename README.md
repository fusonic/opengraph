# fusonic/opengraph

[![Latest Stable Version](https://poser.pugx.org/fusonic/opengraph/v/stable)](https://packagist.org/packages/fusonic/opengraph)
[![Total Downloads](https://poser.pugx.org/fusonic/opengraph/downloads)](https://packagist.org/packages/fusonic/opengraph)
[![Build Status](https://api.travis-ci.org/fusonic/opengraph.svg)](https://travis-ci.org/fusonic/opengraph)
[![License](https://poser.pugx.org/fusonic/opengraph/license)](https://packagist.org/packages/fusonic/opengraph)

A simple library to read Open Graph data from the web and generate HTML code to publish your own Open Graph objects. A fallback mode enables you to read data from websites that do not implement the Open Graph protocol.

Using this library you can easily retrieve stuff like meta data, video information from YouTube or Vimeo or image information from Flickr without using site-specific APIs since they all implement the Open Graph protocol.

See [ogp.me](http://ogp.me) for information on the Open Graph protocol.

## Requirements

* PHP 7.4+
* [fusonic/linq](https://github.com/fusonic/linq)
* [symfony/css-selector](https://github.com/symfony/CssSelector)
* [symfony/dom-crawler](https://github.com/symfony/DomCrawler)
* [psr/http-client](https://github.com/php-fig/http-client), [psr/http-factory](https://github.com/php-fig/http-factory) and compatible implementation such as [guzzle/guzzle](https://github.com/guzzle/guzzle)

## Installation

The most flexible installation method is using Composer: Simply create a composer.json file in the root of your project:
``` json
{
    "require": {
        "fusonic/opengraph": "^2.0"
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
use Fusonic\OpenGraph\Consumer;

$consumer = new Consumer($httpClient, $httpRequestFactory);
$object = $consumer->loadUrl("http://www.youtube.com/watch?v=P422jZg50X4");

// Basic information of the object
echo "Title: " . $object->title;                // Getting started with Facebook Open Graph
echo "Site name: " . $object->siteName;         // YouTube
echo "Description: " . $object->description;    // Originally recorded at the Facebook World ...
echo "Canonical URL: " . $object->url;          // http://www.youtube.com/watch?v=P422jZg50X4

// Images
$image = $object->images[0];
echo "Image[0] URL: " . $image->url             // https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg
echo "Image[0] height: " . $image->height       // null (May return height in pixels on other pages)
echo "Image[0] width: " . $image->width         // null (May return width in pixels on other pages)

// Videos
$video = $object->videos[0];
echo "Video URL: " . $video->url                // http://www.youtube.com/v/P422jZg50X4?version=3&autohide=1
echo "Video height: " . $video->height          // 1080
echo "Video width: " . $video->width            // 1920
echo "Video type: " . $video->type              // application/x-shockwave-flash
```

_There are some more properties but these are the basic and most commonly used ones._

### Publish own Open Graph data

``` php
use Fusonic\OpenGraph\Elements\Image;
use Fusonic\OpenGraph\Elements\Video;
use Fusonic\OpenGraph\Publisher;
use Fusonic\OpenGraph\Objects\Website;

$publisher = new Publisher();
$object = new Website();

// Basic information of the object
$object->title = "Getting started with Facebook Open Graph";
$object->siteName = "YouTube";
$object->description = "Originally recorded at the Facebook World ..."
$object->url = "http://www.youtube.com/watch?v=P422jZg50X4";

// Images
$image = new Image("https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg");
$object->images[] = $image;

// Videos
$video = new Video("http://www.youtube.com/v/P422jZg50X4?version=3&autohide=1");
$video->height = 1080;
$video->width = 1920;
$video->type = "application/x-shockwave-flash";
$object->videos[] = $video;

// Generate HTML code
echo $publisher->generateHtml($object);
// <meta property="og:description"
//       content="Originally recorded at the Facebook World ...">
// <meta property="og:image:url"
//       content="https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg">
// <meta property="og:site_name"
//       content="YouTube">
// <meta property="og:type"
//       content="website">
// <meta property="og:url"
//       content="http://www.youtube.com/watch?v=P422jZg50X4">
// <meta property="og:video:url"
//       content="http://www.youtube.com/v/P422jZg50X4?version=3&amp;autohide=1">
// <meta property="og:video:height"
//       content="1080">
// <meta property="og:video:type"
//       content="application/x-shockwave-flash">
// <meta property="og:video:width"
//       content="1920">
```

_HTML code is formatted just for displaying purposes. You may choose between HTML5/XHTML output using the ```$publisher->doctype``` property._

## Running tests

You can run the test suite by running `phpunit` from the command line.

## FAQ

**I don't get any information from a webpage, but Facebook shows information for the same URL. What do I do wrong?**

It seems that some pages (like Twitter) only publish OpenGraph information if Facebook's user agent string `facebookexternalhit/1.1` is used (see #28). So you should configure your PSR-18 client to use this user agent string:

```php
$client = new Psr18Client(new NativeHttpClient([ "headers" => [ "User-Agent" => "facebookexternalhit/1.1" ] ]));
```

## License

This library is licensed under the MIT license.
