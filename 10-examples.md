---
layout: page
title: Examples
permalink: /examples/
---

### Retrieve Open Graph data from a URL

{{ "{% highlight php linenos "}}%}
use Fusonic\OpenGraph\Consumer;

$consumer = new Consumer();
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
{{ "{% endhighlight "}}%}

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
// <meta property="og:description" content="Originally recorded at the Facebook World ...">
// <meta property="og:image:url" content="https://i1.ytimg.com/vi/P422jZg50X4/maxresdefault.jpg">
// <meta property="og:site_name" content="YouTube">
// <meta property="og:type" content="website">
// <meta property="og:url" content="http://www.youtube.com/watch?v=P422jZg50X4">
// <meta property="og:video:url" content="http://www.youtube.com/v/P422jZg50X4?version=3&amp;autohide=1">
// <meta property="og:video:height" content="1080">
// <meta property="og:video:type" content="application/x-shockwave-flash">
// <meta property="og:video:width" content="1920">
```

_HTML code is formatted just for displaying purposes. You may choose between HTML5/XHTML output using the ```$publisher->doctype``` property._
