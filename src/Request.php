<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\HasHeaders;

class Request
{
    use HasHeaders;

    protected const INPUT_SOURCE = 'php://input';

    protected $method;
    protected $uri;
    protected $protocol;
    protected $body;

    public function __construct(
        string $method,
        string $uri,
        array $headers = [],
        string $protocol = 'HTTP/1.1'
    ) {
        $this->method = strtoupper($method);
        $this->uri = new Uri($uri);
        $this->protocol = $protocol;

        $this->addHeaders($headers);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): Uri
    {
        return $this->uri;
    }

    public function getRequestUri(): ?string
    {
        return $this->uri->getPath();
    }

    public function getProtocol(): string
    {
        return $this->protocol ?? 'HTTP/1.1';
    }

    public function getProtocolVersion(): string
    {
        return str_replace('HTTP/', '', $this->getProtocol());
    }

    public function getBody(): ?string
    {
        if ($this->body === null) {
            $body = file_get_contents(static::INPUT_SOURCE);

            if (mb_strlen($body) > 0) {
                $this->body = trim($body);
            }
        }

        return $this->body;
    }

    public function getUser(): ?string
    {
        return $this->uri->getUser();
    }

    public function getPass(): ?string
    {
        return $this->uri->getPass();
    }

    public function isHttps(): bool
    {
        return $this->uri->isHttps();
    }

    public function isMethod(string $method): bool
    {
        return $this->getMethod() === strtoupper($method);
    }

    protected function addHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
    }

    protected function addHeader(string $header, string $value): void
    {
        $normalized = $this->normalize($header);

        $this->originalHeaders[$normalized] = $header;

        $this->headers[$normalized] = $value;
    }
}
