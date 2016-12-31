<?php

namespace Fusonic\OpenGraph\Elements;

use Fusonic\OpenGraph\Property;

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
     * @var string
     */
    public $secureUrl;

    /**
     * The MIME type of an image resource.
     *
     * @var type
     */
    public $type;

    /**
     * The width of an image resource in pixels.
     *
     * @var int
     */
    public $width;

    /**
     * The height of an image resource in pixels.
     *
     * @var int
     */
    public $height;

    /**
     * Whether the image is user-generated or not.
     *
     * @var bool
     */
    public $userGenerated;

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

        if ($this->userGenerated !== null) {
            $properties[] = new Property(Property::IMAGE_USER_GENERATED, $this->userGenerated);
        }

        return $properties;
    }
}
