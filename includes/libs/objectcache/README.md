# wikimedia/objectcache

## Statistics

Sent to StatsD under MediaWiki's namespace.

### WANObjectCache

The default WANObjectCache provided by MediaWikiServices disables these
statistics in processes where `$wgCommandLineMode` is true.

#### `wanobjectcache.{kClass}.{cache_action_and_result}`

Upon cache access via `WANObjectCache::getWithSetCallback()`, this measures the total time spent
in this method from start to end for all cases, except process-cache hits.

See also subsets of this measure:

* `wanobjectcache.{kClass}.regen_walltime`: If regenerated, just the portion of time to regenerate the value.
* `wanobjectcache.{kClass}.regen_set_delay`: If regenerated and approved for storing, the time from start to right
  before storing.

* Type: Measure (in milliseconds).
* Variable `kClass`: The first part of your cache key.
* Variable `result`: One of:
  * `"hit.good"`: A non-expired value was returned (and call did not get chosen
    for pre-emptive refresh).
  * `"hit.refresh"`: A non-expired value was returned (and call was chosen for
    a pre-emptive refresh, and an async refresh was scheduled).
  * `"hit.volatile"`: A value was found that was generated and stored less than 0.1s ago,
    and returned as-is despite appearing to also be expired already. This amount of time is
    considered negligible in terms of clock accuracy, and by random chance we usually decide
    to treat these as a cache hit (see `RECENT_SET_HIGH_MS`).
  * `"hit.stale"`: An expired value was found, but we are within the allowed stale period
    specified by a `lockTSE` option, and the current request did not get the regeneration lock.
    The stale value is returned as-is.
  * `"miss.compute"`: A new value was computed by the callback and returned.
    No non-expired value was found, and if this key needed a regeneration lock, we got the lock.
  * `"miss.busy"`: A busy value was produced by a `busyValue` callback and returned.
    No non-expired value was found, and we tried to use a regeneration lock (per the `busyValue`
    option), but the current request did not get the lock.
  * `"renew.compute"`: Artificial demand from an async refresh, led to a new value being
    computed by the callback. These are like `"miss.compute"`, but in response to `"hit.refresh"`.
  * `"renew.busy"`: Artificial demand from an async refresh failed to produce a value.
    The key used the `busyValue` option, and could not get a regeneration lock.

#### `wanobjectcache.{kClass}.regen_walltime`

Upon cache update due to a cache miss or async refresh, this measures the time spent in
the regeneration callback when computing a new value.

* Type: Measure (in milliseconds).
* Variable `kClass`: The first part of your cache key.

#### `wanobjectcache.{kClass}.cooloff_bounce`

This counter is incremented whenever a new value was computed, but not stored.

Upon a cache miss or async refresh, the `WANObjectCache::getWithSetCallback()` method
usually recomputes the value from the callback, and sends it to a backend store.

If regenerating the value takes longer than a certain threshold of time (e.g. 50ms),
then for popular keys it is likely that many web servers will generate and store
the value simultaneously when the key is entirely absent from the cache. In this case,
the cool-off feature can be used to protect backend stores against network congestion.
This protection is implemented with a lock and subsequent cool-off period.
The winner stores their value, while other web servers just return their value without
storing it.

* Type: Counter.
* Variable `kClass`: The first part of your cache key.

When the regeneration callback is slow, the following scenarios may use the cool-off feature:

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

#### `wanobjectcache.{kClass}.regen_set_delay`

Upon cache update due to a cache miss or async refresh, this measures the time spent in
`WANObjectCache::getWithSetCallback()`, from the start of the method to right after
the new value has been computed by the callback.

This essentially measures the whole method (including retrieval of any old value,
validation, any regeneration locks, and the callback), except for the time spent
in sending the value to the backend store.

* Type: Measure (in milliseconds).
* Variable `kClass`: The first part of your cache key.

#### `wanobjectcache.{kClass}.regen_set_bytes`

Upon cache update due to a cache miss or async refresh, this estimates the size of a newly
computed value sent to the backend store.

* Type: Counter (in bytes).
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
