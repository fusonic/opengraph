<?php

namespace Fusonic\OpenGraph\Elements;

/**
 * Abstract base class for all OpenGraph elements (e.g. images, videos etc.)
 */
abstract class Element
{
    protected function __construct()
    {
    }

    /**
     * Gets all properties set on this element.
     *
     * @return  array|Property[]
     */
    abstract public function getProperties();
}
