<?php

namespace Mpyw\OpenGraph\Test\TestData;

use Mpyw\OpenGraph\Objects\ObjectBase;
use Mpyw\OpenGraph\Property;

/**
 * Class TestPublishObject
 */
class TestPublishObject extends ObjectBase
{
    public const KEY = 'og:title';

    /**
     * @var string
     */
    protected $value;

    /**
     * TestPublishObject constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return [
            new Property(self::KEY, $this->value),
        ];
    }
}
