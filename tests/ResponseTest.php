<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testSimple200ResponseWithDefaultValues(): void
    {
        $response = new Response(200, 'Hello world.');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('OK', $response->getStatusCodeText());
        self::assertSame('Hello world.', $response->getBody());
        self::assertSame('text/html', $response->getContentType());
        self::assertSame('HTTP/1.1', $response->getProtocol());
        self::assertSame(['Content-Type' => 'text/html'], $response->getHeaders());
        self::assertNull($response->getHeader('test'));
    }

    public function testResponseWithCustomStatusCode(): void
    {
        $response = new Response(418, 'Teapot, baby!');

        self::assertSame(418, $response->getStatusCode());
        self::assertSame('I\'m a teapot', $response->getStatusCodeText());
        self::assertSame('Teapot, baby!', $response->getBody());
    }

    public function testResponseWithUnknownStatusCode(): void
    {
        $response = new Response(10009, 'What the heck kinda status code is that?');

        self::assertSame(10009, $response->getStatusCode());
        self::assertNull($response->getStatusCodeText());
        self::assertSame('What the heck kinda status code is that?', $response->getBody());
    }

    public function testResponseWithCustomContentType(): void
    {
        $response = new Response(200, '{}', 'application/json');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getContentType());
    }

    public function testResponseWithCustomHeaders(): void
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $response = new Response(200, 'Hello.', 'text/html', $headers);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getContentType());
    }

    public function testResponseWithCustomProtocol(): void
    {
        $response = new Response(200, 'Hello.', 'text/html', [], 'HTTP/5.0');

        self::assertSame('HTTP/5.0', $response->getProtocol());
        self::assertSame('5.0', $response->getProtocolVersion());
    }

    public function testSetStatusCode(): void
    {
        $response = new Response(200, 'Hello.');

        self::assertSame(200, $response->getStatusCode());

        $response->setStatusCode(404);

        self::assertSame(404, $response->getStatusCode());
    }

    public function testSetBody(): void
    {
        $response = new Response(200, 'Hello,');

        self::assertSame('Hello,', $response->getBody());

        $response->setBody('World!');

        self::assertSame('World!', $response->getBody());
    }

    public function testSetPrependedBody(): void
    {
        $response = new Response(200, 'world!');

        self::assertSame('world!', $response->getBody());

        $response->prependBody('Hello, ');

        self::assertSame('Hello, world!', $response->getBody());
    }

    public function testSetAppendedBody(): void
    {
        $response = new Response(200, 'Hello,');

        self::assertSame('Hello,', $response->getBody());

        $response->appendBody(' world!');

        self::assertSame('Hello, world!', $response->getBody());
    }

    public function testSetContentType(): void
    {
        $response = new Response(200, 'Hello.');

        self::assertSame('text/html', $response->getContentType());

        $response->setContentType('application/json');

        self::assertSame('application/json', $response->getContentType());
    }

    public function testAddHeader(): void
    {
        $response = new Response(200, 'Hello.');

        self::assertNull($response->getHeader('TestHeader'));

        $response->addHeader('TestHeader', 'set');

        self::assertSame('set', $response->getHeader('TestHeader'));
    }

    public function testAddHeaders(): void
    {
        $response = new Response(200, 'Hello.', 'text/html', ['TestHeader' => 'set']);

        self::assertCount(2, $response->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'TestHeader' => 'set',
            ],
            $response->getHeaders()
        );

        $response->addHeaders([
            'OtherHeader' => 'now set',
        ]);

        self::assertCount(3, $response->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'TestHeader' => 'set',
                'OtherHeader' => 'now set',
            ],
            $response->getHeaders()
        );
    }

    public function testSetHeadersOverwritesAllButContentType(): void
    {
        $response = new Response(200, 'Hello.', 'text/html', ['TestHeader' => 'set']);

        self::assertCount(2, $response->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'TestHeader' => 'set',
            ],
            $response->getHeaders()
        );

        $response->setHeaders([
            'OtherHeader' => 'now set',
        ]);

        self::assertCount(2, $response->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'OtherHeader' => 'now set',
            ],
            $response->getHeaders()
        );
    }

    public function testSetProtocol(): void
    {
        $response = new Response(200, 'Hello.');

        self::assertSame('HTTP/1.1', $response->getProtocol());

        $response->setProtocol('HTTP/2.0');

        self::assertSame('HTTP/2.0', $response->getProtocol());
    }
}
