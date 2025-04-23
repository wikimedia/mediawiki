WRStats is a library for production-compatible metric storage and retrieval.

## Data model

Currently, only counters are supported, in the RRDTool sense of an
increment-only value with some support for calculating derivatives (rates).

Memcached is the intended data store. Since memcached does not support
floating-point increment, metrics can be transparently scaled by a resolution
factor.

Each metric can contain multiple time-series sequences. A sequence is a set of
counter values, equally spaced in time, with a fixed retention period. So for
example, a metric might contain a sequence with a value for each second,
expiring after one minute, and a sequence with a value for each minute,
expiring after one hour.

To read a rate, a sequence is selected, then all buckets in the requested time
range are fetched. When the boundary of the time range does not exactly
coincide with the boundary of a bucket, interpolation is applied, to scale down
the counter value from the bucket.

Interpolation assumes that the rate of events in the time window is constant.
If the end of the bucket would be after the current time, the current time is
used as the bucket end time for interpolation purposes. In other words, the
number of events in the future is assumed to be zero; all events are assumed to
be in the past.

## Storage keys

Storage keys are composed of an array of components, conventionally joined by
the storage class into a string key separated by colons:

    <prefix> : <metric name> : <sequence name> : <time> [ : <entity key> ]

* The prefix is a string or array of strings which are fixed for a given
  factory. The prefix is supposed to identify the caller.
* The metric name identifies the metric in the configuration.
* The sequence name identifies the sequence. If there is only one unnamed
  sequence, its name is the empty string. Subsequent sequences will be named
  after their key in the configuration array. An explicit sequence name can be
  given if desired for stability.
* The time bucket is identified by an integer.
* Additional key components can be supplied. This "entity key" is used to
  distinguish different instances of a metric which share the same
  configuration. For example, if you want to count the number of edits by
  each user, the entity key could include the user ID.

Entity keys may either be local (LocalEntityKey) or global (GlobalEntityKey).
This flag is passed down to the storage class. If a local entity key is
supplied, MediaWiki will prefix the key with the name of the wiki.

## Usage

```php
$specs = [
   'a' => [
      'type' => 'counter',
      'sequences' => [ [
          'timeStep' => 10,
          'expiry' => 3600,
      ] ],
      'resolution' => 1,
   ],
   'b' => [
      'type' => 'counter',
      'resolution' => 0.01,
      'sequences' => [ [
          'timeStep' => 1,
          'expiry' => 60,
      ] ]
   ],
];
$entity = new LocalEntityKey( [ 'user_id' => 1 ] );

$wrstatsFactory = MediaWikiServices::getInstance()->getWRStatsFactory();
$writer = $wrstatsFactory->createWriter( $specs );
$writer->incr( 'a', $entity, 1 );
$writer->incr( 'b', $entity, 25 );
$writer->flush();

$reader = $wrstatsFactory->createReader( $specs );
$rateA = $reader->getRate( 'a', $entity, $reader->latest( 60 ) );
$rateB = $reader->getRate( 'b', $entity, $reader->latest( 120 ) );
print $rateA->perSecond();
print $rateB->perMinute();
```

## Rate limiter

WRStatsRateLimiter uses underlying WRStats metrics to implement rate limiting
functionality. The rate limiter allows you to limit the number of actions in a
rolling time window.

WRStats does not require a synchronous data store, so the counter may overshoot
its limit slightly before actions are prevented.

Typical usage:

```php
$wrstatsFactory = MediaWikiServices::getInstance()->getWRStatsFactory();
$rateLimiter = $wrstatsFactory->createRateLimiter(
    [
        'by_user' => new LimitCondition( 10, 60 ), // 10 edits per minute
    ],
    'MyLimitCaller'
);
$entity = new LocalEntityKey( [ 'user_id', 1 ] );

if ( $rateLimiter->tryIncr( 'by_user', $entity )->isAllowed() ) {
    // perform the action
}
```
