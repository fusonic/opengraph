<?php

namespace Mpyw\OpenGraph\Elements;

use Mpyw\OpenGraph\Property;

/**
 * An OpenGraph video element.
 */
class Video extends ElementBase
{
    /**
     * The URL of a video resource associated with the object.
     *
     * @var string
     */
    public $url;

    /**
     * An alternate URL to use if a video resource requires HTTPS.
     *
     * @var null|string
     */
    public $secureUrl;

    /**
     * The MIME type of a video resource associated with the object.
     *
     * @var null|string
     */
    public $type;

    /**
     * The width of a video resource associated with the object in pixels.
     *
     * @var null|int
     */
    public $width;

    /**
     * The height of a video resource associated with the object in pixels.
     *
     * @var null|int
     */
    public $height;

    /**
     * @param mixed $url URL to the video.
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
            case Property::VIDEO_HEIGHT:
                $this->height = (int)$value;
                break;
            case Property::VIDEO_WIDTH:
                $this->width = (int)$value;
                break;
            case Property::VIDEO_TYPE:
                $this->type = (string)$value;
                break;
            case Property::VIDEO_SECURE_URL:
                $this->secureUrl = (string)$value;
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
            $properties[] = new Property(Property::VIDEO_URL, $this->url);
        }

        if ($this->height !== null) {
            $properties[] = new Property(Property::VIDEO_HEIGHT, $this->height);
        }

        if ($this->secureUrl !== null) {
            $properties[] = new Property(Property::VIDEO_SECURE_URL, $this->secureUrl);
        }

        if ($this->type !== null) {
            $properties[] = new Property(Property::VIDEO_TYPE, $this->type);
        }

        if ($this->width !== null) {
            $properties[] = new Property(Property::VIDEO_WIDTH, $this->width);
        }

        return $properties;
    }
}
