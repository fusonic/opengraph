<?php

namespace Fusonic\OpenGraph\Elements;

use Fusonic\OpenGraph\Property;

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
     * @var string
     */
    public $secureUrl;

    /**
     * The MIME type of an audio resource associated with the object.
     *
     * @var type
     */
    public $type;

    /**
     * @param   string      $url            URL to the audio file.
     */
    public function __construct($url)
    {
        parent::__construct();

        $this->url = $url;
    }

    /**
     * Gets all properties set on this element.
     *
     * @return  array|Property[]
     */
    public function getProperties()
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
