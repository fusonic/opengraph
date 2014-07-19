<?php

use Fusonic\OpenGraph\Elements\Image;
use Fusonic\OpenGraph\Website;

if (!$loader = @include __DIR__.'/../vendor/autoload.php') {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

// Construct a new Open Graph object
$website = new Website();

// Set some basic properties
$website->url = "http://www.fusonic.net";
$website->title = "Fusonic - Intranet & Mobile Applications from Austria";
$website->description = "Creators of the awesome fusonic-opengraph library.";
$website->siteName = "Fusonic";
$website->locale = "en_GB";

// Attach an image
$image = new Image("http://www.fusonic.net/en/assets/images/logo.png");
$image->width = 140;
$image->height = 41;
$image->type = "image/png";
$website->images[] = $image;

// Now render all tags
echo $website->getHtml();
