<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\SupportsOutputBuffers;

class Dispatcher
{
    use SupportsOutputBuffers;

    /**
     * @var bool
     */
    protected $shouldTerminate = true;

    public function dispatch(Response $response): void
    {
        if (HeaderSender::alreadySent()) {
            throw new Exception('Cannot dispatch response if headers already sent.');
        }

        HeaderSender::send(sprintf(
            '%s %s %s',
            $response->getProtocol(),
            $response->getStatusCode(),
            $response->getStatusCodeText() ?? 'Unknown status code'
        ));

        foreach ($response->getHeaders() as $header => $value) {
            HeaderSender::send(sprintf(
                '%s: %s',
                $header,
                $value
            ));
        }

        $bufferedContent = $this->getAll();

        echo $bufferedContent . $response->getBody();

        if ($this->shouldTerminate) {
            $this->terminate(0);
        }
    }

    public function setShouldTerminate(bool $shouldTerminate): void
    {
        $this->shouldTerminate = $shouldTerminate;
    }

    public function dispatchAndTerminate(Response $response, int $exitCode = 0): void
    {
        $this->dispatch($response);
        $this->terminate($exitCode);
    }

    /**
     * @codeCoverageIgnore
     */
    protected function terminate(int $exitCode): void
    {
        exit($exitCode);
    }
}
