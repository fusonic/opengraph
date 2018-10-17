<?php

namespace Mpyw\OpenGraph\Test\TestData;

use Mpyw\OpenGraph\Objects\ObjectBase;
use Mpyw\OpenGraph\Property;

class TestPublishObject extends ObjectBase
{
    const KEY = "og:title";

    private $value;

    public function __construct($value)
    {
        parent::__construct();

        $this->value = $value;
    }

    public function getProperties()
    {
        return [
            new Property(self::KEY, $this->value),
        ];
    }
}
