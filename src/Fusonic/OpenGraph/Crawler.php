<?php

namespace Fusonic\OpenGraph;

use GuzzleHttp\Adapter\AdapterInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * Crawler that extracts Open Graph data from either a URL or a HTML string.
 */
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

    private $client;

    /**
     * When enabled, crawler will read content of title and meta description if no
     * Open Graph data is provided by target page.
     *
     * @var bool
     */
    public $useFallbackMode = false;

    /**
     * When enabled, crawler will throw exceptions for some crawling errors like unexpected
     * Open Graph elements.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * @param   AdapterInterface    $adapter        Guzzle adapter to use for making HTTP requests.
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->client = new Client(
            [
                "adapter" => $adapter,
            ]
        );
    }

    /**
     * Fetches HTML content from the given URL and then crawls it for Open Graph data.
     *
     * @param   string  $url            URL to be crawled.
     *
     * @return  Website
     */
    public function crawlUrl($url)
    {
        // Fetch HTTP content using Guzzle
        $response = $this->client->get($url);

        return $this->crawlHtml($response->getBody()->__toString(), $url);
    }

    /**
     * Crawls the given HTML string for OpenGraph data.
     *
     * @param   string  $html           HTML string, usually whole content of crawled web resource.
     * @param   string  $fallbackUrl    URL to use when fallback mode is enabled.
     *
     * @return  Object
     */
    public function crawlHtml($html, $fallbackUrl = null)
    {
        // Extract all data that can be found
        $page = $this->extractOpenGraphData($html);

        // Use the user's URL as fallback
        if ($this->useFallbackMode && $page->url === null) {
            $page->url = $fallbackUrl;
        }

        // Return result
        return $page;
    }

    private function extractOpenGraphData($content)
    {
        $crawler = new SymfonyCrawler($content);
        $page = new Website();

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
                    $page->images[] = new Elements\Image($value);
                    break;
                case self::IMAGE_HEIGHT:
                case self::IMAGE_SECURE_URL:
                case self::IMAGE_TYPE:
                case self::IMAGE_WIDTH:
                    if (count($page->images) > 0) {
                        $this->handleImageAttribute($page->images[count($page->images) - 1], $name, $value);
                    } elseif ($this->debug) {
                        throw new \UnexpectedValueException(printf("Found %0 property but no image was found before.", $name));
                    }
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
                    $page->videos[] = new Elements\Video($value);
                    break;
                case self::VIDEO_HEIGHT:
                case self::VIDEO_SECURE_URL:
                case self::VIDEO_TYPE:
                case self::VIDEO_WIDTH:
                    if (count($page->videos) > 0) {
                        $this->handleVideoAttribute($page->videos[count($page->ideos) - 1], $name, $value);
                    } elseif ($this->debug) {
                        throw new \UnexpectedValueException(printf("Found %0 property but no video was found before.", $name));
                    }
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

        return $page;
    }

    private function handleImageAttribute(Elements\Image $element, $name, $value)
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

    private function handleVideoAttribute(Elements\Video $element, $name, $value)
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
}
