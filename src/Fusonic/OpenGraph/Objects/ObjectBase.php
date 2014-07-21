<?php

namespace Fusonic\OpenGraph\Objects;

use Fusonic\Linq\Linq;
use Fusonic\OpenGraph\Elements\Audio;
use Fusonic\OpenGraph\Elements\Image;
use Fusonic\OpenGraph\Elements\Video;
use Fusonic\OpenGraph\Property;

/**
 * Abstract base class for all Open Graph objects (website, video, ...)
 */
abstract class ObjectBase
{
    /**
     * @var array|Audio[]
     */
    public $audios = [];

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $determiner;

    /**
     * @var array|Image[]
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
     * @var bool
     */
    public $richAttachment;

    /**
     * @var array|string[]
     */
    public $seeAlso = [];

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
    public $type;

    /**
     * @var \DateTime
     */
    public $updatedTime;

    /**
     * @var string
     */
    public $url;

    /**
     * @var array|Video[]
     */
    public $videos = [];

    public function __construct()
    {
    }

    /**
     * Assigns all properties given to the this Object instance.
     *
     * @param   array|Property[]    $properties     Array of all properties to assign.
     * @param   bool                $debug          Throw exceptions when parsing or not.
     *
     * @throws  \UnexpectedValueException
     */
    public function assignProperties(array $properties, $debug = false)
    {
        foreach ($properties as $property) {
            $name = $property->key;
            $value = $property->value;

            switch($name) {
                case Property::AUDIO:
                case Property::AUDIO_URL:
                    $this->audios[] = new Audio($value);
                    break;
                case Property::AUDIO_SECURE_URL:
                case Property::AUDIO_TYPE:
                    if (count($this->audios) > 0) {
                        $this->handleAudioAttribute($this->audios[count($this->audios) - 1], $name, $value);
                    } elseif ($debug) {
                        throw new \UnexpectedValueException(sprintf("Found '%s' property but no audio was found before.", $name));
                    }
                    break;
                case Property::DESCRIPTION:
                    if ($this->description === null) {
                        $this->description = $value;
                    }
                    break;
                case Property::DETERMINER:
                    if ($this->determiner === null) {
                        $this->determiner = $value;
                    }
                    break;
                case Property::IMAGE:
                case Property::IMAGE_URL:
                    $this->images[] = new Image($value);
                    break;
                case Property::IMAGE_HEIGHT:
                case Property::IMAGE_SECURE_URL:
                case Property::IMAGE_TYPE:
                case Property::IMAGE_WIDTH:
                    if (count($this->images) > 0) {
                        $this->handleImageAttribute($this->images[count($this->images) - 1], $name, $value);
                    } elseif ($debug) {
                        throw new \UnexpectedValueException(sprintf("Found '%s' property but no image was found before.", $name));
                    }
                    break;
                case Property::LOCALE:
                    if ($this->locale === null) {
                        $this->locale = $value;
                    }
                    break;
                case Property::LOCALE_ALTERNATE:
                    $this->localeAlternate[] = $value;
                    break;
                case Property::RICH_ATTACHMENT:
                    $this->richAttachment = $this->convertToBoolean($value);
                    break;
                case Property::SEE_ALSO:
                    $this->seeAlso[] = $value;
                    break;
                case Property::SITE_NAME:
                    if ($this->siteName === null) {
                        $this->siteName = $value;
                    }
                    break;
                case Property::TITLE:
                    if ($this->title === null) {
                        $this->title = $value;
                    }
                    break;
                case Property::UPDATED_TIME:
                    if ($this->updatedTime === null) {
                        $this->updatedTime = $this->convertToDateTime($value);
                    }
                    break;
                case Property::URL:
                    if ($this->url === null) {
                        $this->url = $value;
                    }
                    break;
                case Property::VIDEO:
                case Property::VIDEO_URL:
                    $this->videos[] = new Video($value);
                    break;
                case Property::VIDEO_HEIGHT:
                case Property::VIDEO_SECURE_URL:
                case Property::VIDEO_TYPE:
                case Property::VIDEO_WIDTH:
                    if (count($this->videos) > 0) {
                        $this->handleVideoAttribute($this->videos[count($this->videos) - 1], $name, $value);
                    } elseif ($debug) {
                        throw new \UnexpectedValueException(sprintf("Found '%s' property but no video was found before.", $name));
                    }
            }
        }
    }

