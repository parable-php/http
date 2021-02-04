<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testIsPopulatedFromServerIfNoConstructorParametersPassed(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        $request = new Request();

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://test.dev', $request->getUri()->__toString());
    }

    public function testGetUri(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertSame("http://test.dev/folder/being/requested", (string)$request->getUri());
    }

    public function testGetMethod(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertSame("GET", $request->getMethod());
    }

    public function testGetRequestUri(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertSame("folder/being/requested", $request->getRequestUri());
    }

    public function testGetProtocol(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertSame("HTTP/1.1", $request->getProtocol());
    }

    public function testGetProtocolVersion(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertSame("1.1", $request->getProtocolVersion());
    }

    public function testGetBodyWithoutBodyReturnsNull(): void
    {
        $request = new Request('GET', 'http://test.dev/folder/being/requested');

        self::assertNull($request->getBody());
    }

    public function testGetBodyReturnsInputSource(): void
    {
        $request = new class ('GET', 'https://test.dev') extends Request {
            protected const INPUT_SOURCE = __DIR__ . '/Files/InputSource.txt';
        };

        self::assertSame("test=true", $request->getBody());
    }

    public function testGetHeaderReturnsNullOnNonExistingHeader(): void
    {
        $request = new Request('GET', 'http://test.dev');

        self::assertNull($request->getHeader('host'));
    }

    public function testGetHeaderCanGetIndividualHeader(): void
    {
        $request = new Request('GET', 'http://test.dev', ['host' => 'test.dev']);

        self::assertSame('test.dev', $request->getHeader('host'));
    }

    public function testGetHeaderCanGetIndividualHeaderAndIgnoresCase(): void
    {
        $request = new Request('GET', 'http://test.dev', ['HoSt' => 'test.dev']);

        self::assertSame('test.dev', $request->getHeader('hOsT'));
    }

    public function testGetHeadersEmptyIfNotSet(): void
    {
        $request = new Request('GET', 'http://test.dev');

        self::assertEmpty($request->getHeaders());
    }

    public function testGetHeaderSetProperly(): void
    {
        $request = new Request('GET', 'http://test.dev', ['host' => 'test.dev']);

        self::assertSame(['host' => 'test.dev'], $request->getHeaders());
    }

    public function testIsHttpsWorksProperly(): void
    {
        self::assertFalse((new Request('GET', 'http://test.dev'))->isHttps());
        self::assertTrue((new Request('GET', 'https://test.dev'))->isHttps());
    }

    public function testIsMethodWorksProperly(): void
    {
        self::assertTrue((new Request('GET', 'http://test.dev'))->isMethod('GET'));
        self::assertTrue((new Request('POST', 'http://test.dev'))->isMethod('POST'));
        self::assertTrue((new Request('PUT', 'http://test.dev'))->isMethod('PUT'));
        self::assertTrue((new Request('PATCH', 'http://test.dev'))->isMethod('PATCH'));

        self::assertFalse((new Request('GET', 'http://test.dev'))->isMethod('POST'));
        self::assertFalse((new Request('GET', 'http://test.dev'))->isMethod('PUT'));
        self::assertFalse((new Request('GET', 'http://test.dev'))->isMethod('PATCH'));
    }

    public function testIsMethodIgnoresCase(): void
    {
        self::assertTrue((new Request('GeT', 'http://test.dev'))->isMethod('gEt'));
    }
}
