<?php

namespace Fusonic\OpenGraph;

use Fusonic\Linq\Linq;
use GuzzleHttp\Adapter\AdapterInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\functionsTest;
use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;

/**
 * Crawler that extracts Open Graph data from either a URL or a HTML string.
 */
class Crawler
{
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

        // Get all meta-tags starting with "og:"
        $ogMetaTags = $crawler->filter("meta[property^='og:']");

        // Create clean property array
        $properties = Linq::from($ogMetaTags)
            ->select(
                function (\DOMElement $tag) {
                    $name = strtolower(trim(substr($tag->getAttribute("property"), 3)));
                    $value = trim($tag->getAttribute("content"));
                    return new Property($name, $value);
                }
            )
            ->toArray();

        // Create new object of the correct type
        $typeProperty = Linq::from($properties)
            ->firstOrNull(
                function (Property $property) {
                    return $property->key === Property::TYPE;
                }
            );
        switch ($typeProperty === null ? "website" : $typeProperty->value) {
            default:
                $object = new Website();
                break;
        }

        // Assign all properties to the object
        $object->assignProperties($properties, $this->debug);

        // Fallback for title
        if ($this->useFallbackMode && !$object->title) {
            $titleElement = $crawler->filter("title")->first();
            if ($titleElement) {
                $object->title = trim($titleElement->text());
            }
        }

        // Fallback for description
        if ($this->useFallbackMode && !$object->description) {
            $descriptionElement = $crawler->filter("meta[property='description']")->first();
            if ($descriptionElement) {
                $object->description = trim($descriptionElement->attr("content"));
            }
        }

        return $object;
    }
}
