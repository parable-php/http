<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\HasHeaders;
use Parable\Http\Traits\HasStatusCode;

class Response
{
    use HasHeaders;
    use HasStatusCode;

    protected ?string $body;
    protected string $protocol;

    public function __construct(
        int $statusCode = 200,
        string $body = null,
        string $contentType = 'text/html',
        array $headers = [],
        string $protocol = 'HTTP/1.1'
    ) {
        $this->body = $body;
        $this->protocol = $protocol;

        $this->addHeader('Content-Type', $contentType);
        $this->addHeaders($headers);

        $this->setStatusCode($statusCode);
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function prependBody(string $content): void
    {
        $this->body = $content . $this->body;
    }

    public function appendBody(string $content): void
    {
        $this->body .= $content;
    }

    public function getContentType(): string
    {
        return $this->getHeader('Content-Type') ?? 'text/html';
    }

    public function setContentType(string $contentType): void
    {
        $this->addHeader('Content-Type', $contentType);
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getProtocolVersion(): string
    {
        $parts = explode('/', $this->getProtocol());
        return end($parts);
    }

    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function setHeaders(array $headers): void
    {
        $contentType = $this->getContentType();

        $this->headers = [];

        $this->addHeader('Content-Type', $contentType);
        $this->addHeaders($headers);
    }

    public function addHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
    }

    public function addHeader(string $header, string $value): void
    {
        $normalized = $this->normalize($header);

        $this->originalHeaders[$normalized] = $header;
        $this->headers[$normalized] = $value;
    }
}
