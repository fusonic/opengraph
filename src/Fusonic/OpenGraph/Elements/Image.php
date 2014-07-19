<?php

namespace Fusonic\OpenGraph\Elements;

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
}
