<?php declare(strict_types=1);

namespace Parable\Http;

class HeaderSender
{
    /** @var string[] */
    protected static array $headers = [];

    protected static bool $testMode = false;

    public static function setTestMode(bool $testMode): void
    {
        self::$testMode = $testMode;
    }

    public static function send(string $header): void
    {
        if (self::$testMode) {
            self::$headers[] = $header;

            return;
        }

        header($header);
    }

    public static function alreadySent(): bool
    {
        if (self::$testMode) {
            return false;
        }

        return headers_sent();
    }

    public static function list(): array
    {
        if (self::$testMode) {
            return self::$headers;
        }

        return headers_list();
    }
}
