<?php declare(strict_types=1);

namespace Parable\Http;

use Parable\Http\Traits\HasHeaders;
use Parable\Http\Traits\HasStatusCode;

class Response
{
    use HasHeaders;
    use HasStatusCode;

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

        $this->addHeader('Content-Type', $contentType);
        $this->addHeaders($headers);

        $this->setStatusCode($statusCode);
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

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setPrependedBody(string $content): void
    {
        $this->body = $content . $this->body;
    }

    public function setAppendedBody(string $content): void
    {
        $this->body = $this->body . $content;
    }

    public function setContentType(string $contentType): void
    {
        $this->addHeader('Content-Type', $contentType);
    }

    public function setHeaders(array $headers): void
    {
        $contentType = $this->getContentType();

        $this->clearHeaders();
        $this->addHeader('Content-Type', $contentType);
        $this->addHeaders($headers);
    }

    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
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

    protected function clearHeaders(): void
    {
        $this->headers = [];
    }
}
