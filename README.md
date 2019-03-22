# Parable Http

[![Build Status](https://travis-ci.org/parable-php/http.svg?branch=master)](https://travis-ci.org/parable-php/http)
[![Latest Stable Version](https://poser.pugx.org/parable-php/http/v/stable)](https://packagist.org/packages/parable-php/http)
[![Latest Unstable Version](https://poser.pugx.org/parable-php/http/v/unstable)](https://packagist.org/packages/parable-php/http)
[![License](https://poser.pugx.org/parable-php/http/license)](https://packagist.org/packages/parable-php/http)

Parable Http is a minimalist Http library used to receive requests and send responses. It is not a full implementation, offering just-enough functionality.

## Install

Php 7.1+ and [composer](https://getcomposer.org) are required.

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

#### Request
- `getMethod(): string` - returns `GET`, `POST`, etc.
- `getUri(): Uri` - return a `Uri` object representing the uri being requested.
- `getRequestUri(): ?string` - the path of the `Uri`
- `getProtocol(): string` - the protocol used, `HTTP/1.1` by default
- `getProtocolVersion(): string` - just the `1.1` part of the protocol
- `getBody(): ?string` - the body of the request, if any
- `getUser(): ?string` - the username from the uri
- `getPass(): ?string` - the password from the uri
- `isHttps(): bool` - whether the request was made over https. This represents a 'best guess' based on multiple checks.
- `isMethod(string $method): bool` - check whether the method matches `$method`

#### Response
The `Response` class is immutable, meaning it can't be altered after creation, but new copies with altered properties can be created using the `withSomething()` methods.

- `getStatusCode(): int` - the status code to be sent (i.e. `200`)
- `getStatusCodeText(): int` - the status code text to be sent (i.e. `OK`)
- `getBody(): ?string` - the body to be sent
- `getContentType(): string` - the content type (i.e. `text/html`, `application/json`)
- `getProtocol(): string` - the protocol used (i.e. `HTTP/1.1`)
- `getProtocolVersion(): string` - the protocol version (i.e. `1.1`)
- `withStatusCode(int $value): self` - 
- `withBody(string $value): self` - 
- `withPrependedBody(string $value): self` - 
- `withAppendedBody(string $value): self` - 
- `withContentType(string $value): self` - 
- `withHeader(string $header, string $value): self` - 
- `withHeaders(array $headers): self` - 
- `withAddedHeaders(array $headers): self` - 
- `withProtocol(string $value): self` - 

## Contributing

Any suggestions, bug reports or general feedback is welcome. Use github issues and pull requests, or find me over at [devvoh.com](https://devvoh.com).

## License

All Parable components are open-source software, licensed under the MIT license.
