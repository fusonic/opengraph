<?php

namespace Fusonic\OpenGraph\Test;

use DateTime;
use Fusonic\OpenGraph\Publisher;
use Fusonic\OpenGraph\Test\TestData\TestPublishObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use UnexpectedValueException;

final class PublisherTest extends TestCase
{
    public function testGenerateHtmlNull(): void
    {
        // arrange
        $publisher = new Publisher();

        $object = new TestPublishObject(null);

        // act
        $result = $publisher->generateHtml($object);

        // assert
        self::assertSame('', $result);
    }

    #[DataProvider('generateHtmlValuesProvider')]
    public function testGenerateHtmlValues($value, $expectedContent)
    {
        // arrange
        $publisher = new Publisher();

        $object = new TestPublishObject($value);

        // act
        $result = $publisher->generateHtml($object);

        // assert
        self::assertSame(
            expected: \sprintf('<meta property="%s" content="%s">', TestPublishObject::KEY, $expectedContent),
            actual: $result
        );
    }

    public function testGenerateHtmlUnsupportedObject(): void
    {
        // assert
        $this->expectException(UnexpectedValueException::class);

        // arrange
        $publisher = new Publisher();

        $object = new TestPublishObject(new stdClass());

        // act
        $publisher->generateHtml($object);
    }

    public static function generateHtmlValuesProvider(): array
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
}
