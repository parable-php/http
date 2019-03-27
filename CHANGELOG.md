# Parable Http

## 0.2.0

_Changes_
- Removed all `with` methods from `Response`, replaced with `set` methods. It's no longer immutable.
- Traits have been renamed to `HasHeaders` and `HasStatusCode`. `HasHeaders` only provides get methods.
- Methods on `SupportsOutputBuffers` have been suffixed with `OutputBuffer(s)` for clairity when used within a class using it.

## 0.1.0

_Changes_
- First release.
