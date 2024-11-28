<?php

namespace Fusonic\OpenGraph\Test\TestData;

use Fusonic\OpenGraph\Objects\ObjectBase;
use Fusonic\OpenGraph\Property;

final class TestPublishObject extends ObjectBase
{
    const KEY = "og:title";

    public function __construct(
        private readonly mixed $value
    ) {
        parent::__construct();
    }

    public function getProperties(): array
    {
        return [
            new Property(self::KEY, $this->value),
        ];
    }
}
