<?php declare(strict_types=1);

namespace DCarbone\Go\HTTP\Tests;

use DCarbone\Go\HTTP\HTTP;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function DCarbone\Go\HTTP\StatusName;
use function DCarbone\Go\HTTP\StatusText;

final class HttpTest extends TestCase
{
    public static function statusProvider(): array
    {
        return [
            [HTTP::StatusOK, 'OK', 'OK'],
            [HTTP::StatusNotFound, 'Not Found', 'NotFound'],
            [HTTP::StatusTeapot, "I'm a teapot", 'Teapot'],
            [HTTP::StatusRequestHeaderFieldsTooLarge, 'Request Header Fields Too Large', 'RequestHeaderFieldsTooLarge'],
            [HTTP::StatusNetworkAuthenticationRequired, 'Network Authentication Required', 'NetworkAuthenticationRequired'],
        ];
    }

    #[DataProvider('statusProvider')]
    public function testStatusTextAndNameFromNamespaceFunctions(int $code, string $text, string $name): void
    {
        self::assertSame($text, StatusText($code));
        self::assertSame($name, StatusName($code));
    }

    #[DataProvider('statusProvider')]
    public function testStatusTextAndNameFromHttpClass(int $code, string $text, string $name): void
    {
        self::assertSame($text, HTTP::StatusText($code));
        self::assertSame($name, HTTP::StatusName($code));
    }

    public function testUnknownStatusCodeReturnsEmptyStrings(): void
    {
        self::assertSame('', StatusText(9999));
        self::assertSame('', StatusName(9999));
        self::assertSame('', HTTP::StatusText(9999));
        self::assertSame('', HTTP::StatusName(9999));
    }

    public function testMethodConstantsMatchHttpVerbs(): void
    {
        self::assertSame('GET', HTTP::MethodGet);
        self::assertSame('HEAD', HTTP::MethodHead);
        self::assertSame('POST', HTTP::MethodPost);
        self::assertSame('PUT', HTTP::MethodPut);
        self::assertSame('PATCH', HTTP::MethodPatch);
        self::assertSame('DELETE', HTTP::MethodDelete);
        self::assertSame('CONNECT', HTTP::MethodConnect);
        self::assertSame('OPTIONS', HTTP::MethodOptions);
        self::assertSame('TRACE', HTTP::MethodTrace);
    }
}
