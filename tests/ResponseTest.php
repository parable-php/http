<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple200ResponseWithDefaultValues()
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

    public function testResponseWithCustomStatusCode()
    {
        $response = new Response(418, 'Teapot, baby!');

        self::assertSame(418, $response->getStatusCode());
        self::assertSame('I\'m a teapot', $response->getStatusCodeText());
        self::assertSame('Teapot, baby!', $response->getBody());
    }

    public function testResponseWithUnknownStatusCode()
    {
        $response = new Response(10009, 'What the heck kinda status code is that?');

        self::assertSame(10009, $response->getStatusCode());
        self::assertNull($response->getStatusCodeText());
        self::assertSame('What the heck kinda status code is that?', $response->getBody());
    }

    public function testResponseWithCustomContentType()
    {
        $response = new Response(200, '{}', 'application/json');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getContentType());
    }

    public function testResponseWithCustomHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $response = new Response(200, 'Hello.', 'text/html', $headers);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('application/json', $response->getContentType());
    }

    public function testResponseWithCustomProtocol()
    {
        $response = new Response(200, 'Hello.', 'text/html', [], 'HTTP/5.0');

        self::assertSame('HTTP/5.0', $response->getProtocol());
        self::assertSame('5.0', $response->getProtocolVersion());
    }

    public function testResponseCloningWithStatusCode()
    {
        $response = new Response(200, 'Hello.');

        self::assertSame(200, $response->getStatusCode());

        $responseClone = $response->withStatusCode(404);

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(404, $responseClone->getStatusCode());
    }

    public function testResponseCloningWithBody()
    {
        $response = new Response(200, 'Hello,');

        self::assertSame('Hello,', $response->getBody());

        $responseClone = $response->withBody('World!');

        self::assertSame('Hello,', $response->getBody());
        self::assertSame('World!', $responseClone->getBody());
    }

    public function testResponseCloningWithPrependedBody()
    {
        $response = new Response(200, 'world!');

        self::assertSame('world!', $response->getBody());

        $responseClone = $response->withPrependedBody('Hello, ');

        self::assertSame('world!', $response->getBody());
        self::assertSame('Hello, world!', $responseClone->getBody());
    }

    public function testResponseCloningWithAppendedBody()
    {
        $response = new Response(200, 'Hello,');

        self::assertSame('Hello,', $response->getBody());

        $responseClone = $response->withAppendedBody(' world!');

        self::assertSame('Hello,', $response->getBody());
        self::assertSame('Hello, world!', $responseClone->getBody());
    }

    public function testResponseCloningWithContentType()
    {
        $response = new Response(200, 'Hello.');

        self::assertSame('text/html', $response->getContentType());

        $responseClone = $response->withContentType('application/json');

        self::assertSame('text/html', $response->getContentType());
        self::assertSame('application/json', $responseClone->getContentType());
    }

    public function testResponseCloningWithHeader()
    {
        $response = new Response(200, 'Hello.');

        self::assertNull($response->getHeader('TestHeader'));

        $responseClone = $response->withHeader('TestHeader', 'set');

        self::assertNull($response->getHeader('TestHeader'));
        self::assertSame('set', $responseClone->getHeader('TestHeader'));
    }

    public function testResponseCloningWithHeaders()
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

        $responseClone = $response->withHeaders([
            'OtherHeader' => 'now set',
        ]);

        self::assertCount(2, $response->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'TestHeader' => 'set',
            ],
            $response->getHeaders()
        );

        self::assertCount(2, $responseClone->getHeaders());
        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'OtherHeader' => 'now set',
            ],
            $responseClone->getHeaders()
        );
    }

    public function testResponseCloningWithAddedHeaders()
    {
        $response = new Response(200, 'Hello.', 'text/html', ['TestHeader' => 'set']);

        self::assertCount(2, $response->getHeaders());

        $responseClone = $response->withAddedHeaders([
            'OtherHeader' => 'also set',
        ]);

        self::assertCount(2, $response->getHeaders());
        self::assertCount(3, $responseClone->getHeaders());

        self::assertSame(
            [
                'Content-Type' => 'text/html',
                'TestHeader' => 'set',
                'OtherHeader' => 'also set',
            ],
            $responseClone->getHeaders()
        );
    }

    public function testResponseCloningWithProtocol()
    {
        $response = new Response(200, 'Hello.');

        self::assertSame('HTTP/1.1', $response->getProtocol());

        $responseClone = $response->withProtocol('HTTP/2.0');

        self::assertSame('HTTP/1.1', $response->getProtocol());
        self::assertSame('HTTP/2.0', $responseClone->getProtocol());
    }
}
