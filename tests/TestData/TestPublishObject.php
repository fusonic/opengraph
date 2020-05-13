<?php

namespace Fusonic\OpenGraph\Test\TestData;

use Fusonic\OpenGraph\Objects\ObjectBase;
use Fusonic\OpenGraph\Property;

class TestPublishObject extends ObjectBase
{
    const KEY = "og:title";

    private $value;

    public function __construct($value)
    {
        parent::__construct();

        $this->value = $value;
    }

    public function getProperties(): array
    {
        return [
            new Property(self::KEY, $this->value),
        ];
    }
}
