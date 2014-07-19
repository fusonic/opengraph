<?php

namespace Fusonic\OpenGraph;

use Fusonic\Linq\Linq;

/**
 * Abstract base class for all Open Graph objects (website, video, ...)
 */
abstract class Object
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $determiner;

    /**
     * @var array|Elements\Image[]
     */
    public $images = [];

    /**
     * @var string
     */
    public $locale;

    /**
     * @var array|string[]
     */
    public $localeAlternate = [];

    /**
     * @var string
     */
    public $siteName;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $url;

    /**
     * @var array|Elements\Video[]
     */
    public $videos = [];

    public function __construct()
    {
    }

    /**
     * Returns the largest of all images found or NULL.
     *
     * @return  Elements\Image
     */
    public function getLargestImage()
    {
        return Linq::from($this->images)
            ->orderByDescending(
                function (Elements\Image $image) {
                    return $image->width * $image->height;
                }
            )
            ->firstOrNull();
    }

    /**
     * Returns the smallest image larger than $minWidth and $minHeight.
     *
     * @param   int     $minWidth       Minimum width of the image.
     * @param   int     $minHeight      Minimum height of the image.
     *
     * @return  Elements\Image
     */
    public function getImage($minWidth, $minHeight)
    {
        return Linq::from($this->images)
            ->where(
                function (Elements\Image $image) use ($minWidth, $minHeight) {
                    return $image->width >= $minWidth && $image->height >= $minHeight;
                }
            )
            ->orderBy(
                function (Elements\Image $image) {
                    return $image->width * $image->height;
                }
            )
            ->firstOrNull();
    }
}
