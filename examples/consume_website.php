<?php

use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpClient\Psr18Client;

require __DIR__ . "/../vendor/autoload.php";

// Initialize new Psr\HttpClient implementation. This example uses Symfony's implementation from the symfony/http-client
// package but you can use any implementation provided by your framework of choice.
$client = new Psr18Client(new NativeHttpClient());

// Create a new crawler
$crawler = new Fusonic\OpenGraph\Consumer($client, $client);

// Crawl the desired URL and retrieve a Fusonic\OpenGraph\Object in response
$object = $crawler->loadUrl("https://github.com");

var_dump($object);
