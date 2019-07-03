<?php

namespace Mpyw\OpenGraph\Elements;

use Mpyw\OpenGraph\Property;

/**
 * An Open Graph audio element.
 */
class Audio extends ElementBase
{
    /**
     * The URL of an audio resource associated with the object.
     *
     * @var string
     */
    public $url;

    /**
     * An alternate URL to use if an audio resource requires HTTPS.
     *
     * @var null|string
     */
    public $secureUrl;

    /**
     * The MIME type of an audio resource associated with the object.
     *
     * @var null|string
     */
    public $type;

    /**
     * @param mixed $url URL to the audio file.
     */
    public function __construct($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setAttribute(string $name, $value)
    {
        switch ($name) {
            case Property::AUDIO_TYPE:
                $this->type = (string)$value;
                break;
            case Property::AUDIO_SECURE_URL:
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
            $properties[] = new Property(Property::AUDIO_URL, $this->url);
        }

        if ($this->secureUrl !== null) {
            $properties[] = new Property(Property::AUDIO_SECURE_URL, $this->secureUrl);
        }

        if ($this->type !== null) {
            $properties[] = new Property(Property::AUDIO_TYPE, $this->type);
        }

        return $properties;
    }
}
