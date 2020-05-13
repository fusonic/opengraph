<?php

namespace Fusonic\OpenGraph;

use DOMElement;
use Fusonic\Linq\Linq;
use Fusonic\OpenGraph\Objects\ObjectBase;
use Fusonic\OpenGraph\Objects\Website;
use LogicException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Consumer that extracts Open Graph data from either a URL or a HTML string.
 */
class Consumer
{
    private ?ClientInterface $client;
    private ?RequestFactoryInterface $requestFactory;

    /**
     * When enabled, crawler will read content of title and meta description if no
     * Open Graph data is provided by target page.
     */
    public bool $useFallbackMode = false;

    /**
     * When enabled, crawler will throw exceptions for some crawling errors like unexpected
     * Open Graph elements.
     */
    public bool $debug = false;

    /**
     * @param ClientInterface|null         $client         A PSR-18 ClientInterface implementation.
     * @param RequestFactoryInterface|null $requestFactory A PSR-17 RequestFactoryInterface implementation.
     */
    public function __construct(?ClientInterface $client = null, ?RequestFactoryInterface $requestFactory = null)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * Fetches HTML content from the given URL and then crawls it for Open Graph data.
     *
     * @param string $url URL to be crawled.
     *
     * @return ObjectBase
     *
     * @throws ClientExceptionInterface
     */
    public function loadUrl(string $url): ObjectBase
    {
        if ($this->client === null) {
            throw new LogicException(
                "To use loadUrl() you must provide \$client and \$requestFactory when instantiating the consumer."
            );
        }

        $request = $this->requestFactory->createRequest("GET", $url);
        $response = $this->client->sendRequest($request);

        return $this->loadHtml($response->getBody()->getContents(), $url);
    }

    /**
     * Crawls the given HTML string for OpenGraph data.
     *
     * @param string $html        HTML string, usually whole content of crawled web resource.
     * @param string $fallbackUrl URL to use when fallback mode is enabled.
     *
     * @return  ObjectBase
     */
    public function loadHtml(string $html, string $fallbackUrl = null): ObjectBase
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

    private function extractOpenGraphData(string $content): ObjectBase
    {
        $crawler = new Crawler;
        $crawler->addHTMLContent($content, 'UTF-8');

        $properties = [];
        foreach(['name', 'property'] as $t)
        {
            // Get all meta-tags starting with "og:"
            $ogMetaTags = $crawler->filter("meta[{$t}^='og:']");
            // Create clean property array
            $props = Linq::from($ogMetaTags)
                ->select(
                    function (DOMElement $tag) use ($t) {
                        $name = strtolower(trim($tag->getAttribute($t)));
                        $value = trim($tag->getAttribute("content"));
                        return new Property($name, $value);
                    }
                )
                ->toArray();
            $properties = array_merge($properties, $props);
          
        }
            
        // Create new object of the correct type
        $typeProperty = Linq::from($properties)
            ->firstOrNull(
                function (Property $property) {
                    return $property->key === Property::TYPE;
                }
            );
        switch ($typeProperty !== null ? $typeProperty->value : null) {
            default:
                $object = new Website();
                break;
        }

        // Assign all properties to the object
        $object->assignProperties($properties, $this->debug);

        // Fallback for url
        if ($this->useFallbackMode && !$object->url) {
            $urlElement = $crawler->filter("link[rel='canonical']")->first();
            if ($urlElement->count() > 0) {
                $object->url = trim($urlElement->attr("href"));
            }
        }

        // Fallback for title
        if ($this->useFallbackMode && !$object->title) {
            $titleElement = $crawler->filter("title")->first();
            if ($titleElement->count() > 0) {
                $object->title = trim($titleElement->text());
            }
        }

        // Fallback for description
        if ($this->useFallbackMode && !$object->description) {
            $descriptionElement = $crawler->filter("meta[property='description']")->first();
            if ($descriptionElement->count() > 0) {
                $object->description = trim($descriptionElement->attr("content"));
            }
        }

        return $object;
    }
}
