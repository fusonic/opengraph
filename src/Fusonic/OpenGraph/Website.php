<?php

namespace Fusonic\OpenGraph;

/**
 * An Open Graph website object (http://graph.facebook.com/schema/og/website)
 */
class Website extends Object
{
    const TYPE = "website";

    public function __construct()
    {
        parent::__construct();

        $this->type = self::TYPE;
    }
}
