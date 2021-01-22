# Parable Http

[![Workflow Status](https://github.com/parable-php/http/workflows/Tests/badge.svg)](https://github.com/parable-php/http/actions?query=workflow%3ATests)
[![Latest Stable Version](https://poser.pugx.org/parable-php/http/v/stable)](https://packagist.org/packages/parable-php/http)
[![Latest Unstable Version](https://poser.pugx.org/parable-php/http/v/unstable)](https://packagist.org/packages/parable-php/http)
[![License](https://poser.pugx.org/parable-php/http/license)](https://packagist.org/packages/parable-php/http)

Parable Http is a minimalist Http library used to receive requests and send responses. It is not a full implementation, offering just-enough functionality.

## Install

Php 8.0+ and [composer](https://getcomposer.org) are required.

```bash
$ composer require parable-php/http
```

## Usage

To create a `Request` object automatically from the server variables, use:

```php
$request = RequestFactory::createFromServer();
```

To create a `Request` from scratch, use:

```php
$request = new Request(
    'GET', 
    'http://url.here/path?param=value'
);
```

To set up a minimal response you want to send to the client:

```php
$response = new Response(200, 'This is the body');
```

And to send it, use the `Dispatcher`:

```php
$response = new Response(200, 'This is the body');
$dispatcher = new Dispatcher();

$dispatcher->dispatch($response);
```

This will send a response with stat
us code `200`, with the body set as passed to the `Response` upon creation.

## API

### Request

- `getMethod(): string` - returns GET, POST, etc.
- `getUri(): Uri` - return a `Uri` object representing the uri being requested
- `getRequestUri(): ?string` - the path of the `Uri`
- `getProtocol(): string` - the protocol used (i.e. `HTTP/1.1`)
- `getProtocolVersion(): string` - the version part of the protocol (i.e. `1.1`)
- `getBody(): ?string` - the body of the request, if any
- `getUser(): ?string` - the username from the uri
- `getPass(): ?string` - the password from the uri
- `isHttps(): bool` - whether the request was made over https. This represents a 'best guess' based on multiple checks
- `isMethod(string $method): bool` - check whether the method matches `$method`

From the `HasHeaders` trait:

- `getHeader(string $header): ?string` - get a single header by string, `null` if non-existing
- `getHeaders(): string[]` - get all headers

### Response

- `getBody(): ?string` - the body to be sent
- `setBody(string $body): void` - set the body as a string 
- `prependBody(string $content): void` - prepend the value to the body
- `appendBody(string $content): void` - append the value to the body
- `getContentType(): string` - the content type (i.e. `text/html`, `application/json`)
- `setContentType(string $contentType): void` - set the content type
- `getProtocol(): string` - the protocol to be sent with (i.e. `HTTP/1.1`)
- `getProtocolVersion(): string` - the protocol version (i.e. `1.1`)
- `setProtocol(string $protocol): void` - set the protocol
- `setHeaders(array $headers): void` - set multiple headers, resetting 
- `addHeaders(array $headers): void` - add multiple headers
- `addHeader(string $header, string $value): void` - add single header

From the `HasHeaders` trait:

- `getHeader(string $header): ?string` - get a single header by string, `null` if non-existing
- `getHeaders(): string[]` - get all headers

From the `HasStatusCode` trait:

- `getStatusCode(): int` - the status code to be sent (i.e. `200`)
- `getStatusCodeText(): ?string` -  the status code text to be sent (i.e. `OK`)
- `setStatusCode(int $statusCode): void` - set the status code 

### Dispatcher

- `dispatch(Response $response): void` - dispatch a Response, sending all its content as set
- `dispatchAndTerminate(Response $response, int $exitCode = 0): void` - dispatch a Response and terminate, i.e., ending program flow immediately afterwards 


## Contributing

Any suggestions, bug reports or general feedback is welcome. Use github issues and pull requests, or find me over at [devvoh.com](https://devvoh.com).

## License

All Parable components are open-source software, licensed under the MIT license.
