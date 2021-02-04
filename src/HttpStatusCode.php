<?php declare(strict_types=1);

namespace Parable\Http;

use MyCLabs\Enum\Enum;

/**
 * @method static HttpStatusCode _100
 * @method static HttpStatusCode _101
 * @method static HttpStatusCode _200
 * @method static HttpStatusCode _201
 * @method static HttpStatusCode _202
 * @method static HttpStatusCode _203
 * @method static HttpStatusCode _204
 * @method static HttpStatusCode _205
 * @method static HttpStatusCode _206
 * @method static HttpStatusCode _300
 * @method static HttpStatusCode _301
 * @method static HttpStatusCode _302
 * @method static HttpStatusCode _303
 * @method static HttpStatusCode _304
 * @method static HttpStatusCode _305
 * @method static HttpStatusCode _307
 * @method static HttpStatusCode _308
 * @method static HttpStatusCode _400
 * @method static HttpStatusCode _401
 * @method static HttpStatusCode _402
 * @method static HttpStatusCode _403
 * @method static HttpStatusCode _404
 * @method static HttpStatusCode _405
 * @method static HttpStatusCode _406
 * @method static HttpStatusCode _407
 * @method static HttpStatusCode _408
 * @method static HttpStatusCode _409
 * @method static HttpStatusCode _410
 * @method static HttpStatusCode _411
 * @method static HttpStatusCode _412
 * @method static HttpStatusCode _413
 * @method static HttpStatusCode _414
 * @method static HttpStatusCode _415
 * @method static HttpStatusCode _416
 * @method static HttpStatusCode _417
 * @method static HttpStatusCode _418
 * @method static HttpStatusCode _421
 * @method static HttpStatusCode _426
 * @method static HttpStatusCode _428
 * @method static HttpStatusCode _429
 * @method static HttpStatusCode _431
 * @method static HttpStatusCode _451
 * @method static HttpStatusCode _500
 * @method static HttpStatusCode _501
 * @method static HttpStatusCode _502
 * @method static HttpStatusCode _503
 * @method static HttpStatusCode _504
 * @method static HttpStatusCode _505
 * @method static HttpStatusCode _506
 * @method static HttpStatusCode _507
 * @method static HttpStatusCode _511
 */
class HttpStatusCode extends Enum
{
    private const _100 = 100;
    private const _101 = 101;
    private const _200 = 200;
    private const _201 = 201;
    private const _202 = 202;
    private const _203 = 203;
    private const _204 = 204;
    private const _205 = 205;
    private const _206 = 206;
    private const _300 = 300;
    private const _301 = 301;
    private const _302 = 302;
    private const _303 = 303;
    private const _304 = 304;
    private const _305 = 305;
    private const _307 = 307;
    private const _308 = 308;
    private const _400 = 400;
    private const _401 = 401;
    private const _402 = 402;
    private const _403 = 403;
    private const _404 = 404;
    private const _405 = 405;
    private const _406 = 406;
    private const _407 = 407;
    private const _408 = 408;
    private const _409 = 409;
    private const _410 = 410;
    private const _411 = 411;
    private const _412 = 412;
    private const _413 = 413;
    private const _414 = 414;
    private const _415 = 415;
    private const _416 = 416;
    private const _417 = 417;
    private const _418 = 418;
    private const _421 = 421;
    private const _426 = 426;
    private const _428 = 428;
    private const _429 = 429;
    private const _431 = 431;
    private const _451 = 451;
    private const _500 = 500;
    private const _501 = 501;
    private const _502 = 502;
    private const _503 = 503;
    private const _504 = 504;
    private const _505 = 505;
    private const _506 = 506;
    private const _507 = 507;
    private const _511 = 511;

    public function getText(): string
    {
        return match ($this->getValue()) {
            static::_100 => "Continue",
            static::_101 => "Switching Protocols",
            static::_200 => "OK",
            static::_201 => "Created",
            static::_202 => "Accepted",
            static::_203 => "Non-Authoritative Information",
            static::_204 => "No Content",
            static::_205 => "Reset Content",
            static::_206 => "Partial Content",
            static::_300 => "Multiple Choice",
            static::_301 => "Moved Permanently",
            static::_302 => "Found",
            static::_303 => "See Other",
            static::_304 => "Not Modified",
            static::_305 => "Use Proxy",
            static::_307 => "Temporary Redirect",
            static::_308 => "Permanent Redirect",
            static::_400 => "Bad Request",
            static::_401 => "Unauthorized",
            static::_402 => "Payment Required",
            static::_403 => "Forbidden",
            static::_404 => "Not Found",
            static::_405 => "Method Not Allowed",
            static::_406 => "Not Acceptable",
            static::_407 => "Proxy Authentication Required",
            static::_408 => "Request Timeout",
            static::_409 => "Conflict",
            static::_410 => "Gone",
            static::_411 => "Length Required",
            static::_412 => "Precondition Failed",
            static::_413 => "Payload Too Large",
            static::_414 => "URI Too Long",
            static::_415 => "Unsupported Media Type",
            static::_416 => "Requested Range Not Satisfiable",
            static::_417 => "Expectation Failed",
            static::_418 => "I'm a teapot",
            static::_421 => "Misdirected Request",
            static::_426 => "Upgrade Required",
            static::_428 => "Precondition Required",
            static::_429 => "Too Many Requests",
            static::_431 => "Request Header Fields Too Large",
            static::_451 => "Unavailable For Legal Reasons",
            static::_500 => "Internal Server Error",
            static::_501 => "Not Implemented",
            static::_502 => "Bad Gateway",
            static::_503 => "Service Unavailable",
            static::_504 => "Gateway Timeout",
            static::_505 => "HTTP Version Not Supported",
            static::_506 => "Variant Also Negotiates",
            static::_507 => "Variant Also Negotiates",
            static::_511 => "Network Authentication Required",
            default => throw new HttpException('Unknown status code: ' . $this->getValue()),
        };
    }
}
