<?php

namespace Fusonic\OpenGraph\Elements;

/**
 * An OpenGraph video element.
 */
class Video extends Element
{
    /**
     * URL for the video. May be SSL-secured or not.
     *
     * @var string
     */
    public $url;

    /**
     * SSL-secured URL of the video or NULL.
     *
     * @var string
     */
    public $secureUrl;

    /**
     * Mime-type of the video or NULL.
     *
     * @var type
     */
    public $type;

    /**
     * Width of the video or NULL.
     *
     * @var int
     */
    public $width;

    /**
     * Height of the video or NULL.
     *
     * @var int
     */
    public $height;

    /**
     * @param   string      $url            URL to the video.
     */
    public function __construct($url)
    {
        parent::__construct();

        $this->url = $url;
    }
}
