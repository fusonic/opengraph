<?php

namespace Fusonic\OpenGraph;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

class Crawler
{
    const DESCRIPTION = "description";
    const DETERMINER = "determiner";
    const IMAGE = "image";
    const IMAGE_URL = "image:url";
    const IMAGE_SECURE_URL = "image:secure_url";
    const IMAGE_TYPE = "image:type";
    const IMAGE_WIDTH = "image:width";
    const IMAGE_HEIGHT = "image:height";
    const LOCALE = "locale";
    const LOCALE_ALTERNATE = "locale:alternate";
    const SITE_NAME = "site_name";
    const TITLE = "title";
    const URL = "url";
    const VIDEO = "video";
    const VIDEO_URL = "video:url";
    const VIDEO_SECURE_URL = "video:secure_url";
    const VIDEO_TYPE = "video:type";
    const VIDEO_WIDTH = "video:width";
    const VIDEO_HEIGHT = "video:height";

    public $useFallbackMode = false;

    public function __construct()
    {
    }

    public function crawlUrl($url)
    {
        $content = file_get_contents($url);
        return $this->crawlContent($content, $url);
    }

    public function crawlContent($content, $url = null)
    {
        // Create new page instance holding information
        $page = new Page();

        // Extract all data that can be found
        $this->extractOpenGraphData($content, $page);

        // Use the user's URL as fallback
        if ($this->useFallbackMode && $page->url === null) {
            $page->url = $url;
        }

        // Return result
        return $page;
    }

    private function extractOpenGraphData($content, Page $page)
    {
        $crawler = new SymfonyCrawler($content);

        // Get all meta-tags starting with "og:"
        $ogMetaTags = $crawler->filter("meta[property^='og:']");

        // Walk all properties and attach to page
        foreach ($ogMetaTags as $tag) {
            $name = strtolower(trim(substr($tag->getAttribute("property"), 3)));
            $value = trim($tag->getAttribute("content"));

            switch($name) {
                case self::DESCRIPTION:
                    if ($page->description === null) {
                        $page->description = $value;
                    }
                    break;
                case self::DETERMINER:
                    if ($page->determiner === null) {
                        $page->determiner = $value;
                    }
                    break;
                case self::IMAGE:
                case self::IMAGE_URL:
                    $page->images[] = new Image($value);
                    break;
                case self::IMAGE_HEIGHT:
                case self::IMAGE_SECURE_URL:
                case self::IMAGE_TYPE:
                case self::IMAGE_WIDTH:
                    $this->handleImageAttribute($page->images[count($page->images) - 1], $name, $value);
                    break;
                case self::LOCALE:
                    if ($page->locale === null) {
                        $page->locale = $value;
                    }
                    break;
                case self::LOCALE_ALTERNATE:
                    $page->localeAlternate[] = $value;
                    break;
                case self::SITE_NAME:
                    if ($page->siteName === null) {
                        $page->siteName = $value;
                    }
                    break;
                case self::TITLE:
                    if ($page->title === null) {
                        $page->title = $value;
                    }
                    break;
                case self::URL:
                    if ($page->url === null) {
                        $page->url = $value;
                    }
                    break;
                case self::VIDEO:
                case self::VIDEO_URL:
                    $page->videos[] = new Video($value);
                    break;
                case self::VIDEO_HEIGHT:
                case self::VIDEO_SECURE_URL:
                case self::VIDEO_TYPE:
                case self::VIDEO_WIDTH:
                    $this->handleVideoAttribute($page->videos[count($page->videos) - 1], $name, $value);
            }
        }

        // Fallback for title
        if ($this->useFallbackMode && !$page->title) {
            $titleElement = $crawler->filter("title")->first();
            if ($titleElement) {
                $page->title = trim($titleElement->text());
            }
        }

        // Fallback for description
        if ($this->useFallbackMode && !$page->description) {
            $descriptionElement = $crawler->filter("meta[property='description']")->first();
            if ($descriptionElement) {
                $page->description = trim($descriptionElement->attr("content"));
            }
        }
    }

    private function handleImageAttribute(Image $element, $name, $value)
    {
        switch($name)
        {
            case self::IMAGE_HEIGHT:
                $element->height = (int)$value;
                break;
            case self::IMAGE_WIDTH:
                $element->width = (int)$value;
                break;
            case self::IMAGE_TYPE:
                $element->type = $value;
                break;
            case self::IMAGE_SECURE_URL:
                $element->secureUrl = $value;
                break;
        }
    }

    private function handleVideoAttribute(Video $element, $name, $value)
    {
        switch($name)
        {
            case self::VIDEO_HEIGHT:
                $element->height = (int)$value;
                break;
            case self::VIDEO_WIDTH:
                $element->width = (int)$value;
                break;
            case self::VIDEO_TYPE:
                $element->type = $value;
                break;
            case self::VIDEO_SECURE_URL:
                $element->secureUrl = $value;
                break;
        }
    }

    private function extractFallbackValue($crawler, $property)
    {

    }
}
