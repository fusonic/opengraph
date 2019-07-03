<?php

namespace Mpyw\OpenGraph\Elements;

use Mpyw\OpenGraph\GenericHelper;
use Mpyw\OpenGraph\Property;

/**
 * Abstract base class for all OpenGraph elements (e.g. images, videos etc.)
 */
abstract class ElementBase
{
    use GenericHelper;

    /**
     * Gets all properties set on this element.
     *
     * @return Property[]
     */
    abstract public function getProperties(): array;
}
