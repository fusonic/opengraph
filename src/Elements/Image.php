<?php

namespace Mpyw\OpenGraph\Elements;

use Mpyw\OpenGraph\Property;

/**
 * An Open Graph image element.
 */
class Image extends ElementBase
{
    /**
     * The URL of an image resource associated with the object.
     *
     * @var string
     */
    public $url;

    /**
     * An alternate URL to use if an image resource requires HTTPS.
     *
     * @var null|string
     */
    public $secureUrl;

    /**
     * The MIME type of an image resource.
     *
     * @var null|string
     */
    public $type;

    /**
     * The width of an image resource in pixels.
     *
     * @var null|int
     */
    public $width;

    /**
     * The height of an image resource in pixels.
     *
     * @var null|int
     */
    public $height;

    /**
     * Whether the image is user-generated or not.
     *
     * @var null|bool
     */
    public $userGenerated;

    /**
     * @param mixed $url URL to the image file.
     */
    public function __construct($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setAttribute(string $name, $value): void
    {
        switch ($name) {
            case Property::IMAGE_HEIGHT:
                $this->height = (int)$value;
                break;
            case Property::IMAGE_WIDTH:
                $this->width = (int)$value;
                break;
            case Property::IMAGE_TYPE:
                $this->type = (string)$value;
                break;
            case Property::IMAGE_SECURE_URL:
                $this->secureUrl = (string)$value;
                break;
            case Property::IMAGE_USER_GENERATED:
                $this->userGenerated = $this->convertToBoolean($value);
                break;
        }
    }

    /**
     * Gets all properties set on this element.
     *
     * @return Property[]
     */
    public function getProperties(): array
    {
        $properties = [];

        // URL must precede all other properties
        if ($this->url !== null) {
            $properties[] = new Property(Property::IMAGE_URL, $this->url);
        }

        if ($this->height !== null) {
            $properties[] = new Property(Property::IMAGE_HEIGHT, $this->height);
        }

        if ($this->secureUrl !== null) {
            $properties[] = new Property(Property::IMAGE_SECURE_URL, $this->secureUrl);
        }

        if ($this->type !== null) {
            $properties[] = new Property(Property::IMAGE_TYPE, $this->type);
        }

        if ($this->width !== null) {
            $properties[] = new Property(Property::IMAGE_WIDTH, $this->width);
        }

        if ($this->userGenerated !== null) {
            $properties[] = new Property(Property::IMAGE_USER_GENERATED, $this->userGenerated);
        }

        return $properties;
    }
}
