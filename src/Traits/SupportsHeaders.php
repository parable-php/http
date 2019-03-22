<?php

namespace Parable\Http\Traits;

trait SupportsHeaders
{
    /**
     * @var string[]
     */
    protected $headers = [];

    /**
     * @var string[]
     */
    protected $originalHeaders = [];

    public function getHeader(string $header): ?string
    {
        return $this->headers[$this->normalize($header)] ?? null;
    }

    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->headers as $normalizedHeader => $value) {
            $originalHeader = $this->originalHeaders[$normalizedHeader];

            $headers[$originalHeader] = $value;
        }

        return $headers;
    }

    protected function setHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            $this->setHeader($header, $value);
        }
    }

    protected function setHeader(string $header, string $value): void
    {
        $normalized = $this->normalize($header);

        $this->originalHeaders[$normalized] = $header;

        $this->headers[$normalized] = $value;
    }

    protected function normalize(string $value): string
    {
        return strtolower(trim($value));
    }

    protected function clearHeaders(): void
    {
        $this->headers = [];
    }
}
