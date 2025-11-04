# wikimedia/objectcache

## Statistics

Sent to StatsD under MediaWiki's namespace.

### WANObjectCache

The default WANObjectCache provided by MediaWikiServices disables these
statistics in entry points where MW_ENTRY_POINT is 'cli'.

#### `wanobjectcache.{kClass}.{cache_action_and_result}`

Upon cache access via `WANObjectCache::getWithSetCallback()`, this measures the total time spent
in this method from start to end for all cases, except process-cache hits.

See also `wanobjectcache.{kClass}.regen_walltime`, which, during misses/renews, measures just the
portion of time spent in the callback to regenerate the value.

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
