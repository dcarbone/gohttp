<?php declare(strict_types=1);

namespace DCarbone\Go\HTTP\Tests\URL;

use DCarbone\Go\HTTP\URL\Values;
use PHPUnit\Framework\TestCase;

final class ValuesTest extends TestCase
{
    public function testConstructorSeedsValuesAndAccessorsWork(): void
    {
        $values = new Values([
            'foo' => 'bar',
            'abc' => '',
        ]);

        self::assertSame('bar', $values->get('foo'));
        self::assertSame(['bar'], $values->getAll('foo'));
        self::assertSame('', $values->get('abc'));
        self::assertSame([''], $values->getAll('abc'));
        self::assertSame('', $values->get('missing'));
        self::assertSame([], $values->getAll('missing'));
    }

    public function testAddAndSetMaintainExpectedOrderAndBehavior(): void
    {
        $values = new Values();
        $values->add('foo', 'bar');
        $values->add('foo', 'baz');

        self::assertSame('bar', $values->get('foo'));
        self::assertSame(['bar', 'baz'], $values->getAll('foo'));

        $values->set('foo', 'qux');
        self::assertSame(['qux'], $values->getAll('foo'));
    }

    public function testDeleteToPsr7ArrayAndCount(): void
    {
        $values = new Values(['foo' => 'bar', 'abc' => 'xyz']);
        self::assertCount(2, $values);

        $values->delete('foo');

        self::assertCount(1, $values);
        self::assertSame(['abc' => ['xyz']], $values->toPsr7Array());
    }

    public function testArrayAccessReadWriteAndUnset(): void
    {
        $values = new Values();

        $values['foo'] = 'bar';
        self::assertTrue(isset($values['foo']));
        self::assertSame('bar', $values['foo']);

        unset($values['foo']);
        self::assertFalse(isset($values['foo']));
        self::assertSame('', $values['foo']);
    }

    public function testArrayAccessRejectsInvalidTypes(): void
    {
        $values = new Values();

        self::assertFalse(isset($values[123]));

        $this->expectException(\TypeError::class);
        $values[123] = 'bar';
    }

    public function testArrayAccessRejectsNonStringValues(): void
    {
        $values = new Values();

        $this->expectException(\TypeError::class);
        $values['foo'] = 1;
    }

    public function testIterationAndStringSerialization(): void
    {
        $values = new Values();
        $values->add('foo', 'bar');
        $values->add('foo', 'baz');
        $values->add('abc', '');

        $iterated = [];
        foreach ($values as $key => $current) {
            $iterated[$key] = $current;
        }

        self::assertSame(
            [
                'foo' => ['bar', 'baz'],
                'abc' => [''],
            ],
            $iterated
        );
        self::assertSame('foo=bar&foo=baz&abc', (string)$values);
    }

    public function testJsonSerializationReturnsInternalMapShape(): void
    {
        $values = new Values(['foo' => 'bar']);
        self::assertSame(['foo' => ['bar']], $values->jsonSerialize());
        self::assertSame('{"foo":["bar"]}', json_encode($values, JSON_THROW_ON_ERROR));
    }
}
