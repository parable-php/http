<?php declare(strict_types=1);

namespace Parable\Http;

class Uri
{
    /**
     * @var string|null
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $pass;

    /**
     * @var string|null
     */
    protected $host;

    /**
     * @var int|null
     */
    protected $port;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $query;

    /**
     * @var string|null
     */
    protected $fragment;

    public function __construct(string $uri)
    {
        $parsed = parse_url($uri);

        self::setPropertiesFromArray($this, $parsed);
    }

    public static function setPropertiesFromArray(self $uri, array $values): self
    {
        $uri->scheme = $values['scheme'] ?? null;
        $uri->user = $values['user'] ?? null;
        $uri->pass = $values['pass'] ?? null;
        $uri->host = $values['host'] ?? null;
        $uri->port = $values['port'] ?? null;
        $uri->path = $values['path'] ?? null;
        $uri->query = $values['query'] ?? null;
        $uri->fragment = $values['fragment'] ?? null;

        if ($uri->host !== null) {
            $portPosition = strpos($uri->host, ':');

            if ($portPosition !== false) {
                $uri->host = substr_replace($uri->host, '', $portPosition);
            }
        }

        if ($uri->path !== null) {
            $uri->path = ltrim($uri->path, '/');
        }

        return $uri;
    }

    public function getUriString(): string
    {
        $restUri = $this->getUriRestString();

        if (strlen($restUri) > 0 && !in_array($restUri[0], ['?', '#'])) {
            $restUri = '/' . ltrim($restUri, '/');
        }

        return trim($this->getUriBaseString() . $restUri, '/');
    }

    public function getUriBaseString(): string
    {
        $parts = [];

        $parts[] = $this->getScheme();
        $parts[] = '://';

        if ($this->getUser() !== null) {
            $parts[] = $this->getUser();

            if ($this->getPass() !== null) {
                $parts[] = ':';
                $parts[] = $this->getPass();
            }

            $parts[] = '@';
        }

        $parts[] = $this->getHost();

        // We need a port, but we ignore the defaults for http/https
        if ($this->getPort() !== null && !$this->isPortDefaultForScheme()) {
            $parts[] = ':';
            $parts[] = $this->getPort();
        }

        return implode($parts);
    }

    public function getUriRestString(): string
    {
        $parts = [];

        if ($this->getPath() !== null) {
            $parts[] = $this->getPath();
        } else {
            $parts[] = '/';
        }

        if ($this->getQuery() !== null) {
            $parts[] = '?';
            $parts[] = $this->getQuery();
        }

        if ($this->getFragment() !== null) {
            $parts[] = '#';
            $parts[] = $this->getFragment();
        }

        return implode($parts);
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function withScheme(string $value): self
    {
        $clone = clone $this;
        $clone->scheme = $value;

        return $clone;
    }

    public function isHttps(): bool
    {
        return $this->getScheme() === 'https';
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function withHost(string $value): self
    {
        $clone = clone $this;
        $clone->host = $value;

        return $clone;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function withPort(?int $value): self
    {
        $clone = clone $this;
        $clone->port = $value;

        return $clone;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function withUser(?string $value): self
    {
        $clone = clone $this;
        $clone->user = $value;

        return $clone;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function withPass(?string $value): self
    {
        $clone = clone $this;
        $clone->pass = $value;

        return $clone;
    }

    public function getPath(): ?string
    {
        return $this->path ?? null;
    }

    public function withPath(?string $value): self
    {
        $clone = clone $this;
        $clone->path = $value;

        return $clone;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function withQuery(?string $value): self
    {
        $clone = clone $this;
        $clone->query = $value;

        return $clone;
    }

    public function getQueryArray(): array
    {
        if ($this->getQuery() === null) {
            return [];
        }

        parse_str($this->getQuery(), $parsed);

        return $parsed;
    }

    public function withQueryArray(array $values): self
    {
        if ($values === []) {
            return $this->withQuery(null);
        }

        return $this->withQuery(http_build_query($values));
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    public function withFragment(?string $value): self
    {
        $clone = clone $this;
        $clone->fragment = $value;

        return $clone;
    }

    public function __toString(): string
    {
        return $this->getUriString();
    }

    protected function isPortDefaultForScheme(): bool
    {
        if ($this->isHttps() && $this->getPort() === 443) {
            return true;
        }

        if (!$this->isHttps() && $this->getPort() === 80) {
            return true;
        }

        return false;
    }
}
