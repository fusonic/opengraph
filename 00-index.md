---
layout: index
title: Home
permalink: /
---

A simple library to read Open Graph data from the web and generate HTML code to publish your own Open Graph objects. A fallback mode enables you to read data from websites that do not implement the Open Graph protocol.

Using this library you can easily retrieve stuff like meta data, video information from YouTube or Vimeo or image information from Flickr without using site-specific APIs since they all implement the Open Graph protocol.

See [ogp.me](http://ogp.me) for information on the Open Graph protocol.

## Requirements

* PHP 5.4 and up
* [fusonic/linq](https://github.com/fusonic/linq)
* [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
* [symfony/css-selector](https://github.com/symfony/CssSelector)
* [symfony/dom-crawler](https://github.com/symfony/DomCrawler)

## Installation

The most flexible installation method is using Composer: Simply create a composer.json file in the root of your project:

{% highlight json %}
{
    "require": {
        "fusonic/opengraph": "@dev"
    }
}
{% endhighlight %}

Install composer and run install command:

{% highlight bash %}
curl -s http://getcomposer.org/installer | php
php composer.phar install
{% endhighlight %}

Once installed, include vendor/autoload.php in your script.

{% highlight php startinline %}
require "vendor/autoload.php";
{% endhighlight %}

## License

This library is licensed under the MIT license.
