<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\HasHeaders;

class Request
{
    use HasHeaders;

    protected const INPUT_SOURCE = 'php://input';

    protected string $method;
    protected Uri $uri;
    protected string $protocol;
    protected ?string $body = null;

    public function __construct(
        string $method = null,
        string|Uri $uri = null,
        array $headers = [],
        string $protocol = 'HTTP/1.1'
    ) {
        if ($method === null || $uri === null) {
            [$method, $uri, $headers, $protocol] = RequestFactory::getValuesFromServer();
        }

        $this->method = strtoupper($method);
        $this->protocol = $protocol;
        $this->addHeaders($headers);

        if (!($uri instanceof Uri)) {
            $uri = new Uri($uri);
        }

        $this->uri = $uri;
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
        return $this->protocol;
    }

    public function getProtocolVersion(): string
    {
        return str_replace('HTTP/', '', $this->getProtocol());
    }

    public function getBody(): ?string
    {
        if ($this->body === null) {
            $body = file_get_contents(static::INPUT_SOURCE);

            if (!empty($body)) {
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
