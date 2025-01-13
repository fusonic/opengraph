# fusonic/opengraph

[![GitHub Release](https://img.shields.io/github/v/release/fusonic/opengraph)](https://github.com/fusonic/opengraph/releases/latest)
[![Packagist Downloads](https://img.shields.io/packagist/dt/fusonic/opengraph?color=blue)](https://packagist.org/packages/fusonic/opengraph)
[![Packagist License](https://img.shields.io/packagist/l/fusonic/opengraph)](https://github.com/fusonic/opengraph/blob/master/LICENSE)
[![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/fusonic/opengraph/test.yml?logo=github&label=Tests)](https://github.com/fusonic/opengraph/actions/workflows/test.yml)

A simple library to read Open Graph data from the web and generate HTML code to publish your own Open Graph objects. A
fallback mode enables you to read data from websites that do not implement the Open Graph protocol.

Using this library you can easily retrieve stuff like metadata, video information from YouTube or Vimeo or image
information from Flickr without using site-specific APIs since they all implement the Open Graph protocol.

See [ogp.me](https://ogp.me) for information on the Open Graph protocol.

## Requirements

* PHP 8.1+
* [symfony/css-selector](https://github.com/symfony/css-selector)
* [symfony/dom-crawler](https://github.com/symfony/dom-crawler)
* [psr/http-client](https://github.com/php-fig/http-client)
* [psr/http-factory](https://github.com/php-fig/http-factory)
* and compatible implementation such as [symfony/http-client](https://github.com/symfony/http-client)

## Installation

The most flexible installation method is using Composer:

```bash
composer require fusonic/opengraph
```

Install Composer and run the `install` command:
```bash
curl -s http://getcomposer.org/installer | php
php composer.phar install
``` 

Once installed, include `vendor/autoload.php` in your script.

``` php
require 'vendor/autoload.php';
```

## Usage

### Retrieve Open Graph data from a URL

```php
<?php

use Fusonic\OpenGraph\Consumer;

$consumer = new Consumer($httpClient, $httpRequestFactory);
$object = $consumer->loadUrl('https://www.youtube.com/watch?v=P422jZg50X4');

// Basic information of the object
echo "Title: " . $object->title;                 // Getting started with Facebook Open Graph
echo "Site name: " . $object->siteName;          // YouTube
echo "Description: " . $object->description;     // Originally recorded at the Facebook World ...
echo "Canonical URL: " . $object->url;           // https://www.youtube.com/watch?v=P422jZg50X4

// Images
$image = $object->images[0];
echo "Image[0] URL: " . $image->url;             // https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg
echo "Image[0] height: " . $image->height;       // null (May return height in pixels on other pages)
echo "Image[0] width: " . $image->width;         // null (May return width in pixels on other pages)

// Videos
$video = $object->videos[0];
echo "Video URL: " . $video->url;                // https://www.youtube.com/v/P422jZg50X4?version=3&autohide=1
echo "Video height: " . $video->height;          // 1080
echo "Video width: " . $video->width;            // 1920
echo "Video type: " . $video->type;              // application/x-shockwave-flash
```

_There are some more properties but these are the basic and most commonly used ones._

### Publish own Open Graph data

```php
<?php

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
$object->url = "https://www.youtube.com/watch?v=P422jZg50X4";

// Images
$image = new Image("https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg");
$object->images[] = $image;

// Videos
$video = new Video("https://www.youtube.com/v/P422jZg50X4?version=3&autohide=1");
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

You can run the test suite by running `composer test` from the command line.

## FAQ

**I don't get any information from a webpage, but Facebook shows information for the same URL. What do I do wrong?**

It seems that some pages (like Twitter/X) only publish Open Graph information if Facebook's user agent string
`facebookexternalhit/1.1` is used (see [#28](https://github.com/fusonic/opengraph/issues/28)). So you should configure
your PSR-18 client to use this user agent string:

```php
$client = new Psr18Client(new NativeHttpClient(['headers' => ['User-Agent' => 'facebookexternalhit/1.1']]));
```

## License

fusonic/opengraph is licensed under the MIT license. See [LICENSE](LICENSE) for more information.
