<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\ResponseDispatcher;
use Parable\Http\Exception;
use Parable\Http\HeaderSender;
use Parable\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseDispatcherTest extends TestCase
{
    protected ResponseDispatcher $dispatcher;
    protected ?int $lastExitCode = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = $this->createPartialMock(ResponseDispatcher::class, ['terminate']);

        $this->dispatcher->method('terminate')->willReturnCallback(function (int $exitCode) {
            $this->lastExitCode = $exitCode;
        });
    }

    public function testDispatchThrowsExceptionOnHeadersSent(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot dispatch response if headers already sent.');

        $response = new Response(200, 'body');

        $this->dispatcher->dispatch($response);
    }

    public function testDispatchWorksIfNoHeadersSent(): void
    {
        ob_start();

        // Setting the HeaderSender to test mode will not send headers but store them instead
        HeaderSender::setTestMode(true);

        self::assertNull($this->lastExitCode);

        $response = new Response(200, 'body');

        $this->dispatcher->dispatchAndTerminate($response);

        $headers = HeaderSender::list();

        self::assertCount(2, $headers);

        self::assertSame('HTTP/1.1 200 OK', $headers[0]);
        self::assertSame('Content-Type: text/html', $headers[1]);

        self::assertSame('body', ob_get_clean());

        self::assertSame(0, $this->lastExitCode);
    }

    public function testDispatchDoesNotTerminateWhenToldNotTo(): void
    {
        ob_start();

        // Setting the HeaderSender to test mode will not send headers but store them instead
        HeaderSender::setTestMode(true);

        self::assertNull($this->lastExitCode);

        $response = new Response(200, 'body');

        $this->dispatcher->dispatch($response);

        self::assertNull($this->lastExitCode);

        // We need to clean the output from the dispatched Response
        ob_get_clean();
    }

    public function testDispatchAndTerminateIgnoresShouldTerminateSetting(): void
    {
        ob_start();

        // Setting the HeaderSender to test mode will not send headers but store them instead
        HeaderSender::setTestMode(true);

        self::assertNull($this->lastExitCode);

        $response = new Response(200, 'body');

        $this->dispatcher->dispatchAndTerminate($response);

        self::assertSame(0, $this->lastExitCode);

        // We need to clean the output from the dispatched Response
        ob_get_clean();
    }
}
