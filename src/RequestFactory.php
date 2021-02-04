<?php declare(strict_types=1);

namespace Parable\Http;

class RequestFactory
{
    public static function createFromServer(): Request
    {
        return new Request(...self::getValuesFromServer());
    }

    public static function getValuesFromServer(): array
    {
        $method = self::getMethodFromServerArray($_SERVER);
        $uri = self::buildUriFromServerArray($_SERVER);
        $headers = getallheaders();
        $protocol = self::getProtocolFromServerArray($_SERVER);

        if ($uri === null) {
            throw new HttpException('Could not build uri from $_SERVER array.');
        }

        return [$method, $uri, $headers, $protocol];
    }

    protected static function buildUriFromServerArray(array $serverArray): ?Uri
    {
        $uri = [];

        $uri[] = self::getSchemeFromServerArray($serverArray);
        $uri[] = '://';

        $auth = self::getAuthFromServerArray($serverArray);
        if ($auth !== null) {
            $uri[] = $auth;
            $uri[] = '@';
        }

        $uri[] = self::getHostFromServerArray($serverArray);

        $port = self::getPortFromServerArray($serverArray);
        if ($port !== null) {
            $uri[] = ':';
            $uri[] = $port;
        }

        $requestUri = self::getRequestUriFromServerArray($serverArray);
        if ($requestUri !== null) {
            $uri[] = $requestUri;
        }

        // If any of the values are `null`, we don't have enough information to build a uri
        if (in_array(null, $uri, true)) {
            return null;
        }

        return new Uri(implode($uri));
    }

    protected static function getMethodFromServerArray(array $serverArray): string
    {
        return $serverArray['REQUEST_METHOD'] ?? 'GET';
    }

    protected static function getSchemeFromServerArray(array $serverArray): string
    {
        if (self::isValueSetAndMatches($serverArray, 'REQUEST_SCHEME', 'https')) {
            return 'https';
        }

        if (self::isValueSetAndMatches($serverArray, 'REDIRECT_REQUEST_SCHEME', 'https')) {
            return 'https';
        }

        if (self::isValueSetAndMatches($serverArray, 'HTTP_X_FORWARDED_PROTO', 'https')) {
            return 'https';
        }

        if (self::isValueSetAndMatches($serverArray, 'HTTPS', 'on')) {
            return 'https';
        }

        if (self::isValueSetAndMatches($serverArray, 'SERVER_PORT', '443')) {
            return 'https';
        }

        return 'http';
    }

    protected static function getAuthFromServerArray(array $serverArray): ?string
    {
        $auth = [
            'user' => $serverArray['PHP_AUTH_USER'] ?? null,
            'pass' => $serverArray['PHP_AUTH_PW'] ?? null,
        ];

        if ($auth['user'] === null) {
            return null;
        }

        if ($auth['pass'] === null) {
            return $auth['user'];
        }

        return implode(':', $auth);
    }

    protected static function getHostFromServerArray(array $serverArray): ?string
    {
        $host = $serverArray['HTTP_HOST'] ?? null;

        if ($host === null) {
            return null;
        }

        return rtrim($host, '/');
    }

    protected static function getPortFromServerArray(array $serverArray): ?string
    {
        return $serverArray['SERVER_PORT'] ?? null;
    }

    protected static function getRequestUriFromServerArray(array $serverArray): ?string
    {
        $requestUri = $serverArray['REQUEST_URI'] ?? null;

        if ($requestUri === null) {
            return null;
        }

        return '/' . ltrim($requestUri, '/');
    }

    protected static function getProtocolFromServerArray(array $serverArray): string
    {
        return $serverArray['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    }

    protected static function isValueSetAndMatches(array $serverArray, string $key, string $valueToMatch): bool
    {
        return ($serverArray[$key] ?? null) === $valueToMatch;
    }
}