    private function handleImageAttribute(Image $element, $name, $value)
    {
        switch($name)
        {
            case Property::IMAGE_HEIGHT:
                $element->height = (int)$value;
                break;
            case Property::IMAGE_WIDTH:
                $element->width = (int)$value;
                break;
            case Property::IMAGE_TYPE:
                $element->type = $value;
                break;
            case Property::IMAGE_SECURE_URL:
                $element->secureUrl = $value;
                break;
        }
    }

    private function handleVideoAttribute(Video $element, $name, $value)
    {
        switch($name)
        {
            case Property::VIDEO_HEIGHT:
                $element->height = (int)$value;
                break;
            case Property::VIDEO_WIDTH:
                $element->width = (int)$value;
                break;
            case Property::VIDEO_TYPE:
                $element->type = $value;
                break;
            case Property::VIDEO_SECURE_URL:
                $element->secureUrl = $value;
                break;
        }
    }

    private function handleAudioAttribute(Audio $element, $name, $value)
    {
        switch($name)
        {
            case Property::AUDIO_TYPE:
                $element->type = $value;
                break;
            case Property::AUDIO_SECURE_URL:
                $element->secureUrl = $value;
                break;
        }
    }

    protected function convertToDateTime($value)
    {
        return new \DateTime($value);
    }

    protected function convertToBoolean($value)
    {
        switch(strtolower($value))
        {
            case "1":
            case "true":
                return true;
            default:
                return false;
        }
    }

    /**
     * Returns the largest of all images found or NULL.
     *
     * @return  Image
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
     * @return  Image
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

    /**
     * Gets all properties set on this object.
     *
     * @return  array|Property[]
     */
    public function getProperties()
    {
        $properties = [];

        foreach ($this->audios as $audio) {
            $properties = array_merge($properties, $audio->getProperties());
        }

        if ($this->description !== null) {
            $properties[] = new Property(Property::DESCRIPTION, $this->description);
        }

        if ($this->determiner !== null) {
            $properties[] = new Property(Property::DETERMINER, $this->determiner);
        }

        foreach ($this->images as $image) {
            $properties = array_merge($properties, $image->getProperties());
        }

        if ($this->locale !== null) {
            $properties[] = new Property(Property::LOCALE, $this->locale);
        }

        foreach ($this->localeAlternate as $locale) {
            $properties[] = new Property(Property::LOCALE_ALTERNATE, $locale);
        }

        if ($this->richAttachment !== null) {
            $properties[] = new Property(Property::RICH_ATTACHMENT, (int)$this->richAttachment);
        }

        foreach ($this->seeAlso as $seeAlso) {
            $properties[] = new Property(Property::SEE_ALSO, $seeAlso);
        }

        if ($this->siteName !== null) {
            $properties[] = new Property(Property::SITE_NAME, $this->siteName);
        }

        if ($this->type !== null) {
            $properties[] = new Property(Property::TYPE, $this->type);
        }

        if ($this->updatedTime !== null) {
            $properties[] = new Property(Property::UPDATED_TIME, $this->updatedTime->format("c"));
        }

        if ($this->url !== null) {
            $properties[] = new Property(Property::URL, $this->url);
        }

        foreach ($this->videos as $video) {
            $properties = array_merge($properties, $video->getProperties());
        }

        return $properties;
    }

    public function getHtml()
    {
        $html = "";

        foreach ($this->getProperties() as $property) {
            $html .= sprintf("<meta property=\"%s\" content=\"%s\">\n", $property->key, htmlspecialchars($property->value));
        }

        return $html;
    }
}
