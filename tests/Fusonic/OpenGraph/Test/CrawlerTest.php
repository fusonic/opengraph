<?php

namespace Fusonic\OpenGraph\Test;

use Fusonic\OpenGraph\Crawler;

class CrawlerTestTest extends \PHPUnit_Framework_TestCase
{
    public function testCrawlContentBasics()
    {
        $content = <<<LONG
<html>
<head>
<meta property="og:description" content="Description">
<meta property="og:determiner" content="auto">
<meta property="og:locale" content="en_GB">
<meta property="og:locale:alternate" content="en_US">
<meta property="og:locale:alternate" content="de_AT">
<meta property="og:site_name" content="Site name">
<meta property="og:title" content="Title">
<meta property="og:url" content="https://github.com/fusonic/fusonic-opengraph">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();

        $res = $crawler->crawlContent($content, "about:blank");

        $this->assertEquals("Description", $res->description);
        $this->assertEquals("auto", $res->determiner);
        $this->assertEquals("en_GB", $res->locale);
        $this->assertContains("en_US", $res->localeAlternate);
        $this->assertContains("de_AT", $res->localeAlternate);
        $this->assertEquals("Site name", $res->siteName);
        $this->assertEquals("Title", $res->title);
        $this->assertEquals("https://github.com/fusonic/fusonic-opengraph", $res->url);
    }

    public function testCrawlContentFallbacksOff()
    {
        $content = <<<LONG
<html>
<head>
<title>Title</title>
<meta property="description" content="Description">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();

        $res = $crawler->crawlContent($content, "about:blank");

        $this->assertNull($res->description);
        $this->assertNull($res->title);
        $this->assertNull($res->url);
    }

    public function testCrawlContentFallbacksOn()
    {
        $content = <<<LONG
<html>
<head>
<title>Title</title>
<meta property="description" content="Description">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();
        $crawler->useFallbackMode = true;

        $res = $crawler->crawlContent($content, "about:blank");

        $this->assertEquals("Description", $res->description);
        $this->assertEquals("Title", $res->title);
        $this->assertEquals("about:blank", $res->url);
    }
}
