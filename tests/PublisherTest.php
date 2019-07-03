<?php

namespace Mpyw\OpenGraph\Test;

use DateTimeImmutable;
use Mpyw\OpenGraph\Exceptions\UnexpectedValueException;
use Mpyw\OpenGraph\Publisher;
use Mpyw\OpenGraph\Test\TestData\TestPublishObject;
use PHPUnit\Framework\TestCase;

class PublisherTest extends TestCase
{
    /**
     * @var Publisher
     */
    protected $publisher;

    protected function setUp(): void
    {
        $this->publisher = new Publisher();

        parent::setUp();
    }

    public function testGenerateHtmlNull(): void
    {
        $object = new TestPublishObject(null);

        $result = $this->publisher->generateHtml($object);

        $this->assertEquals('', $result);
    }

    /**
     * @throws \Exception
     * @return array
     */
    public function generateHtmlValuesProvider(): array
    {
        return [
            [true,                                         '1'],
            [false,                                        '0'],
            [1,                                            '1'],
            [-1,                                           '-1'],
            [1.11111,                                      '1.11111'],
            [-1.11111,                                     '-1.11111'],
            [new DateTimeImmutable('2014-07-21T20:14:00+02:00'),   '2014-07-21T20:14:00+02:00'],
            ['string',                                     'string'],
            ['some " quotes',                             'some &quot; quotes'],
            ['some & ampersand',                           'some &amp; ampersand'],
        ];
    }

    /**
     * @dataProvider generateHtmlValuesProvider
     * @param mixed $value
     * @param mixed $expectedContent
     */
    public function testGenerateHtmlValues($value, $expectedContent): void
    {
        $object = new TestPublishObject($value);

        $result = $this->publisher->generateHtml($object);

        $this->assertEquals('<meta property="' . TestPublishObject::KEY . '" content="' . $expectedContent . '">', $result);
    }

    public function testGenerateHtmlUnsupportedObject(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $object = new TestPublishObject(new \stdClass());

        $this->publisher->generateHtml($object);
    }
}
