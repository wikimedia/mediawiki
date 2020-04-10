# wikimedia/objectcache

## Statistics

Sent to StatsD under MediaWiki's namespace.

### WANObjectCache

The default WANObjectCache provided by MediaWikiServices disables these
statistics in processes where `$wgCommandLineMode` is true.

#### `wanobjectcache.{kClass}.{cache_action_and_result}`

Call counter from `WANObjectCache::getWithSetCallback()`.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.
* Variable `result`: One of:
  * `"hit.good"`,
  * `"hit.refresh"`,
  * `"hit.volatile"`,
  * `"hit.stale"`,
  * `"miss.busy"` (or `"renew.busy"`, if the `minAsOf` is used),
  * `"miss.compute"` (or `"renew.busy"`, if the `minAsOf` is used).

#### `wanobjectcache.{kClass}.regen_set_delay`

Upon cache update due to a cache miss, this measures the time spent in
`WANObjectCache::getWithSetCallback()`, from the start of the method to right after
the new value has been computed by the callback.

This essentially measures the whole method (including retrieval of any old value,
validation, any locks for `lockTSE`, and the callbacks), except for the time spent
in sending the value to the backend server.

* Type: Measure (in milliseconds).
* Variable `kClass`: The first part of your cache key.

#### `wanobjectcache.{kClass}.regen_set_bytes`

Upon cache update due to a cache miss, this estimates the size of the new value
sent from `WANObjectCache::getWithSetCallback()`.

* Type: Counter (in bytes).
* Variable `kClass`: The first part of your cache key.

#### `wanobjectcache.{kClass}.regen_walltime`

Upon cache update due to a cache miss, this measures the time spent in
`WANObjectCache::getWithSetCallback()` from the start of the callback to
right after the new value has been computed.

* Type: Measure (in milliseconds).
* Variable `kClass`: The first part of your cache key.

#### `wanobjectcache.{kClass}.ck_touch.{result}`

Call counter from `WANObjectCache::touchCheckKey()`.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.
* Variable `result`: One of `"ok"` or `"error"`.

#### `wanobjectcache.{kClass}.ck_reset.{result}`

Call counter from `WANObjectCache::resetCheckKey()`.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.
* Variable `result`: One of `"ok"` or `"error"`.

#### `wanobjectcache.{kClass}.delete.{result}`

Call counter from `WANObjectCache::delete()`.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.
* Variable `result`: One of `"ok"` or `"error"`.

#### `wanobjectcache.{kClass}.cooloff_bounce`

Upon a cache miss, the `WANObjectCache::getWithSetCallback()` method generally
recomputes the value from the callback, and stores it for re-use.

If regenerating the value costs more than a certain threshold of time (e.g. 50ms),
then for popular keys it is likely that many web servers will generate and store
the value simultaneously when the key is entirely absent from the cache. In this case,
the cool-off feature can be used to protect backend cache servers against network
congestion. This protection is implemented with a lock and subsequent cool-off period.
The winner stores their value, while other web server return their value directly.

This counter is incremented whenever a new value was regenerated but not stored.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.

When the regeneration callback is slow, these scenarios may use the cool-off feature:

* Storing the first interim value for tombstoned keys.

  If a key is currently tombstoned due to a recent `delete()` action, and thus in "hold-off", then
  the key may not be written to. A mutex lock will let one web server generate the new value and
  (until the hold-off is over) the generated value will be considered an interim (temporary) value
  only. Requests that cannot get the lock will use the last stored interim value.
  If there is no interim value yet, then requests that cannot get the lock may still generate their
  own value. Here, the cool-off feature is used to decide which requests stores their interim value.

* Storing the first interim value for stale keys.

  If a key is currently in "hold-off" due to a recent `touchCheckKey()` action, then the key may
  not be written to. A mutex lock will let one web request generate the new value and (until the
  hold-off is over) such value will be considered an interim (temporary) value only. Requests that
  lose the lock, will instead return the last stored interim value, or (if it remained in cache) the
  stale value preserved from before `touchCheckKey()` was called.
  If there is no stale value and no interim value yet, then multiple requests may need to
  generate the value simultaneously. In this case, the cool-off feature is used to decide
  which requests store their interim value.

  The same logic applies when the callback passed to getWithSetCallback() in the "touchedCallback"
  parameter starts returning an updated timestamp due to a dependency change.

* Storing the first value when `lockTSE` is used.

  When `lockTSE` is in use, and no stale value is found on the backend, and no `busyValue`
  callback is provided, then multiple requests may generate the value simultaneously;
  the cool-off is used to decide which requests store their interim value.
