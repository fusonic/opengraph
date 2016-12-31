<?php

namespace Fusonic\OpenGraph\Test;

use Fusonic\OpenGraph\Publisher;
use Fusonic\OpenGraph\Test\TestData\TestPublishObject;

class PublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Publisher
     */
    private $publisher;

    protected function setUp()
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

    public function testGenerateHtmlValuesProvider()
    {
        return [
            [ true,                                         "1" ],
            [ false,                                        "0" ],
            [ 1,                                            "1" ],
            [ -1,                                           "-1" ],
            [ 1.11111,                                      "1.11111" ],
            [ -1.11111,                                     "-1.11111" ],
            [ new \DateTime("2014-07-21T20:14:00+02:00"),   "2014-07-21T20:14:00+02:00" ],
            [ "string",                                     "string" ],
            [ "some \" quotes",                             "some &quot; quotes" ],
            [ "some & ampersand",                           "some &amp; ampersand" ],
        ];
    }

    /**
     * @dataProvider testGenerateHtmlValuesProvider
     */
    public function testGenerateHtmlValues($value, $expectedContent)
    {
        $object = new TestPublishObject($value);

        $result = $this->publisher->generateHtml($object);

        $this->assertEquals('<meta property="' . TestPublishObject::KEY . '" content="' . $expectedContent . '">', $result);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGenerateHtmlUnsupportedObject()
    {
        $object = new TestPublishObject(new \stdClass());

        $this->publisher->generateHtml($object);
    }
}
