<?php

namespace Mpyw\OpenGraph\Objects;

use DateTimeInterface;
use Mpyw\OpenGraph\Elements\Audio;
use Mpyw\OpenGraph\Elements\Image;
use Mpyw\OpenGraph\Elements\Video;
use Mpyw\OpenGraph\Exceptions\UnexpectedValueException;
use Mpyw\OpenGraph\GenericHelper;
use Mpyw\OpenGraph\Property;

/**
 * Abstract base class for all Open Graph objects (website, video, ...)
 */
abstract class ObjectBase
{
    use GenericHelper;

    /**
     * An array of audio resources attached to the object.
     *
     * @var Audio[]
     */
    public $audios = [];

    /**
     * A short description of the object.
     *
     * @var null|string
     */
    public $description;

    /**
     * The word that appears before the object's title in a sentence. This is an list of words from 'a', 'an', 'the',
     * ' "" ', or 'auto'. If 'auto' is chosen, the consumer of the object will chose between 'a' or 'an'. The default is
     * the blank, "".
     *
     * @var null|string
     */
    public $determiner;

    /**
     * An array of images attached to the object.
     *
     * @var Image[]
     */
    public $images = [];

    /**
     * The locale that the object's tags are marked up in, in the format language_TERRITORY.
     *
     * @var null|string
     */
    public $locale;

    /**
     * An array of alternate locales in which the resource is available.
     *
     * @var string[]
     */
    public $localeAlternate = [];

    /**
     * The price amount specified.
     *
     * @var null|string
     */
    public $priceAmount;

    /**
     * The currency code specified.
     *
     * @var null|string
     */
    public $priceCurrency;
    
    /**
     * @var null|bool
     */
    public $richAttachment;

    /**
     * An array of URLs of related resources.
     *
     * @var string[]
     */
    public $seeAlso = [];

    /**
     * The name of the web site upon which the object resides.
     *
     * @var null|string
     */
    public $siteName;

    /**
     * The title of the object as it should appear in the graph.
     *
     * @var null|string
     */
    public $title;

    /**
     * The type of the object, such as 'article'.
     *
     * @var null|string
     */
    public $type;

    /**
     * The time when the object was last updated.
     *
     * @var null|DateTimeInterface
     */
    public $updatedTime;

    /**
     * The canonical URL of the object, used as its ID in the graph.
     *
     * @var null|string
     */
    public $url;

    /**
     * An array of videos attached to the object.
     *
     * @var Video[]
     */
    public $videos = [];

    /**
     * Assigns all properties given to the this Object instance.
     *
     * @param  Property[]               $properties Array of all properties to assign.
     * @param  bool                     $debug      Throw exceptions when parsing or not.
     * @throws UnexpectedValueException
     * @return $this
     */
    public function assignProperties(array $properties, bool $debug = false)
    {
        foreach ($properties as $property) {
            $name = $property->key;
            $value = $property->value;

            switch ($name) {
                case Property::AUDIO:
                case Property::AUDIO_URL:
                    $this->audios[] = new Audio($value);
                    break;
                case Property::AUDIO_SECURE_URL:
                case Property::AUDIO_TYPE:
                    if (count($this->audios) > 0) {
                        $this->audios[count($this->audios) - 1]->setAttribute($name, $value);
                    } elseif ($debug) {
                        throw new UnexpectedValueException(
                            sprintf(
                                "Found '%s' property but no audio was found before.",
                                $name
                            )
                        );
                    }
                    break;
                case Property::DESCRIPTION:
                    if ($this->description === null) {
                        $this->description = (string)$value;
                    }
                    break;
                case Property::DETERMINER:
                    if ($this->determiner === null) {
                        $this->determiner = (string)$value;
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
                case Property::IMAGE_USER_GENERATED:
                    if (count($this->images) > 0) {
                        $this->images[count($this->images) - 1]->setAttribute($name, $value);
                    } elseif ($debug) {
                        throw new UnexpectedValueException(
                            sprintf(
                                "Found '%s' property but no image was found before.",
                                $name
                            )
                        );
                    }
                    break;
                case Property::LOCALE:
                    if ($this->locale === null) {
                        $this->locale = (string)$value;
                    }
                    break;
                case Property::LOCALE_ALTERNATE:
                    $this->localeAlternate[] = (string)$value;
                    break;
                case Property::PRICE_AMOUNT:
                    if ($this->priceAmount === null) {
                        $this->priceAmount = (string)$value;
                    }
                    break;
                case Property::PRICE_CURRENCY:
                    if ($this->priceCurrency === null) {
                        $this->priceCurrency = (string)$value;
                    }
                    break;
                case Property::RICH_ATTACHMENT:
                    $this->richAttachment = $this->convertToBoolean($value);
                    break;
                case Property::SEE_ALSO:
                    $this->seeAlso[] = (string)$value;
                    break;
                case Property::SITE_NAME:
                    if ($this->siteName === null) {
                        $this->siteName = (string)$value;
                    }
                    break;
                case Property::TITLE:
                    if ($this->title === null) {
                        $this->title = (string)$value;
                    }
                    break;
                case Property::UPDATED_TIME:
                    if ($this->updatedTime === null) {
                        $this->updatedTime = $this->convertToDateTime($value);
                    }
                    break;
                case Property::URL:
                    if ($this->url === null) {
                        $this->url = (string)$value;
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
                        $this->videos[count($this->videos) - 1]->setAttribute($name, $value);
                    } elseif ($debug) {
                        throw new UnexpectedValueException(
                            sprintf(
                                "Found '%s' property but no video was found before.",
                                $name
                            )
                        );
                    }
                    break;
            }
        }

        return $this;
    }

    /**
     * Gets all properties set on this object.
     *
     * @return Property[]
     */
    public function getProperties(): array
    {
        $properties = [];

        foreach ($this->audios as $audio) {
            $properties = array_merge($properties, $audio->getProperties());
        }

        if ($this->title !== null) {
            $properties[] = new Property(Property::TITLE, $this->title);
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
        
        if ($this->priceAmount !== null) {
            $properties[] = new Property(Property::PRICE_AMOUNT, $this->priceAmount);
        }

        if ($this->priceCurrency !== null) {
            $properties[] = new Property(Property::PRICE_CURRENCY, $this->priceCurrency);
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
            $properties[] = new Property(Property::UPDATED_TIME, $this->updatedTime->format('c'));
        }

        if ($this->url !== null) {
            $properties[] = new Property(Property::URL, $this->url);
        }

        foreach ($this->videos as $video) {
            $properties = array_merge($properties, $video->getProperties());
        }

        return $properties;
    }
}
