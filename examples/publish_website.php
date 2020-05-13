<?php

use Fusonic\OpenGraph\Elements\Audio;
use Fusonic\OpenGraph\Elements\Image;
use Fusonic\OpenGraph\Elements\Video;
use Fusonic\OpenGraph\Objects\Website;
use Fusonic\OpenGraph\Publisher;

require __DIR__ . "/../vendor/autoload.php";

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

// Attach a video
$video = new Video("http://www.fusonic.net/en/we-dont-have-no-video.mp4");
$video->width = 1920;
$video->height = 1080;
$video->type = "video/mp4";
$website->videos[] = $video;

// Attach an audio
$audio = new Audio("http://www.fusonic.net/en/we-dont-have-no-audio.mp3");
$audio->type = "audio/mp3";
$website->audios[] = $audio;

// Create Publisher object and echo HTML code
$publisher = new Publisher();
$publisher->doctype = Publisher::DOCTYPE_HTML5;
echo $publisher->generateHtml($website);
