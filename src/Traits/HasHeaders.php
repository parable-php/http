<?php

namespace Parable\Http\Traits;

trait HasHeaders
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

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        $headers = [];

        foreach ($this->headers as $normalizedHeader => $value) {
            $originalHeader = $this->originalHeaders[$normalizedHeader];

            $headers[$originalHeader] = $value;
        }

        return $headers;
    }

    protected function normalize(string $value): string
    {
        return strtolower(trim($value));
    }
}
