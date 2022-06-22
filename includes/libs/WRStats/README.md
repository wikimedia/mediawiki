WRStats is a library for production-compatible metric storage and retrieval.

Currently, only counters are supported, in the RRDTool sense of an
increment-only value with some support for calculating derivatives (rates).

Memcached is the intended data store. Since memcached does not support
floating-point increment, metrics can be transparently scaled by a resolution
factor.

The counter is split into time window "buckets". To read a rate, all buckets in
the requested time range are fetched. When the boundary of the time range does
not exactly coincide with the boundary of a bucket, interpolation is applied,
to scale down the counter value from the bucket.

Interpolation assumes that the rate of events in the time window is constant.
If the end of the bucket would be after the current time, the current time is
used as the bucket end time for interpolation purposes. In other words, the
number of events in the future is assumed to be zero; all events are assumed to
be in the past.

Usage:

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
$wrstatsFactory = MediaWikiServices::getInstance()->getWRStatsFactory();
$writer = $wrstatsFactory->createWriter( $specs );
$writer->incr( 'a', null, 1 );
$writer->incr( 'b', null, 25 );
$writer->flush();

$reader = $wrstatsFactory->createReader( $specs );
$rateA = $reader->getRate( 'a', null, $reader->latest( 60 ) );
$rateB = $reader->getRate( 'b', null, $reader->latest( 120 ) );
print $rateA->perSecond();
print $rateB->perMinute();
```
