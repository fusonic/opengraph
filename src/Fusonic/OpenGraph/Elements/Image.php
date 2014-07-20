<?php

namespace Fusonic\OpenGraph\Elements;

use Fusonic\OpenGraph\Property;

/**
 * An Open Graph image element.
 */
class Image extends Element
{
    /**
     * URL for the image. May be SSL-secured or not.
     *
     * @var string
     */
    public $url;

    /**
     * SSL-secured URL of the image or NULL.
     *
     * @var string
     */
    public $secureUrl;

    /**
     * Mime-type of the image or NULL.
     *
     * @var type
     */
    public $type;

    /**
     * Width of the image or NULL.
     *
     * @var int
     */
    public $width;

    /**
     * Height of the image or NULL.
     *
     * @var int
     */
    public $height;

    /**
     * @param   string      $url            URL to the image file.
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

        return $properties;
    }
}
