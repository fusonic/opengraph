<?php

namespace Fusonic\OpenGraph\Test;

use DateTime;
use Fusonic\OpenGraph\Publisher;
use Fusonic\OpenGraph\Test\TestData\TestPublishObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use UnexpectedValueException;

class PublisherTest extends TestCase
{
    /**
     * @var Publisher
     */
    private $publisher;

    protected function setUp(): void
    {
        $this->publisher = new Publisher();

        parent::setUp();
    }

    public function testGenerateHtmlNull()
    {
        $object = new TestPublishObject(null);

        $result = $this->publisher->generateHtml($object);

        $this->assertEquals("", $result);
    }

    public function generateHtmlValuesProvider()
    {
        return [
            "Boolean true" =>           [ true,                                         "1" ],
            "Boolean false" =>          [ false,                                        "0" ],
            "Integer 1" =>              [ 1,                                            "1" ],
            "Integer -1" =>             [ -1,                                           "-1" ],
            "Float 1.11111" =>          [ 1.11111,                                      "1.11111" ],
            "Float -1.11111" =>         [ -1.11111,                                     "-1.11111" ],
            "DateTime" =>               [ new DateTime("2014-07-21T20:14:00+02:00"),   "2014-07-21T20:14:00+02:00" ],
            "String" =>                 [ "string",                                     "string" ],
            "String with quotes" =>     [ "some \" quotes",                             "some &quot; quotes" ],
            "String with ampersands" => [ "some & ampersand",                           "some &amp; ampersand" ],
        ];
    }

    /**
     * @dataProvider generateHtmlValuesProvider
     */
    public function testGenerateHtmlValues($value, $expectedContent)
    {
        $object = new TestPublishObject($value);

        $result = $this->publisher->generateHtml($object);

        $this->assertEquals('<meta property="' . TestPublishObject::KEY . '" content="' . $expectedContent . '">', $result);
    }

    public function testGenerateHtmlUnsupportedObject()
    {
        $this->expectException(UnexpectedValueException::class);

        $object = new TestPublishObject(new stdClass());

        $this->publisher->generateHtml($object);
    }
}
