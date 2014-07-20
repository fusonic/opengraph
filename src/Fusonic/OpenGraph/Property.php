<?php

namespace Fusonic\OpenGraph;

/**
 * Class holding data for a single Open Graph property on a web page.
 */
class Property
{
    const AUDIO = "audio";
    const AUDIO_SECURE_URL = "audio:secure_url";
    const AUDIO_TYPE = "audio:type";
    const AUDIO_URL = "audio:url";
    const DESCRIPTION = "description";
    const DETERMINER = "determiner";
    const IMAGE = "image";
    const IMAGE_HEIGHT = "image:height";
    const IMAGE_SECURE_URL = "image:secure_url";
    const IMAGE_TYPE = "image:type";
    const IMAGE_URL = "image:url";
    const IMAGE_WIDTH = "image:width";
    const LOCALE = "locale";
    const LOCALE_ALTERNATE = "locale:alternate";
    const SEE_ALSO = "see_also";
    const SITE_NAME = "site_name";
    const TITLE = "title";
    const TYPE = "type";
    const URL = "url";
    const VIDEO = "video";
    const VIDEO_HEIGHT = "video:height";
    const VIDEO_SECURE_URL = "video:secure_url";
    const VIDEO_TYPE = "video:type";
    const VIDEO_URL = "video:url";
    const VIDEO_WIDTH = "video:width";

    /**
     * Key of the property without "og:" prefix.
     *
     * @var string
     */
    public $key;

    /**
     * Value of the property.
     *
     * @var string
     */
    public $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
