<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\OpenGraph\Test;

use Fusonic\OpenGraph\Consumer;
use PHPUnit\Framework\TestCase;

final class ConsumerTest extends TestCase
{
    /**
     * Checks crawler to read basic properties.
     */
    public function testLoadHtmlBasics(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:description" content="Description">
                    <meta property="og:determiner" content="auto">
                    <meta property="og:locale" content="en_GB">
                    <meta property="og:locale:alternate" content="en_US">
                    <meta property="og:locale:alternate" content="de_AT">
                    <meta property="og:rich_attachment" content="True">
                    <meta property="og:see_also" content="https://github.com/fusonic/fusonic-linq">
                    <meta property="og:see_also" content="https://github.com/fusonic/fusonic-spreadsheetexport">
                    <meta property="og:site_name" content="Site name">
                    <meta property="og:title" content="Title">
                    <meta property="og:updated_time" content="2014-07-20T17:51:00+02:00">
                    <meta property="og:url" content="https://github.com/fusonic/fusonic-opengraph">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content, 'about:blank');

        // assert
        self::assertSame('Description', $result->description);
        self::assertSame('auto', $result->determiner);
        self::assertSame('en_GB', $result->locale);
        self::assertContains('en_US', $result->localeAlternate);
        self::assertContains('de_AT', $result->localeAlternate);
        self::assertTrue($result->richAttachment);
        self::assertContains('https://github.com/fusonic/fusonic-linq', $result->seeAlso);
        self::assertContains('https://github.com/fusonic/fusonic-spreadsheetexport', $result->seeAlso);
        self::assertSame('Site name', $result->siteName);
        self::assertSame('Title', $result->title);
        self::assertInstanceOf(\DateTimeInterface::class, $result->updatedTime);
        self::assertSame('https://github.com/fusonic/fusonic-opengraph', $result->url);
    }

    /**
     * Checks crawler not to use fallback if disabled even if no OG data is provided.
     */
    public function testLoadHtmlFallbacksOff(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <title>Title</title>
                    <meta property="description" content="Description">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content, 'about:blank');

        // assert
        self::assertNull($result->description);
        self::assertNull($result->title);
        self::assertNull($result->url);
    }

    /**
     * Checks crawler to correctly use fallback elements when activated.
     */
    public function testLoadHtmlFallbacksOn(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <title>Title</title>
                    <meta property="description" content="Description">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();
        $consumer->useFallbackMode = true;

        // act
        $result = $consumer->loadHtml($content, 'about:blank');

        // assert
        self::assertSame('Description', $result->description);
        self::assertSame('Title', $result->title);
        self::assertSame('about:blank', $result->url);
    }

    /**
     * Checks crawler to correctly use fallback elements when activated.
     */
    public function testLoadHtmlCanonicalLinkFallbacksOn(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <title>Title</title>
                    <meta property="description" content="Description">
                    <link rel="canonical" href="https://github.com/fusonic/opengraph">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();
        $consumer->useFallbackMode = true;

        // act
        $result = $consumer->loadHtml($content, 'about:blank');

        // assert
        self::assertSame('Description', $result->description);
        self::assertSame('Title', $result->title);
        self::assertSame('https://github.com/fusonic/opengraph', $result->url);
    }

    /**
     * Checks crawler to handle arrays of elements with child-properties like described in the
     * Open Graph documentation (http://ogp.me/#array).
     */
    public function testLoadHtmlArrayHandling(): void
    {
        // arrange
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

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(3, $result->images);
        self::assertSame('http://example.com/rock.jpg', $result->images[0]->url);
        self::assertSame(300, $result->images[0]->width);
        self::assertSame(300, $result->images[0]->height);
        self::assertSame('http://example.com/rock2.jpg', $result->images[1]->url);
        self::assertNull($result->images[1]->width);
        self::assertNull($result->images[1]->height);
        self::assertSame('http://example.com/rock3.jpg', $result->images[2]->url);
        self::assertNull($result->images[2]->width);
        self::assertSame(1000, $result->images[2]->height);
    }

    public function testLoadHtmlImages(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:image" content="http://example.com/rock.jpg">
                    <meta property="og:image:secure_url" content="https://example.com/rock.jpg">
                    <meta property="og:image:width" content="300">
                    <meta property="og:image:height" content="300">
                    <meta property="og:image:type" content="image/jpg">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(1, $result->images);
        self::assertSame('http://example.com/rock.jpg', $result->images[0]->url);
        self::assertSame('https://example.com/rock.jpg', $result->images[0]->secureUrl);
        self::assertSame(300, $result->images[0]->width);
        self::assertSame(300, $result->images[0]->height);
        self::assertSame('image/jpg', $result->images[0]->type);
    }

    public function testLoadHtmlVideos(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:video" content="http://example.com/rock.ogv">
                    <meta property="og:video:secure_url" content="https://example.com/rock.ogv">
                    <meta property="og:video:width" content="300">
                    <meta property="og:video:height" content="300">
                    <meta property="og:video:type" content="video/ogv">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(1, $result->videos);
        self::assertSame('http://example.com/rock.ogv', $result->videos[0]->url);
        self::assertSame('https://example.com/rock.ogv', $result->videos[0]->secureUrl);
        self::assertSame(300, $result->videos[0]->width);
        self::assertSame(300, $result->videos[0]->height);
        self::assertSame('video/ogv', $result->videos[0]->type);
    }

    public function testLoadHtmlAudios(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:audio" content="http://example.com/rock.mp3">
                    <meta property="og:audio:secure_url" content="https://example.com/rock.mp3">
                    <meta property="og:audio:type" content="audio/mp3">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(1, $result->audios);
        self::assertSame('http://example.com/rock.mp3', $result->audios[0]->url);
        self::assertSame('https://example.com/rock.mp3', $result->audios[0]->secureUrl);
        self::assertSame('audio/mp3', $result->audios[0]->type);
    }

    public function testCrawlHtmlImageExceptionDebugOff(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:image:width" content="300">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(0, $result->images);
    }

    public function testCrawlHtmlImageExceptionDebugOn(): void
    {
        // assert
        $this->expectException(\UnexpectedValueException::class);

        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:image:width" content="300">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();
        $consumer->debug = true;

        // act
        $consumer->loadHtml($content);
    }

    public function testCrawlHtmlVideoExceptionDebugOff(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:video:width" content="300">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(0, $result->videos);
    }

    public function testCrawlHtmlVideoExceptionDebugOn(): void
    {
        // assert
        $this->expectException(\UnexpectedValueException::class);

        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:video:width" content="300">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();
        $consumer->debug = true;

        // act
        $consumer->loadHtml($content);
    }

    public function testCrawlHtmlAudioExceptionDebugOff(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:audio:secure_url" content="300">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertCount(0, $result->audios);
    }

    public function testCrawlHtmlAudioExceptionDebugOn(): void
    {
        // assert
        $this->expectException(\UnexpectedValueException::class);

        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:audio:type" content="audio/mp3">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();
        $consumer->debug = true;

        // act
        $consumer->loadHtml($content);
    }

    public function testLoadHtmlSpecialCharacters(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta property="og:title" content="Apples &amp; Bananas - just &quot;Fruits&quot;">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertSame('Apples & Bananas - just "Fruits"', $result->title);
    }

    public function testReadMetaName(): void
    {
        // arrange
        $content = <<<LONG
            <html>
                <head>
                    <meta name="og:title" content="A 'name' attribute instead of 'property'">
                </head>
                <body></body>
            </html>
            LONG;

        $consumer = new Consumer();

        // act
        $result = $consumer->loadHtml($content);

        // assert
        self::assertSame("A 'name' attribute instead of 'property'", $result->title);
    }
}
