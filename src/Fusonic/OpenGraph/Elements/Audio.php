<?php

namespace Fusonic\OpenGraph\Elements;

use Fusonic\OpenGraph\Property;

/**
 * An Open Graph audio element.
 */
class Audio extends ElementBase
{
    /**
     * URL for the audio. May be SSL-secured or not.
     *
     * @var string
     */
    public $url;

    /**
     * SSL-secured URL of the audio or NULL.
     *
     * @var string
     */
    public $secureUrl;

    /**
     * Mime-type of the audio or NULL.
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
