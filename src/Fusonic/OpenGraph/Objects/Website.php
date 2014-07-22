<?php

namespace Fusonic\OpenGraph\Objects;

/**
 * This object type represents a website. It is a simple object type and uses only common Open Graph properties. For
 * specific pages within a website, the article object type should be used.
 *
 * https://developers.facebook.com/docs/reference/opengraph/object-type/website/
 */
class Website extends ObjectBase
{
    const TYPE = "website";

    public function __construct()
    {
        parent::__construct();

        $this->type = self::TYPE;
    }
}
