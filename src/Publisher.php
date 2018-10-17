<?php

namespace Mpyw\OpenGraph;

use Mpyw\OpenGraph\Objects\ObjectBase;

/**
 * Class for generating Open Graph tags from objects.
 */
class Publisher
{
    const DOCTYPE_HTML5 = 1;
    const DOCTYPE_XHTML = 2;

    /**
     * Defines the style in which HTML tags should be written. Use one of Publisher::DOCTYPE_HTML5 or
     * Publisher::DOCTYPE_XHTML.
     *
     * @var int
     */
    public $doctype = self::DOCTYPE_HTML5;

    public function __construct()
    {
    }

    public function generateHtml(ObjectBase $object)
    {
        $html = "";
        $format = "<meta property=\"%s\" content=\"%s\"" . ($this->doctype == self::DOCTYPE_XHTML ? " />" : ">");

        foreach ($object->getProperties() as $property) {
            if ($html !== "") {
                $html .= "\n";
            }

            if ($property->value === null) {
                continue;
            } elseif ($property->value instanceof \DateTime) {
                $value = $property->value->format("c");
            } elseif (is_object($property->value)) {
                throw new \UnexpectedValueException(
                    sprintf(
                        "Cannot handle value of type '%0' for property '%1'.",
                        get_class($property->value),
                        $property->key
                    )
                );
            } elseif ($property->value === true) {
                $value = "1";
            } elseif ($property->value === false) {
                $value = "0";
            } else {
                $value = (string)$property->value;
            }

            $html .= sprintf($format, $property->key, htmlspecialchars($value));
        }

        return $html;
    }
}
