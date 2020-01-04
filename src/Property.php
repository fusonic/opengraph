<?php

namespace Mpyw\OpenGraph;

/**
 * Class holding data for a single Open Graph property on a web page.
 */
class Property
{
    public const AUDIO = 'og:audio';
    public const AUDIO_SECURE_URL = 'og:audio:secure_url';
    public const AUDIO_TYPE = 'og:audio:type';
    public const AUDIO_URL = 'og:audio:url';
    public const DESCRIPTION = 'og:description';
    public const DETERMINER = 'og:determiner';
    public const IMAGE = 'og:image';
    public const IMAGE_HEIGHT = 'og:image:height';
    public const IMAGE_SECURE_URL = 'og:image:secure_url';
    public const IMAGE_TYPE = 'og:image:type';
    public const IMAGE_URL = 'og:image:url';
    public const IMAGE_WIDTH = 'og:image:width';
    public const IMAGE_USER_GENERATED = 'og:image:user_generated';
    public const LOCALE = 'og:locale';
    public const LOCALE_ALTERNATE = 'og:locale:alternate';
    public const PRICE_AMOUNT = 'og:price:amount';
    public const PRICE_CURRENCY = 'og:price:currency';
    public const RICH_ATTACHMENT = 'og:rich_attachment';
    public const SEE_ALSO = 'og:see_also';
    public const SITE_NAME = 'og:site_name';
    public const TITLE = 'og:title';
    public const TYPE = 'og:type';
    public const UPDATED_TIME = 'og:updated_time';
    public const URL = 'og:url';
    public const VIDEO = 'og:video';
    public const VIDEO_HEIGHT = 'og:video:height';
    public const VIDEO_SECURE_URL = 'og:video:secure_url';
    public const VIDEO_TYPE = 'og:video:type';
    public const VIDEO_URL = 'og:video:url';
    public const VIDEO_WIDTH = 'og:video:width';

    /**
     * Key of the property without "og:" prefix.
     *
     * @var mixed
     */
    public $key;

    /**
     * Value of the property.
     *
     * @var mixed
     */
    public $value;

    /**
     * Property constructor.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
}
