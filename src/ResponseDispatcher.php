<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\SupportsOutputBuffers;

class ResponseDispatcher
{
    use SupportsOutputBuffers;

    public function dispatch(Response $response, int $exitCode = 0): void
    {
        $this->dispatchWithoutTerminate($response);
        $this->terminate($exitCode);
    }

    public function dispatchWithoutTerminate(Response $response): void
    {
        if (HeaderSender::alreadySent()) {
            throw new HttpException('Cannot dispatch response if headers already sent.');
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

        $bufferedContent = $this->getAllOutputBuffers();

        echo $bufferedContent . $response->getBody();
    }

    /**
     * @codeCoverageIgnore
     */
    protected function terminate(int $exitCode): void
    {
        exit($exitCode);
    }
}
