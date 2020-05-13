<?php

namespace Fusonic\OpenGraph;

/**
 * Class holding data for a single Open Graph property on a web page.
 */
class Property
{
    const AUDIO = "og:audio";
    const AUDIO_SECURE_URL = "og:audio:secure_url";
    const AUDIO_TYPE = "og:audio:type";
    const AUDIO_URL = "og:audio:url";
    const DESCRIPTION = "og:description";
    const DETERMINER = "og:determiner";
    const IMAGE = "og:image";
    const IMAGE_HEIGHT = "og:image:height";
    const IMAGE_SECURE_URL = "og:image:secure_url";
    const IMAGE_TYPE = "og:image:type";
    const IMAGE_URL = "og:image:url";
    const IMAGE_WIDTH = "og:image:width";
    const IMAGE_USER_GENERATED = "og:image:user_generated";
    const LOCALE = "og:locale";
    const LOCALE_ALTERNATE = "og:locale:alternate";
    const RICH_ATTACHMENT = "og:rich_attachment";
    const SEE_ALSO = "og:see_also";
    const SITE_NAME = "og:site_name";
    const TITLE = "og:title";
    const TYPE = "og:type";
    const UPDATED_TIME = "og:updated_time";
    const URL = "og:url";
    const VIDEO = "og:video";
    const VIDEO_HEIGHT = "og:video:height";
    const VIDEO_SECURE_URL = "og:video:secure_url";
    const VIDEO_TYPE = "og:video:type";
    const VIDEO_URL = "og:video:url";
    const VIDEO_WIDTH = "og:video:width";

    /**
     * Key of the property without "og:" prefix.
     */
    public string $key;

    /**
     * Value of the property.
     */
    public $value;

    public function __construct(string $key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
