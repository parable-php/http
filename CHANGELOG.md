# Parable Http

## 0.5.0

_Changes_
- `dispatch` now terminates by default, `dispatchAndTerminate` has been removed, and `dispatchWithoutTerminate` has been added.
- `Parable\Http\Exception` has been renamed to `Parable\Http\HttpException` for clearer usage.

## 0.4.0

_Changes_
- Dropped support for php7, php8 only from now on.

## 0.3.1
_Fixes_
- `Uri` could not deal with double ports, i.e. `devvoh.com:8000:8000` and would set `devvoh.com:8000` as the host, causing issues. This is now fixed. The host is no longer allowed to have port numbers in it.

## 0.3.0

_Changes_
- `Uri` now accepts a `null` value on `withPort()`, `withUser()`, `withPass()`, `withPath()`, `withQuery()` and `withFragment()` to reset these values properly.
- `Uri` also accepts an empty array in `withQueryArray()`
- Urls generated now always have a `/` between query parts, fragments and the actual url.

## 0.2.1

_Changes_
- `Uri` has gained 2 methods: `getUriBaseString()` and `getUriRestString`. Now it's easily possible to get the base uri, or only the path/query/fragment side of one.

## 0.2.0

_Changes_
- Removed all `with` methods from `Response`, replaced with `set` methods. It's no longer immutable.
- Traits have been renamed to `HasHeaders` and `HasStatusCode`. `HasHeaders` only provides get methods.
- Methods on `SupportsOutputBuffers` have been suffixed with `OutputBuffer(s)` for clairity when used within a class using it.
- `Dispatcher` has been renamed to `ResponseDispatcher` because `Dispatcher` is kinda generic.
- `ResponseDispatcher::setShouldTerminate()` has been removed. `->dispatch()` now never terminates, and `->dispatchAndTerminate()` always does.

## 0.1.0

_Changes_
- First release.
