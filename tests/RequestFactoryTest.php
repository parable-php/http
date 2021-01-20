<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Exception;
use Parable\Http\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    public function testCreateFromServerFailsIfAllDataIsMissing(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not build uri from $_SERVER array.');

        RequestFactory::createFromServer();
    }

    public function testCreateFromServerIfHostIsGivenWithRestDefault(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        $request = RequestFactory::createFromServer();

        self::assertSame('http://test.dev', $request->getUri()->getUriString());

        // Default values
        self::assertFalse($request->isHttps());
        self::assertTrue($request->isMethod('GET'));
    }

    public function testGetMethodUsesRequestMethod(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        // Default value
        self::assertTrue((RequestFactory::createFromServer())->isMethod('GET'));

        $_SERVER['REQUEST_METHOD'] = 'POST';

        self::assertFalse((RequestFactory::createFromServer())->isMethod('GET'));
        self::assertTrue((RequestFactory::createFromServer())->isMethod('POST'));
    }

    public function testGetSchemeDerivesHttpsProperlyFromRequestScheme(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertFalse((RequestFactory::createFromServer())->isHttps());

        $_SERVER['REQUEST_SCHEME'] = 'https';

        self::assertTrue((RequestFactory::createFromServer())->isHttps());
    }

    public function testGetSchemeDerivesHttpsProperlyFromRedirectRequestScheme(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertFalse((RequestFactory::createFromServer())->isHttps());

        $_SERVER['REDIRECT_REQUEST_SCHEME'] = 'https';

        self::assertTrue((RequestFactory::createFromServer())->isHttps());
    }

    public function testGetSchemeDerivesHttpsProperlyFromHttpsXForwardedProto(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertFalse((RequestFactory::createFromServer())->isHttps());

        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

        self::assertTrue((RequestFactory::createFromServer())->isHttps());
    }

    public function testGetSchemeDerivesHttpsProperlyFromHttps(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertFalse((RequestFactory::createFromServer())->isHttps());

        $_SERVER['HTTPS'] = 'on';

        self::assertTrue((RequestFactory::createFromServer())->isHttps());
    }

    public function testGetSchemeDerivesHttpsProperlyFromServerPort(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertFalse((RequestFactory::createFromServer())->isHttps());

        $_SERVER['SERVER_PORT'] = '443';

        self::assertTrue((RequestFactory::createFromServer())->isHttps());
    }

    public function testOnlyUserAuthPickedUpProperly(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        $request1 =  RequestFactory::createFromServer();

        // Default value
        self::assertNull($request1->getUser());
        self::assertNull($request1->getPass());

        $_SERVER['PHP_AUTH_USER'] = 'devvoh';

        $request2 =  RequestFactory::createFromServer();

        self::assertSame('devvoh', $request2->getUser());
        self::assertNull($request2->getPass());
    }

    public function testAuthPicksUpUserAndPwProperly(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        $request1 =  RequestFactory::createFromServer();

        // Default value
        self::assertNull($request1->getUser());
        self::assertNull($request1->getPass());

        $_SERVER['PHP_AUTH_USER'] = 'devvoh';
        $_SERVER['PHP_AUTH_PW'] = 'password';

        $request2 =  RequestFactory::createFromServer();

        self::assertSame('devvoh', $request2->getUser());
        self::assertSame('password', $request2->getPass());
    }

    public function testRequestUriPickedUpProperly(): void
    {
        $_SERVER['HTTP_HOST'] = 'test.dev';

        self::assertNull((RequestFactory::createFromServer())->getRequestUri());

        $_SERVER['REQUEST_URI'] = 'path/to/file';

        self::assertSame('path/to/file', (RequestFactory::createFromServer())->getRequestUri());
    }

    public function tearDown(): void
    {
        unset($_SERVER['HTTP_HOST']);
        unset($_SERVER['REQUEST_SCHEME']);
        unset($_SERVER['REDIRECT_REQUEST_SCHEME']);
        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
        unset($_SERVER['HTTPS']);
        unset($_SERVER['SERVER_PORT']);
        unset($_SERVER['REQUEST_METHOD']);
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
        unset($_SERVER['REQUEST_URI']);
    }
}
