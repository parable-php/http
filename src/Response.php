<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\SupportsHeaders;
use Parable\Http\Traits\SupportsStatusCode;

class Response
{
    use SupportsHeaders;
    use SupportsStatusCode;

    /**
     * @var string|null
     */
    protected $body;

    /**
     * @var string
     */
    protected $protocol;

    public function __construct(
        int $statusCode = 200,
        string $body = null,
        string $contentType = 'text/html',
        array $headers = [],
        string $protocol = 'HTTP/1.1'
    ) {
        $this->body = $body;
        $this->protocol = $protocol;

        $this->setHeader('Content-Type', $contentType);
        $this->setStatusCode($statusCode);
        $this->setHeaders($headers);
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getContentType(): string
    {
        return $this->getHeader('Content-Type');
    }

    public function getProtocol(): string
    {
        return $this->protocol ?? 'HTTP/1.1';
    }

    public function getProtocolVersion(): string
    {
        $parts = explode('/', $this->getProtocol());
        return end($parts);
    }

    public function withStatusCode(int $value): self
    {
        $clone = clone $this;
        $clone->statusCode = $value;

        return $clone;
    }

    public function withBody(string $value): self
    {
        $clone = clone $this;
        $clone->body = $value;

        return $clone;
    }

    public function withPrependedBody(string $value): self
    {
        return $this->withBody($value . $this->getBody());
    }

    public function withAppendedBody(string $value): self
    {
        return $this->withBody($this->getBody() . $value);
    }

    public function withContentType(string $value): self
    {
        return $this->withHeader('Content-Type', $value);
    }

    public function withHeader(string $header, string $value): self
    {
        $clone = clone $this;
        $clone->setHeader($header, $value);

        return $clone;
    }

    public function withHeaders(array $headers): self
    {
        $clone = clone $this;
        $clone->clearHeaders();
        $clone->setHeader('Content-Type', $this->getContentType());
        $clone->setHeaders($headers);

        return $clone;
    }

    public function withAddedHeaders(array $headers): self
    {
        $clone = clone $this;

        foreach ($headers as $header => $value) {
            $clone->setHeader($header, $value);
        }

        return $clone;
    }

    public function withProtocol(string $value): self
    {
        $clone = clone $this;
        $clone->protocol = $value;

        return $clone;
    }
}
