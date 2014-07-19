<?php

namespace Fusonic\OpenGraph\Test;

use Fusonic\OpenGraph\Crawler;

class CrawlerTestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks crawler to read basic properties.
     */
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

        $res = $crawler->crawlHtml($content, "about:blank");

        $this->assertEquals("Description", $res->description);
        $this->assertEquals("auto", $res->determiner);
        $this->assertEquals("en_GB", $res->locale);
        $this->assertContains("en_US", $res->localeAlternate);
        $this->assertContains("de_AT", $res->localeAlternate);
        $this->assertEquals("Site name", $res->siteName);
        $this->assertEquals("Title", $res->title);
        $this->assertEquals("https://github.com/fusonic/fusonic-opengraph", $res->url);
    }

    /**
     * Checks crawler not to use fallback if disabled even if no OG data is provided.
     */
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

        $res = $crawler->crawlHtml($content, "about:blank");

        $this->assertNull($res->description);
        $this->assertNull($res->title);
        $this->assertNull($res->url);
    }

    /**
     * Checks crawler to correctly use fallback elements when activated.
     */
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

        $res = $crawler->crawlHtml($content, "about:blank");

        $this->assertEquals("Description", $res->description);
        $this->assertEquals("Title", $res->title);
        $this->assertEquals("about:blank", $res->url);
    }

    /**
     * Checks crawler to handle arrays of elements with child-properties like described in the
     * Open Graph documentation (http://ogp.me/#array).
     */
    public function testCrawlContentImageHandling()
    {
        $content = <<<LONG
<html>
<head>
<meta property="og:image" content="http://example.com/rock.jpg">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">
<meta property="og:image" content="http://example.com/rock2.jpg">
<meta property="og:image" content="http://example.com/rock3.jpg">
<meta property="og:image:height" content="1000">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();

        $res = $crawler->crawlHtml($content);

        $this->assertEquals(3, count($res->images));
        $this->assertEquals("http://example.com/rock.jpg", $res->images[0]->url);
        $this->assertEquals(300, $res->images[0]->width);
        $this->assertEquals(300, $res->images[0]->height);
        $this->assertEquals("http://example.com/rock2.jpg", $res->images[1]->url);
        $this->assertNull($res->images[1]->width);
        $this->assertNull($res->images[1]->height);
        $this->assertEquals("http://example.com/rock3.jpg", $res->images[2]->url);
        $this->assertNull($res->images[2]->width);
        $this->assertEquals(1000, $res->images[2]->height);
    }

    public function testCrawlHtmlImageExceptionDebugOff()
    {
        $content = <<<LONG
<html>
<head>
<meta property="og:image:width" content="300">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();

        $res = $crawler->crawlHtml($content);

        $this->assertEquals(0, count($res->images));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testCrawlHtmlImageExceptionDebugOn()
    {
        $content = <<<LONG
<html>
<head>
<meta property="og:image:width" content="300">
</head>
<body></body>
</html>
LONG;

        $crawler = new Crawler();
        $crawler->debug = true;

        $res = $crawler->crawlHtml($content);
    }
}
