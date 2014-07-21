<?php

namespace Fusonic\OpenGraph\Objects;

/**
 * An Open Graph website object.
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
