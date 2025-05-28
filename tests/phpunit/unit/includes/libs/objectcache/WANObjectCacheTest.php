<?php

/** @noinspection PhpStaticAsDynamicMethodCallInspection */

namespace Wikimedia\Tests\ObjectCache;

use ArrayIterator;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use UnexpectedValueException;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\ObjectCache\WANObjectCache
 */
class WANObjectCacheTest extends MediaWikiUnitTestCase {

	/**
	 * @param array $params
	 * @return array{WANObjectCache,HashBagOStuff}
	 */
	private function newWanCache( array $params = [] ) {
		if ( isset( $params['broadcastRoutingPrefix'] ) ) {
			// Convert mcrouter broadcast keys to regular keys in HashBagOStuff::delete() calls
			$bag = new McrouterHashBagOStuff();
		} elseif ( isset( $params['serialize'] ) ) {
			$bag = new SerialHashBagOStuff();
		} else {
			$bag = new HashBagOStuff();
		}

		$cache = new WANObjectCache( $params + [ 'cache' => $bag ] );

		return [ $cache, $bag ];
	}

	/**
	 * @dataProvider provideSetAndGet
	 */
	public function testSetAndGet( $value, $ttl ) {
		[ $cache ] = $this->newWanCache();

		$curTTL = null;
		$asOf = null;
		$key = $cache->makeKey( 'x', wfRandomString() );

		$cache->get( $key, $curTTL, [], $asOf );
		$this->assertSame( null, $curTTL, "Current TTL (absent)" );
		$this->assertSame( null, $asOf, "Current as-of-time (absent)" );

		$t = microtime( true );

		$cache->set( $key, $value, $cache::TTL_UNCACHEABLE );
		$cache->get( $key, $curTTL, [], $asOf );
		$this->assertSame( null, $curTTL, "Current TTL (TTL_UNCACHEABLE)" );
		$this->assertSame( null, $asOf, "Current as-of-time (TTL_UNCACHEABLE)" );

		$cache->set( $key, $value, $ttl );

		$this->assertSame( $value, $cache->get( $key, $curTTL, [], $asOf ) );
		if ( $ttl === INF ) {
			$this->assertSame( INF, $curTTL, "Current TTL" );
		} else {
			$this->assertGreaterThan( 0, $curTTL, "Current TTL" );
			$this->assertLessThanOrEqual( $ttl, $curTTL, "Current TTL < nominal TTL" );
		}
		$this->assertGreaterThanOrEqual( $t - 1, $asOf, "As-of-time in range of set() time" );
		$this->assertLessThanOrEqual( $t + 1, $asOf, "As-of-time in range of set() time" );
	}

	public static function provideSetAndGet() {
		$a1 = [ 1 ];
		$a2 = [ 'a' => &$a1 ];

		$o1 = (object)[ 'v' => 1 ];
		$o2 = (object)[ 'a' => &$o1 ];

		$co = (object)[ 'p' => 93 ];
		$co->f =& $co;

		return [
			// value, ttl
			[ 14141, 3 ],
			[ 3535.666, 3 ],
			[ [], 3 ],
			[ '0', 3 ],
			[ (object)[ 'meow' ], 3 ],
			[ INF, 3 ],
			[ '', 3 ],
			[ 'pizzacat', INF ],
			[ null, 80 ],
			[ $a2, 3 ],
			[ $o2, 3 ],
			[ $co, 3 ]
		];
	}

	public function testGetNotExists() {
		[ $cache ] = $this->newWanCache();

		$key = $cache->makeGlobalKey( 'y', wfRandomString(), 'p' );
		$curTTL = null;
		$value = $cache->get( $key, $curTTL );

		$this->assertSame( false, $value, "Return value" );
		$this->assertSame( null, $curTTL, "current TTL" );
	}

	public function testSetOver() {
		[ $cache ] = $this->newWanCache();

		$key = wfRandomString();
		for ( $i = 0; $i < 3; ++$i ) {
			$value = wfRandomString();
			$cache->set( $key, $value, 3 );

			$this->assertSame( $cache->get( $key ), $value );
		}
	}

	public static function provideStaleSetParams() {
		return [
			// Given a db transaction (trx lag) that started 30s ago,
			// we generally don't want to cache its values.
			[ 30, 0.0, false ],
			[ 30, 2, false ],
			[ 30, 10, false ],
			[ 30, 20, false ],
			// If the main reason we've hit 30s is that we spent
			// a lot of time in the regeneration callback (as opposed
			// to time mainly having passed before the cache computation)
			// then cache it for at least a little while.
			[ 30, 28, true ],
			// Also if we don't know, cache it for a little while.
			[ 30, null, true ],
		];
	}

	/**
	 * @dataProvider provideStaleSetParams
	 * @param int $ago
	 * @param float|null $walltime
	 * @param bool $cacheable
	 */
	public function testStaleSet( $ago, $walltime, $cacheable ) {
		[ $cache ] = $this->newWanCache();
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$key = wfRandomString();
		$value = wfRandomString();

		$cache->set(
			$key,
			$value,
			$cache::TTL_MINUTE,
			[ 'since' => $mockWallClock - $ago, 'walltime' => $walltime ]
		);

		$this->assertSame(
			$cacheable ? $value : false,
			$cache->get( $key ),
			"Stale set() value ignored"
		);
	}

	public function testProcessCacheTTL() {
		[ $cache ] = $this->newWanCache();
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$key = "mykey-" . wfRandomString();

		$hits = 0;
		$callback = static function ( $oldValue, &$ttl, &$setOpts ) use ( &$hits ) {
			++$hits;
			return 42;
		};

		$cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );
		$cache->delete( $key, $cache::HOLDOFF_TTL_NONE ); // clear persistent cache
		$cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );
		$this->assertSame( 1, $hits, "Value process cached" );

		$mockWallClock += 6;
		$cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );
		$this->assertSame( 2, $hits, "Value expired in process cache" );
	}

	public function testProcessCacheLruAndDelete() {
		[ $cache ] = $this->newWanCache();
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$hit = 0;
		$fn = static function () use ( &$hit ) {
			++$hit;
			return 42;
		};
		$keysA = [ wfRandomString(), wfRandomString(), wfRandomString() ];
		$keysB = [ wfRandomString(), wfRandomString(), wfRandomString() ];
		$pcg = [ 'thiscache:1', 'thatcache:1', 'somecache:1' ];

		foreach ( $keysA as $i => $key ) {
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 3, $hit, "Values not cached yet" );

		foreach ( $keysA as $i => $key ) {
			// Should not evict from process cache
			$cache->delete( $key );
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 3, $hit, "Values cached; not cleared by delete()" );

		foreach ( $keysB as $i => $key ) {
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 6, $hit, "New values not cached yet" );

		foreach ( $keysB as $i => $key ) {
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 6, $hit, "New values cached" );

		foreach ( $keysA as $i => $key ) {
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 9, $hit, "Prior values evicted by new values" );
	}

	public function testProcessCacheInterimKeys() {
		[ $cache ] = $this->newWanCache();
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$hit = 0;
		$fn = static function () use ( &$hit ) {
			++$hit;
			return 42;
		};
		$keysA = [ wfRandomString(), wfRandomString(), wfRandomString() ];
		$pcg = [ 'thiscache:1', 'thatcache:1', 'somecache:1' ];

		// Tombstone the keys
		foreach ( $keysA as $key ) {
			$cache->delete( $key );
		}

		$mockWallClock++; // cached values will be newer than tombstone
		foreach ( $keysA as $i => $key ) {
			// Get into process cache (specific group) and interim cache
			$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5, 'pcGroup' => $pcg[$i] ] );
		}
		$this->assertSame( 3, $hit );

		// Get into process cache (default group)
		$key = reset( $keysA );
		$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5 ] );
		$this->assertSame( 3, $hit, "Value recently interim-cached" );

		$mockWallClock++; // interim key not brand new
		$cache->clearProcessCache();
		$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5 ] );
		$this->assertSame( 4, $hit, "Value calculated (interim key not recent and reset)" );
		$cache->getWithSetCallback( $key, 100, $fn, [ 'pcTTL' => 5 ] );
		$this->assertSame( 4, $hit, "Value process cached" );
	}

	public function testProcessCacheNesting() {
		[ $cache ] = $this->newWanCache();
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$keyOuter = "outer-" . wfRandomString();
		$keyInner = "inner-" . wfRandomString();

		$innerHit = 0;
		$innerFn = static function () use ( &$innerHit ) {
			++$innerHit;
			return 42;
		};

		$outerHit = 0;
		$outerFn = static function () use ( $keyInner, $innerFn, $cache, &$outerHit ) {
			++$outerHit;
			$v = $cache->getWithSetCallback( $keyInner, 100, $innerFn, [ 'pcTTL' => 5 ] );

			return 43 + $v;
		};

		$cache->getWithSetCallback( $keyInner, 100, $innerFn, [ 'pcTTL' => 5 ] );
		$cache->getWithSetCallback( $keyInner, 100, $innerFn, [ 'pcTTL' => 5 ] );

		$this->assertSame( 1, $innerHit, "Inner callback value cached" );
		$cache->delete( $keyInner, $cache::HOLDOFF_TTL_NONE );
		$mockWallClock++;

		$cache->getWithSetCallback( $keyInner, 100, $innerFn, [ 'pcTTL' => 5 ] );
		$this->assertSame( 1, $innerHit, "Inner callback process cached" );

		// Outer key misses and inner key process cache value is refused
		$cache->getWithSetCallback( $keyOuter, 100, $outerFn );

		$this->assertSame( 1, $outerHit, "Outer callback value not yet cached" );
		$this->assertSame( 2, $innerHit, "Inner callback value process cache skipped" );

		$cache->getWithSetCallback( $keyOuter, 100, $outerFn );

		$this->assertSame( 1, $outerHit, "Outer callback value cached" );

		$cache->delete( $keyInner, $cache::HOLDOFF_TTL_NONE );
		$cache->delete( $keyOuter, $cache::HOLDOFF_TTL_NONE );
		$mockWallClock++;
		$cache->clearProcessCache();
		$cache->getWithSetCallback( $keyOuter, 100, $outerFn );

		$this->assertSame( 2, $outerHit, "Outer callback value not yet cached" );
		$this->assertSame( 3, $innerHit, "Inner callback value not yet cached" );

		$cache->delete( $keyInner, $cache::HOLDOFF_TTL_NONE );
		$mockWallClock++;
		$cache->getWithSetCallback( $keyInner, 100, $innerFn, [ 'pcTTL' => 5 ] );

		$this->assertSame( 3, $innerHit, "Inner callback value process cached" );
	}

	/**
	 * @dataProvider getWithSetCallbackProvider
	 * @param array $extOpts
	 */
	public function testGetWithSetCallback( array $extOpts ) {
		[ $cache ] = $this->newWanCache();

		$key = wfRandomString();
		$value = wfRandomString();
		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$priorValue = null;
		$priorAsOf = null;
		$wasSet = 0;
		$func = static function ( $old, &$ttl, &$opts, $asOf )
		use ( &$wasSet, &$priorValue, &$priorAsOf, $value ) {
			++$wasSet;
			$priorValue = $old;
			$priorAsOf = $asOf;
			$ttl = 20; // override with another value
			return $value;
		};

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time
		$cache->setMockTime( $mockWallClock );

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] + $extOpts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );
		$this->assertSame( false, $priorValue, "No prior value" );
		$this->assertSame( null, $priorAsOf, "No prior value" );

		$curTTL = null;
		$cache->get( $key, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overridden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overridden)' );

		$wasSet = 0;
		$v = $cache->getWithSetCallback(
			$key, 30, $func, [ 'lowTTL' => 0, 'lockTSE' => 5 ] + $extOpts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 0, $wasSet, "Value not regenerated" );

		$mockWallClock++;

		$wasSet = 0;
		$v = $cache->getWithSetCallback(
			$key, 30, $func, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated due to check keys" );
		$this->assertSame( $value, $priorValue, "Has prior value" );
		$this->assertIsFloat( $priorAsOf, "Has prior value" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$mockWallClock++; // interim key is not brand new and check keys have past values
		$priorTime = $mockWallClock; // reference time
		$wasSet = 0;
		$v = $cache->getWithSetCallback(
			$key, 30, $func, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL, [ $cKey1, $cKey2 ] );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertGreaterThan( 0, $curTTL, "Value has current TTL > 0 due to T344191" );

		$wasSet = 0;
		$key = wfRandomString();
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( $value, $v, "Value returned" );
		$cache->delete( $key );
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( $value, $v, "Value still returned after deleted" );
		$this->assertSame( 1, $wasSet, "Value process cached while deleted" );

		$oldValReceived = -1;
		$oldAsOfReceived = -1;
		$checkFunc = static function ( $oldVal, &$ttl, array $setOpts, $oldAsOf )
		use ( &$oldValReceived, &$oldAsOfReceived, &$wasSet ) {
			++$wasSet;
			$oldValReceived = $oldVal;
			$oldAsOfReceived = $oldAsOf;

			return 'xxx' . $wasSet;
		};

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time

		$wasSet = 0;
		$key = wfRandomString();
		$v = $cache->getWithSetCallback(
			$key, 30, $checkFunc, [ 'staleTTL' => 50 ] + $extOpts );
		$this->assertSame( 'xxx1', $v, "Value returned" );
		$this->assertSame( false, $oldValReceived, "Callback got no stale value" );
		$this->assertSame( null, $oldAsOfReceived, "Callback got no stale value" );

		$mockWallClock += 40;
		$v = $cache->getWithSetCallback(
			$key, 30, $checkFunc, [ 'staleTTL' => 50 ] + $extOpts );
		$this->assertSame( 'xxx2', $v, "Value still returned after expired" );
		$this->assertSame( 2, $wasSet, "Value recalculated while expired" );
		$this->assertSame( 'xxx1', $oldValReceived, "Callback got stale value" );
		$this->assertNotEquals( null, $oldAsOfReceived, "Callback got stale value" );

		$mockWallClock += 260;
		$v = $cache->getWithSetCallback(
			$key, 30, $checkFunc, [ 'staleTTL' => 50 ] + $extOpts );
		$this->assertSame( 'xxx3', $v, "Value still returned after expired" );
		$this->assertSame( 3, $wasSet, "Value recalculated while expired" );
		$this->assertSame( false, $oldValReceived, "Callback got no stale value" );
		$this->assertSame( null, $oldAsOfReceived, "Callback got no stale value" );

		$mockWallClock = ( $priorTime - $cache::HOLDOFF_TTL - 1 );
		$wasSet = 0;
		$key = wfRandomString();
		$checkKey = $cache->makeKey( 'template', 'X' );
		$cache->touchCheckKey( $checkKey ); // init check key
		$mockWallClock = $priorTime;
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'graceTTL' => $cache::TTL_WEEK, 'checkKeys' => [ $checkKey ] ] + $extOpts
		);
		$this->assertSame( 'xxx1', $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value computed" );
		$this->assertSame( false, $oldValReceived, "Callback got no stale value" );
		$this->assertSame( null, $oldAsOfReceived, "Callback got no stale value" );

		$mockWallClock += $cache::TTL_HOUR; // some time passes
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[
				'graceTTL' => $cache::TTL_WEEK,
				'checkKeys' => [ $checkKey ],
				'ageNew' => -1
			] + $extOpts
		);
		$this->assertSame( 'xxx1', $v, "Cached value returned" );
		$this->assertSame( 1, $wasSet, "Cached value returned" );

		$cache->touchCheckKey( $checkKey ); // make key stale
		$mockWallClock += 0.01; // ~1 week left of grace (barely stale to avoid refreshes)

		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[
				'graceTTL' => $cache::TTL_WEEK,
				'checkKeys' => [ $checkKey ],
				'ageNew' => -1,
			] + $extOpts
		);
		$this->assertSame( 'xxx1', $v, "Value still returned after expired (in grace)" );
		$this->assertSame( 1, $wasSet, "Value still returned after expired (in grace)" );

		// Chance of refresh increase to unity as staleness approaches graceTTL
		$mockWallClock += $cache::TTL_WEEK; // 8 days of being stale
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'graceTTL' => $cache::TTL_WEEK, 'checkKeys' => [ $checkKey ] ] + $extOpts
		);
		$this->assertSame( 'xxx2', $v, "Value was recomputed (past grace)" );
		$this->assertSame( 2, $wasSet, "Value was recomputed (past grace)" );
		$this->assertSame( 'xxx1', $oldValReceived, "Callback got post-grace stale value" );
		$this->assertNotEquals( null, $oldAsOfReceived, "Callback got post-grace stale value" );
	}

	/**
	 * @dataProvider getWithSetCallbackProvider
	 * @param array $extOpts
	 */
	public function testGetWithSetCallback_touched( array $extOpts ) {
		[ $cache ] = $this->newWanCache();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$checkFunc = static function ( $oldVal, &$ttl, array $setOpts, $oldAsOf )
		use ( &$wasSet ) {
			++$wasSet;

			return 'xxx' . $wasSet;
		};

		$key = wfRandomString();
		$wasSet = 0;
		$touched = null;
		$touchedCallback = static function () use ( &$touched ) {
			return $touched;
		};
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'touchedCallback' => $touchedCallback ] + $extOpts
		);
		$mockWallClock += 60;
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'touchedCallback' => $touchedCallback ] + $extOpts
		);
		$this->assertSame( 'xxx1', $v, "Value was computed once" );
		$this->assertSame( 1, $wasSet, "Value was computed once" );

		$touched = $mockWallClock - 10;
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'touchedCallback' => $touchedCallback ] + $extOpts
		);
		$v = $cache->getWithSetCallback(
			$key,
			$cache::TTL_INDEFINITE,
			$checkFunc,
			[ 'touchedCallback' => $touchedCallback ] + $extOpts
		);
		$this->assertSame( 'xxx2', $v, "Value was recomputed once" );
		$this->assertSame( 2, $wasSet, "Value was recomputed once" );
	}

	public static function getWithSetCallbackProvider() {
		return [
			[ [], false ],
			[ [ 'version' => 1 ], true ]
		];
	}

	public function testPreemptiveRefresh() {
		// (T353180) Flaky test, to fix and re-enable
		self::markTestSkippedIfPhp( '>=', '8.2' );

		$value = 'KatCafe';
		$wasSet = 0;
		$func = static function ( $old, &$ttl, &$opts, $asOf ) use ( &$wasSet, &$value )
		{
			++$wasSet;
			return $value;
		};

		$cache = new NearExpiringWANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$wasSet = 0;
		$key = wfRandomString();
		$opts = [ 'lowTTL' => 30 ];
		$v = $cache->getWithSetCallback( $key, 20, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value calculated" );

		$mockWallClock++; // interim key is not brand new
		$v = $cache->getWithSetCallback( $key, 20, $func, $opts );
		$this->assertSame( 2, $wasSet, "Value re-calculated" );

		$wasSet = 0;
		$key = wfRandomString();
		$opts = [ 'lowTTL' => 1 ];
		$v = $cache->getWithSetCallback( $key, 30, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value calculated" );
		$v = $cache->getWithSetCallback( $key, 30, $func, $opts );
		$this->assertSame( 1, $wasSet, "Value cached" );

		$asycList = [];
		$asyncHandler = static function ( $callback ) use ( &$asycList ) {
			$asycList[] = $callback;
		};
		$cache = new NearExpiringWANObjectCache( [
			'cache'        => new HashBagOStuff(),
			'asyncHandler' => $asyncHandler
		] );

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time
		$cache->setMockTime( $mockWallClock );

		$wasSet = 0;
		$key = wfRandomString();
		$opts = [ 'lowTTL' => 100 ];
		$v = $cache->getWithSetCallback( $key, 300, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value calculated" );
		$v = $cache->getWithSetCallback( $key, 300, $func, $opts );
		$this->assertSame( 1, $wasSet, "Cached value used" );
		$this->assertSame( $v, $value, "Value cached" );

		$mockWallClock += 250;
		$v = $cache->getWithSetCallback( $key, 300, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Stale value used" );
		$this->assertCount( 1, $asycList, "Refresh deferred." );
		$value = 'NewCatsInTown'; // change callback return value
		$asycList[0](); // run the refresh callback
		$asycList = [];
		$this->assertSame( 2, $wasSet, "Value calculated at later time" );
		$this->assertSame( [], $asycList, "No deferred refreshes added." );
		$v = $cache->getWithSetCallback( $key, 300, $func, $opts );
		$this->assertSame( $value, $v, "New value stored" );

		$cache = new PopularityRefreshingWANObjectCache( [
			'cache'   => new HashBagOStuff()
		] );

		$mockWallClock = $priorTime;
		$cache->setMockTime( $mockWallClock );

		$wasSet = 0;
		$key = wfRandomString();
		$opts = [ 'hotTTR' => 900 ];
		$v = $cache->getWithSetCallback( $key, 60, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value calculated" );

		$mockWallClock += 30;

		$v = $cache->getWithSetCallback( $key, 60, $func, $opts );
		$this->assertSame( 1, $wasSet, "Value cached" );

		$mockWallClock = $priorTime;
		$wasSet = 0;
		$key = wfRandomString();
		$opts = [ 'hotTTR' => 10 ];
		$v = $cache->getWithSetCallback( $key, 60, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value calculated" );

		$mockWallClock += 30;

		$v = $cache->getWithSetCallback( $key, 60, $func, $opts );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertSame( 2, $wasSet, "Value re-calculated" );
	}

	/**
	 * @dataProvider getWithSetCallbackProvider
	 * @param array $extOpts
	 */
	public function testGetMultiWithSetCallback( array $extOpts ) {
		[ $cache ] = $this->newWanCache();

		$keyA = wfRandomString();
		$keyB = wfRandomString();
		$keyC = wfRandomString();
		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$priorValue = null;
		$priorAsOf = null;
		$wasSet = 0;
		$genFunc = static function ( $id, $old, &$ttl, &$opts, $asOf ) use (
			&$wasSet, &$priorValue, &$priorAsOf
		) {
			++$wasSet;
			$priorValue = $old;
			$priorAsOf = $asOf;
			$ttl = 20; // override with another value
			return "@$id$";
		};

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time
		$cache->setMockTime( $mockWallClock );

		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyA => 3353 ] );
		$value = "@3353$";
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lockTSE' => 5 ] + $extOpts );
		$this->assertSame( $value, $v[$keyA], "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );
		$this->assertSame( false, $priorValue, "No prior value" );
		$this->assertSame( null, $priorAsOf, "No prior value" );

		$curTTL = null;
		$cache->get( $keyA, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overridden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overridden)' );

		$wasSet = 0;
		$value = "@efef$";
		$keyedIds = new ArrayIterator( [ $keyB => 'efef' ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lowTTL' => 0, 'lockTSE' => 5 ] + $extOpts );
		$this->assertSame( $value, $v[$keyB], "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );
		$this->assertSame( 0, $cache->getWarmupKeyMisses(), "Keys warmed in warmup cache" );

		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lowTTL' => 0, 'lockTSE' => 5 ] + $extOpts );
		$this->assertSame( $value, $v[$keyB], "Value returned" );
		$this->assertSame( 1, $wasSet, "Value not regenerated" );
		$this->assertSame( 0, $cache->getWarmupKeyMisses(), "Keys warmed in warmup cache" );

		$mockWallClock++;

		$cache->touchCheckKey( $cKey1 );
		$cache->touchCheckKey( $cKey2 );

		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyB => 'efef' ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertSame( $value, $v[$keyB], "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated due to check keys" );
		$this->assertSame( $value, $priorValue, "Has prior value" );
		$this->assertIsFloat( $priorAsOf, "Has prior value" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$mockWallClock += 0.01;
		$priorTime = $mockWallClock;
		$value = "@43636$";
		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyC => 43636 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertSame( $value, $v[$keyC], "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $keyC, $curTTL, [ $cKey1, $cKey2 ] );
		$this->assertSame( $value, $v, "Value returned" );
		$this->assertLessThanOrEqual( 0, $curTTL, "Value has current TTL < 0 due to check keys" );

		$wasSet = 0;
		$key = wfRandomString();
		$keyedIds = new ArrayIterator( [ $key => 242424 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( "@{$keyedIds[$key]}$", $v[$key], "Value returned" );
		$cache->delete( $key );
		$keyedIds = new ArrayIterator( [ $key => 242424 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( "@{$keyedIds[$key]}$", $v[$key], "Value still returned after deleted" );
		$this->assertSame( 1, $wasSet, "Value process cached while deleted" );

		$calls = 0;
		$ids = [ 1, 2, 3, 4, 5, 6 ];
		$keyFunc = static function ( $id, WANObjectCache $wanCache ) {
			return $wanCache->makeKey( 'test', $id );
		};
		$keyedIds = $cache->makeMultiKeys( $ids, $keyFunc );
		$genFunc = static function ( $id, $oldValue, &$ttl, array &$setops ) use ( &$calls ) {
			++$calls;

			return "val-{$id}";
		};
		$values = $cache->getMultiWithSetCallback( $keyedIds, 10, $genFunc );

		$this->assertSame(
			[ "val-1", "val-2", "val-3", "val-4", "val-5", "val-6" ],
			array_values( $values ),
			"Correct values in correct order"
		);
		$this->assertSame(
			array_map( $keyFunc, $ids, array_fill( 0, count( $ids ), $cache ) ),
			array_keys( $values ),
			"Correct keys in correct order"
		);
		$this->assertSame( count( $ids ), $calls );

		$cache->getMultiWithSetCallback( $keyedIds, 10, $genFunc );
		$this->assertSame( count( $ids ), $calls, "Values cached" );

		// Mock the BagOStuff to assure only one getMulti() call given process caching
		$localBag = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'getMulti' ] )->getMock();
		$localBag->expects( $this->once() )->method( 'getMulti' )->willReturn( [
			'WANCache:v:' . 'k1' => 'val-id1',
			'WANCache:v:' . 'k2' => 'val-id2'
		] );
		$wanCache = new WANObjectCache( [ 'cache' => $localBag ] );

		// Warm the process cache
		$keyedIds = new ArrayIterator( [ 'k1' => 'id1', 'k2' => 'id2' ] );
		$this->assertSame(
			[ 'k1' => 'val-id1', 'k2' => 'val-id2' ],
			$wanCache->getMultiWithSetCallback( $keyedIds, 10, $genFunc, [ 'pcTTL' => 5 ] )
		);
		// Use the process cache
		$this->assertSame(
			[ 'k1' => 'val-id1', 'k2' => 'val-id2' ],
			$wanCache->getMultiWithSetCallback( $keyedIds, 10, $genFunc, [ 'pcTTL' => 5 ] )
		);
	}

	public function testGetMultiWithSetCallback_longId() {
		[ $wanCache ] = $this->newWanCache( [
			'cache' => new ShortKeyHashBagOStuff()
		] );
		$longX = str_repeat( 'x', 190 );

		$calls = 0;
		$values = $wanCache->getMultiWithSetCallback(
			$wanCache->makeMultiKeys(
				[ 1, $longX, 3 ],
				static fn ( $id ) => $wanCache->makeKey( 'maybelong', $id )
			),
			10,
			static function ( $id ) use ( &$calls ) {
				$calls++;
				return "val-{$id}";
			}
		);

		$this->assertSame(
			[ "val-1", "val-$longX", "val-3" ],
			array_values( $values ),
			"Correct values in correct order"
		);
		$this->assertSame( 3, $calls );
	}

	/**
	 * @dataProvider getMultiWithSetCallbackRefreshProvider
	 * @param bool $expiring
	 * @param bool $popular
	 * @param array $idsByKey
	 */
	public function testGetMultiWithSetCallbackRefresh( $expiring, $popular, array $idsByKey ) {
		$deferredCbs = [];
		$bag = new HashBagOStuff();
		$cache = $this->getMockBuilder( WANObjectCache::class )
			->onlyMethods( [ 'worthRefreshExpiring', 'worthRefreshPopular' ] )
			->setConstructorArgs( [
				[
					'cache' => $bag,
					'asyncHandler' => static function ( $callback ) use ( &$deferredCbs ) {
						$deferredCbs[] = $callback;
					}
				]
			] )
			->getMock();

		$cache->method( 'worthRefreshExpiring' )->willReturn( $expiring );
		$cache->method( 'worthRefreshPopular' )->willReturn( $popular );

		$wasSet = 0;
		$keyedIds = new ArrayIterator( $idsByKey );
		$genFunc = static function ( $id, $old, &$ttl, &$opts, $asOf ) use ( &$wasSet ) {
			++$wasSet;
			$ttl = 20; // override with another value
			return "@$id$";
		};

		$v = $cache->getMultiWithSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame( count( $idsByKey ), $wasSet, "Initial sets" );
		$this->assertSame( [], $deferredCbs, "No deferred callbacks yet" );
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Initial cache value generation" );
		}

		$wasSet = 0;
		$preemptiveRefresh = ( $expiring || $popular );
		$v = $cache->getMultiWithSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame( 0, $wasSet, "No values generated" );
		$this->assertCount(
			$preemptiveRefresh ? count( $idsByKey ) : 0,
			$deferredCbs,
			"Deferred callbacks queued"
		);
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Cached value reused; refresh scheduled" );
		}

		// Run the deferred callbacks...
		$deferredCbsReady = $deferredCbs;
		$deferredCbs = []; // empty by-reference queue
		foreach ( $deferredCbsReady as $deferredCb ) {
			$deferredCb();
		}

		$this->assertSame(
			( $preemptiveRefresh ? count( $idsByKey ) : 0 ),
			$wasSet,
			"Deferred callback regenerations"
		);
		$this->assertSame( [], $deferredCbs, "Deferred callbacks queue empty" );

		$wasSet = 0;
		$v = $cache->getMultiWithSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame(
			0,
			$wasSet,
			"Deferred callbacks did not run again"
		);
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Cached value OK after deferred refresh run" );
		}
	}

	public static function getMultiWithSetCallbackRefreshProvider() {
		return [
			[ true, true, [ 'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4 ] ],
			[ true, false, [ 'a' => 'x', 'b' => 'y', 'c' => 'z', 'd' => 'w' ] ],
			[ false, true, [ 'a' => 'p', 'b' => 'q', 'c' => 'r', 'd' => 's' ] ],
			[ false, false, [ 'a' => '%', 'b' => '^', 'c' => '&', 'd' => 'ç' ] ]
		];
	}

	public static function getMultiWithUnionSetCallbackProvider() {
		yield 'default' => [ [] ];
		yield 'versioned' => [ [ 'version' => 1 ] ];
	}

	/**
	 * @dataProvider getMultiWithUnionSetCallbackProvider
	 * @param array $extOpts
	 */
	public function testGetMultiWithUnionSetCallback( array $extOpts ) {
		[ $cache ] = $this->newWanCache();

		$wasSet = 0;
		$genFunc = static function ( array $ids, array &$ttls, array &$setOpts ) use (
			&$wasSet
		) {
			$wasSet++;
			$newValues = [];
			foreach ( $ids as $id ) {
				$newValues[$id] = "@$id$";
				// test that custom TTLs work
				$ttls[$id] = 20;
			}

			return $newValues;
		};

		$mockWallClock = 1549343530.0;
		$t0 = $mockWallClock;
		$cache->setMockTime( $mockWallClock );

		// A: Basic test case.
		// Uses ArrayIterator to emulate makeMultiKeys(), later cases integrate that fully.
		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ 'keyA' => 'apple' ] );
		$v = $cache->getMultiWithUnionSetCallback( $keyedIds, 30, $genFunc, $extOpts );
		$this->assertSame( '@apple$', $v['keyA'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated' );
		$curTTL = null;
		$cache->get( 'keyA', $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Custom TTL between 19-20' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Custom TTL between 19-20' );

		// B: Repeat case.
		// Disables lowTTL to avoid random interference.
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyB' => 'bat' ] ),
			30,
			$genFunc,
			[ 'lowTTL' => 0 ] + $extOpts
		);
		$this->assertSame( '@bat$', $v['keyB'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated' );
		$this->assertSame( 0, $cache->getWarmupKeyMisses(), 'Warmup batch covered all fetches' );
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyB' => 'bat' ] ),
			30,
			$genFunc,
			[ 'lowTTL' => 0 ] + $extOpts
		);
		$this->assertSame( '@bat$', $v['keyB'], 'Value returned' );
		$this->assertSame( 0, $wasSet, 'Value not regenerated' );
		$this->assertSame( 0, $cache->getWarmupKeyMisses(), 'Warmup batch covered all fetches' );

		$mockWallClock++;
		$t1 = $mockWallClock;

		// B: Repeat case with new check keys
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyB' => 'bat' ] ),
			30,
			$genFunc,
			[ 'checkKeys' => [ 'check1', 'check2' ] ] + $extOpts
		);
		$this->assertSame( '@bat$', $v['keyB'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated due to check keys' );
		$time = $cache->getCheckKeyTime( 'check1' );
		$this->assertGreaterThanOrEqual( $t1, $time, 'Check key 1 was autocreated' );
		$time = $cache->getCheckKeyTime( 'check2' );
		$this->assertGreaterThanOrEqual( $t1, $time, 'Check key 2 was autocreated' );

		$mockWallClock++;
		$t2 = $mockWallClock;

		// C: Repeat case with recently created check keys
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyC' => 'cat' ] ),
			30,
			$genFunc,
			[ 'checkKeys' => [ 'check1', 'check2' ] ] + $extOpts
		);
		$this->assertSame( '@cat$', $v['keyC'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated due to cache miss' );
		$time = $cache->getCheckKeyTime( 'check1' );
		$this->assertLessThanOrEqual( $t1, $time, 'Check key 1 did not change' );
		$time = $cache->getCheckKeyTime( 'check2' );
		$this->assertLessThanOrEqual( $t1, $time, 'Check key 2 did not change' );
		$curTTL = null;
		$v = $cache->get( 'keyC', $curTTL, [ 'check1', 'check2' ] );
		$this->assertSame( '@cat$', $v, 'Value returned' );
		$this->assertGreaterThan( 0, $curTTL, 'No hold-off for new check key (T344191)' );

		// Touch one of the check keys so that we have a hold-off period
		$mockWallClock++;
		$cache->touchCheckKey( 'check1' );
		$mockWallClock++;
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyC' => 'cat' ] ),
			30,
			$genFunc,
			[ 'checkKeys' => [ 'check1', 'check2' ] ] + $extOpts
		);
		$this->assertSame( '@cat$', $v['keyC'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated due to cache miss' );
		$curTTL = null;
		$v = $cache->get( 'keyC', $curTTL, [ 'check1', 'check2' ] );
		$this->assertSame( '@cat$', $v, 'Value returned' );
		$this->assertLessThanOrEqual( 0, $curTTL, 'Value is expired during hold-off from new check key' );

		// While the newly-generated value is considered expired on arrival during the
		// hold-off from the check key, it may still be used as valid for a second, until
		// the hold-off period is over.
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyC' => 'cat' ] ),
			30,
			$genFunc,
			[ 'checkKeys' => [ 'check1', 'check2' ] ] + $extOpts
		);
		$this->assertSame( '@cat$', $v['keyC'], 'Value returned' );
		$this->assertSame( 0, $wasSet, 'Value not regenerated within a second' );
		$mockWallClock++;
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			new ArrayIterator( [ 'keyC' => 'cat' ] ),
			30,
			$genFunc,
			[ 'checkKeys' => [ 'check1', 'check2' ] ] + $extOpts
		);
		$this->assertSame( '@cat$', $v['keyC'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated due to check key hold-off' );

		// D: Process cache should return recently deleted value
		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ 'keyD' => 'derk' ] );
		$v = $cache->getMultiWithUnionSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( '@derk$', $v['keyD'], 'Value returned' );
		$this->assertSame( 1, $wasSet, 'Value regenerated due to cache miss' );

		$cache->delete( 'keyD' );
		$wasSet = 0;
		$v = $cache->getMultiWithUnionSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertSame( '@derk$', $v['keyD'], 'Value returned from process cache' );
		$this->assertSame( 0, $wasSet, 'Value not regenerated' );

		$ids = [ 2, 6, 4, 7 ];
		$keyedIds = $cache->makeMultiKeys( $ids, static function ( $id, WANObjectCache $cache ) {
			return $cache->makeKey( 'test', $id );
		} );
		$wasSet = 0;
		$genFunc = static function ( array $ids, array &$ttls, array &$setOpts ) use ( &$wasSet ) {
			$newValues = [];
			foreach ( $ids as $id ) {
				$wasSet++;
				$newValues[$id] = ( $id <= 6 ) ? "val-{$id}" : false;
			}
			return $newValues;
		};

		$values = $cache->getMultiWithUnionSetCallback( $keyedIds, 10, $genFunc );
		$this->assertSame( [ 'val-2', 'val-6', 'val-4', false ], array_values( $values ), 'Values in order' );
		$this->assertSame(
			array_keys( iterator_to_array( $keyedIds ) ),
			array_keys( $values ),
			'Correct keys in correct order'
		);
		$this->assertSame( 4, $wasSet, 'Values generated' );

		$wasSet = 0;
		$cache->getMultiWithUnionSetCallback( $keyedIds, 10, $genFunc );
		$this->assertSame( [ 'val-2', 'val-6', 'val-4', false ], array_values( $values ), 'Values in order' );
		$this->assertSame( 1, $wasSet, 'Values not regenerated, except for the missing item 7' );
	}

	public static function provideCoalesceAndMcrouterSettings() {
		return [
			[ [ 'coalesceScheme' => 'hash_tag' ], '{' ],
			[ [ 'broadcastRoutingPrefix' => '/*/test/', 'coalesceScheme' => 'hash_stop' ], '|#|' ],
		];
	}

	/**
	 * @dataProvider getMultiWithSetCallbackRefreshProvider
	 * @param bool $expiring
	 * @param bool $popular
	 * @param array $idsByKey
	 */
	public function testGetMultiWithUnionSetCallbackRefresh( $expiring, $popular, array $idsByKey ) {
		$deferredCbs = [];
		$bag = new HashBagOStuff();
		$cache = $this->getMockBuilder( WANObjectCache::class )
			->onlyMethods( [ 'worthRefreshExpiring', 'worthRefreshPopular' ] )
			->setConstructorArgs( [
				[
					'cache' => $bag,
					'asyncHandler' => static function ( $callback ) use ( &$deferredCbs ) {
						$deferredCbs[] = $callback;
					}
				]
			] )
			->getMock();

		$cache->method( 'worthRefreshExpiring' )->willReturn( $expiring );
		$cache->method( 'worthRefreshPopular' )->willReturn( $popular );

		$wasSet = 0;
		$keyedIds = new ArrayIterator( $idsByKey );
		$genFunc = static function ( array $ids, array &$ttls, array &$setOpts ) use ( &$wasSet ) {
			$newValues = [];
			foreach ( $ids as $id ) {
				++$wasSet;
				$newValues[$id] = "@$id$";
				$ttls[$id] = 20; // override with another value
			}

			return $newValues;
		};

		$v = $cache->getMultiWithUnionSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame( count( $idsByKey ), $wasSet, "Initial sets" );
		$this->assertSame( [], $deferredCbs, "No deferred callbacks yet" );
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Initial cache value generation" );
		}

		$preemptiveRefresh = ( $expiring || $popular );
		$v = $cache->getMultiWithUnionSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame( count( $idsByKey ), $wasSet, "Deferred callbacks did not run yet" );
		$this->assertCount(
			$preemptiveRefresh ? count( $idsByKey ) : 0,
			$deferredCbs,
			"Deferred callbacks queued"
		);
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Cached value reused; refresh scheduled" );
		}

		// Run the deferred callbacks...
		$deferredCbsReady = $deferredCbs;
		$deferredCbs = []; // empty by-reference queue
		foreach ( $deferredCbsReady as $deferredCb ) {
			$deferredCb();
		}

		$this->assertSame(
			count( $idsByKey ) * ( $preemptiveRefresh ? 2 : 1 ),
			$wasSet,
			"Deferred callback regenerations"
		);
		$this->assertSame( [], $deferredCbs, "Deferred callbacks queue empty" );

		$v = $cache->getMultiWithUnionSetCallback( $keyedIds, 30, $genFunc );
		$this->assertSame(
			count( $idsByKey ) * ( $preemptiveRefresh ? 2 : 1 ),
			$wasSet,
			"Deferred callbacks did not run again yet"
		);
		foreach ( $idsByKey as $key => $id ) {
			$this->assertSame( "@$id$", $v[$key], "Cached value OK after deferred refresh run" );
		}
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testLockTSE( array $params ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$key = wfRandomString();
		$value = wfRandomString();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$calls = 0;
		$func = static function () use ( &$calls, $value ) {
			++$calls;
			return $value;
		};

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 1, $calls, 'Value was populated' );

		// Acquire the mutex to verify that getWithSetCallback uses lockTSE properly
		$this->setMutexKey( $bag, $key );

		$checkKeys = [ wfRandomString() ]; // new check keys => force misses
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Old value used' );
		$this->assertSame( 1, $calls, 'Callback was not used' );

		$cache->delete( $key ); // no value at all anymore and still locked

		$mockWallClock += 0.001; // cached values will be newer than tombstone
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Callback was used; interim saved' );
		$this->assertSame( 2, $calls, 'Callback was used; interim saved' );

		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Callback was not used; used interim (mutex failed)' );
		$this->assertSame( 2, $calls, 'Callback was not used; used interim (mutex failed)' );
	}

	private function setMutexKey( BagOStuff $bag, $key ) {
		// Cover all formats for "coalesceScheme"
		$bag->add( "WANCache:$key|#|m", 1 );
		$bag->add( "WANCache:{" . $key . "}:m", 1 );
	}

	private function clearMutexKey( BagOStuff $bag, $key ) {
		// Cover all formats for "coalesceScheme"
		$bag->delete( "WANCache:$key|#|m" );
		$bag->delete( "WANCache:{" . $key . "}:m" );
	}

	private function setCheckKey( BagOStuff $bag, $key, $time ) {
		// Cover all formats for "coalesceScheme"
		$bag->set( "WANCache:$key|#|t", "PURGED:$time" );
		$bag->set( "WANCache:{" . $key . "}:t", "PURGED:$time" );
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testLockTSESlow( array $params ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$key = 'myfirstkey';
		$key2 = 'mysecondkey';
		$value = 'some_slow_value';

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$calls = 0;
		$lastCallOldValue = null;
		$func = static function ( $oldValue, &$ttl, &$setOpts ) use (
			&$calls, $value, &$mockWallClock, &$lastCallOldValue
		) {
			++$calls;
			$lastCallOldValue = $oldValue;
			// Value should be given a low logical TTL due to high snapshot lag
			$setOpts['since'] = $mockWallClock;
			$mockWallClock += 10;
			return $value;
		};

		$curTTL = null;
		$ret = $cache->getWithSetCallback( $key, 300, $func, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( $value, $cache->get( $key, $curTTL ), 'Value populated' );
		$this->assertEqualsWithDelta( 30.0, $curTTL, 0.01, 'Value has reduced logical TTL' );
		$this->assertSame( 1, $calls, 'Value was generated' );
		$this->assertSame( false, $lastCallOldValue, 'No old value for callback' );

		// Just a few seconds after the (reduced) logical TTL expires
		$mockWallClock += 32;

		$ret = $cache->getWithSetCallback( $key, 300, $func, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 2, $calls, 'Callback used (stale, mutex acquired, regenerated)' );
		$this->assertSame( $value, $lastCallOldValue, 'Old value for callback' );

		$ret = $cache->getWithSetCallback( $key, 300, $func, [ 'lockTSE' => 5, 'lowTTL' => -1 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 2, $calls, 'Callback not used (extremely new value reused)' );

		// Just a few seconds after the (reduced) logical TTL expires
		$mockWallClock += 32;
		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->setMutexKey( $bag, $key );

		$ret = $cache->getWithSetCallback( $key, 300, $func, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 2, $calls, 'Callback not used (mutex not acquired, stale value used)' );

		$mockWallClock += 301; // physical TTL expired
		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->setMutexKey( $bag, $key );

		$ret = $cache->getWithSetCallback( $key, 300, $func, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 3, $calls, 'Callback was used (mutex not acquired, not in cache)' );

		$calls = 0;
		$func2 = static function ( $oldValue, &$ttl, &$setOpts ) use ( &$calls, $value ) {
			++$calls;
			$setOpts['lag'] = 15;
			return $value;
		};

		// Value should be given a low logical TTL due to replication lag
		$curTTL = null;
		$ret = $cache->getWithSetCallback( $key2, 300, $func2, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( $value, $cache->get( $key2, $curTTL ), 'Value was populated' );
		$this->assertSame( 30.0, $curTTL, 'Value has reduced logical TTL', 0.01 );
		$this->assertSame( 1, $calls, 'Value was generated' );

		$ret = $cache->getWithSetCallback( $key2, 300, $func2, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 1, $calls, 'Callback was used (not expired)' );

		$mockWallClock += 31;

		$ret = $cache->getWithSetCallback( $key2, 300, $func2, [ 'lockTSE' => 5 ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 2, $calls, 'Callback was used (mutex acquired)' );
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testBusyValueBasic( array $params ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$key = wfRandomString();
		$value = wfRandomString();
		$busyValue = wfRandomString();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$calls = 0;
		$func = static function () use ( &$calls, $value ) {
			++$calls;
			return $value;
		};

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'busyValue' => $busyValue ] );
		$this->assertSame( $value, $ret );
		$this->assertSame( 1, $calls, 'Value was populated' );

		$mockWallClock += 0.2; // interim keys not brand new

		// Acquire a lock to verify that getWithSetCallback uses busyValue properly
		$this->setMutexKey( $bag, $key );

		$checkKeys = [ wfRandomString() ]; // new check keys => force misses
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Callback used' );
		$this->assertSame( 2, $calls, 'Callback used' );

		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 30, 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Old value used' );
		$this->assertSame( 2, $calls, 'Callback was not used' );

		$cache->delete( $key ); // no value at all anymore and still locked

		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $busyValue, $ret, 'Callback was not used; used busy value' );
		$this->assertSame( 2, $calls, 'Callback was not used; used busy value' );

		$this->clearMutexKey( $bag, $key );
		$mockWallClock += 0.001; // cached values will be newer than tombstone
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 30, 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Callback was used; saved interim' );
		$this->assertSame( 3, $calls, 'Callback was used; saved interim' );

		$this->setMutexKey( $bag, $key );
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertSame( $value, $ret, 'Callback was not used; used interim' );
		$this->assertSame( 3, $calls, 'Callback was not used; used interim' );
	}

	public static function getBusyValuesProvider() {
		$hash = new HashBagOStuff( [] );

		return [
			[
				static function () {
					return "Saint Oliver Plunckett";
				},
				'Saint Oliver Plunckett'
			],
			[ 'strlen', 'strlen' ],
			[ 'WANObjectCache::newEmpty', 'WANObjectCache::newEmpty' ],
			[ [ 'WANObjectCache', 'newEmpty' ], [ 'WANObjectCache', 'newEmpty' ] ],
			[ [ $hash, 'getLastError' ], [ $hash, 'getLastError' ] ],
			[ [ 1, 2, 3 ], [ 1, 2, 3 ] ]
		];
	}

	/**
	 * @dataProvider getBusyValuesProvider
	 */
	public function testBusyValueTypes( $busyValue, $expected ) {
		[ $cache, $bag ] = $this->newWanCache();
		$key = wfRandomString();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$calls = 0;
		$func = static function () use ( &$calls ) {
			++$calls;
			return 418;
		};

		// Acquire a lock to verify that getWithSetCallback uses busyValue properly
		$this->setMutexKey( $bag, $key );

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'busyValue' => $busyValue ] );
		$this->assertSame( $expected, $ret, 'busyValue used as expected' );
		$this->assertSame( 0, $calls, 'busyValue was used' );
	}

	public function testGetMulti() {
		[ $cache ] = $this->newWanCache();

		$value1 = [ 'this' => 'is', 'a' => 'test' ];
		$value2 = [ 'this' => 'is', 'another' => 'test' ];

		$key1 = wfRandomString();
		$key2 = wfRandomString();
		$key3 = wfRandomString();

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time
		$cache->setMockTime( $mockWallClock );

		$cache->set( $key1, $value1, 5 );
		$cache->set( $key2, $value2, 10 );

		$curTTLs = [];
		$this->assertSame(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs ),
			'Result array populated'
		);

		$this->assertCount( 2, $curTTLs, "Two current TTLs in array" );
		$this->assertGreaterThan( 0, $curTTLs[$key1], "Key 1 has current TTL > 0" );
		$this->assertGreaterThan( 0, $curTTLs[$key2], "Key 2 has current TTL > 0" );

		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$mockWallClock++;

		$cache->touchCheckKey( $cKey1 );
		$cache->touchCheckKey( $cKey2 );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertSame( $mockWallClock, $t1, 'Check key 1 generated' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertSame( $mockWallClock, $t2, 'Check key 2 generated' );

		$curTTLs = [];
		$this->assertSame(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs, [ $cKey1, $cKey2 ] ),
			"Result array populated even with new check keys"
		);
		$this->assertCount( 2, $curTTLs, "Current TTLs array set" );
		$this->assertLessThanOrEqual( 0, $curTTLs[$key1], 'Key 1 has current TTL <= 0' );
		$this->assertLessThanOrEqual( 0, $curTTLs[$key2], 'Key 2 has current TTL <= 0' );

		$mockWallClock++;

		$curTTLs = [];
		$this->assertSame(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs, [ $cKey1, $cKey2 ] ),
			"Result array still populated even with new check keys"
		);
		$this->assertCount( 2, $curTTLs, "Current TTLs still array set" );
		$this->assertLessThan( 0, $curTTLs[$key1], 'Key 1 has negative current TTL' );
		$this->assertLessThan( 0, $curTTLs[$key2], 'Key 2 has negative current TTL' );
	}

	/**
	 * @param array $params
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testGetMultiCheckKeys( array $params ) {
		[ $cache ] = $this->newWanCache( $params );

		$checkAll = wfRandomString();
		$check1 = wfRandomString();
		$check2 = wfRandomString();
		$check3 = wfRandomString();
		$value1 = wfRandomString();
		$value2 = wfRandomString();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		// Fake initial check key to be set in the past. Otherwise we'd have to sleep for
		// several seconds during the test to assert the behaviour.
		foreach ( [ $checkAll, $check1, $check2 ] as $checkKey ) {
			$cache->touchCheckKey( $checkKey, WANObjectCache::HOLDOFF_TTL_NONE );
		}

		$mockWallClock += 0.100;

		$cache->set( 'key1', $value1, 10 );
		$cache->set( 'key2', $value2, 10 );

		$curTTLs = [];
		$result = $cache->getMulti( [ 'key1', 'key2', 'key3' ], $curTTLs, [
			'key1' => $check1,
			$checkAll,
			'key2' => $check2,
			'key3' => $check3,
		] );
		$this->assertSame(
			[ 'key1' => $value1, 'key2' => $value2 ],
			$result,
			'Initial values'
		);
		$this->assertGreaterThanOrEqual( 9.5, $curTTLs['key1'], 'Initial ttls' );
		$this->assertLessThanOrEqual( 10.5, $curTTLs['key1'], 'Initial ttls' );
		$this->assertGreaterThanOrEqual( 9.5, $curTTLs['key2'], 'Initial ttls' );
		$this->assertLessThanOrEqual( 10.5, $curTTLs['key2'], 'Initial ttls' );

		$mockWallClock += 0.100;
		$cache->touchCheckKey( $check1 );

		$curTTLs = [];
		$result = $cache->getMulti( [ 'key1', 'key2', 'key3' ], $curTTLs, [
			'key1' => $check1,
			$checkAll,
			'key2' => $check2,
			'key3' => $check3,
		] );
		$this->assertSame(
			[ 'key1' => $value1, 'key2' => $value2 ],
			$result,
			'key1 expired by check1, but value still provided'
		);
		$this->assertLessThan( 0, $curTTLs['key1'], 'key1 TTL expired' );
		$this->assertGreaterThan( 0, $curTTLs['key2'], 'key2 still valid' );

		$cache->touchCheckKey( $checkAll );

		$curTTLs = [];
		$result = $cache->getMulti( [ 'key1', 'key2', 'key3' ], $curTTLs, [
			'key1' => $check1,
			$checkAll,
			'key2' => $check2,
			'key3' => $check3,
		] );
		$this->assertSame(
			[ 'key1' => $value1, 'key2' => $value2 ],
			$result,
			'All keys expired by checkAll, but value still provided'
		);
		$this->assertLessThan( 0, $curTTLs['key1'], 'key1 expired by checkAll' );
		$this->assertLessThan( 0, $curTTLs['key2'], 'key2 expired by checkAll' );
	}

	public function testCheckKeyHoldoff() {
		[ $cache ] = $this->newWanCache();
		$key = wfRandomString();
		$checkKey = wfRandomString();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );
		$cache->touchCheckKey( $checkKey, 8 );

		$mockWallClock++;
		$cache->set( $key, 1, 60 );
		$this->assertSame( 1, $cache->get( $key, $curTTL, [ $checkKey ] ) );
		$this->assertLessThan( 0, $curTTL, "Key in hold-off due to check key" );

		$mockWallClock += 3;
		$cache->set( $key, 1, 60 );
		$this->assertSame( 1, $cache->get( $key, $curTTL, [ $checkKey ] ) );
		$this->assertLessThan( 0, $curTTL, "Key in hold-off due to check key" );

		$mockWallClock += 10;
		$cache->set( $key, 1, 60 );
		$this->assertSame( 1, $cache->get( $key, $curTTL, [ $checkKey ] ) );
		$this->assertGreaterThan( 0, $curTTL, "Key not in hold-off due to check key" );
	}

	public function testDelete() {
		[ $cache ] = $this->newWanCache();
		$key = wfRandomString();
		$value = wfRandomString();
		$cache->set( $key, $value );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL );
		$this->assertSame( $value, $v, "Key was created with value" );
		$this->assertGreaterThan( 0, $curTTL, "Existing key has current TTL > 0" );

		$cache->delete( $key );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL );
		$this->assertSame( false, $v, "Deleted key has false value" );
		$this->assertLessThan( 0, $curTTL, "Deleted key has current TTL < 0" );

		$cache->set( $key, $value . 'more' );
		$v = $cache->get( $key, $curTTL );
		$this->assertSame( false, $v, "Deleted key is tombstoned and has false value" );
		$this->assertLessThan( 0, $curTTL, "Deleted key is tombstoned and has current TTL < 0" );

		$cache->set( $key, $value );
		$cache->delete( $key, WANObjectCache::HOLDOFF_TTL_NONE );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL );
		$this->assertSame( false, $v, "Deleted key has false value" );
		$this->assertSame( null, $curTTL, "Deleted key has null current TTL" );

		$cache->set( $key, $value );
		$v = $cache->get( $key, $curTTL );
		$this->assertSame( $value, $v, "Key was created with value" );
		$this->assertGreaterThan( 0, $curTTL, "Existing key has current TTL > 0" );
	}

	/**
	 * @dataProvider getWithSetCallbackProvider
	 * @param array $extOpts
	 * @param bool $versioned
	 */
	public function testGetWithSetCallback_versions( array $extOpts, $versioned ) {
		[ $cache ] = $this->newWanCache();

		$key = wfRandomString();
		$valueV1 = wfRandomString();
		$valueV2 = [ wfRandomString() ];

		$wasSet = 0;
		$funcV1 = static function () use ( &$wasSet, $valueV1 ) {
			++$wasSet;

			return $valueV1;
		};

		$priorValue = false;
		$priorAsOf = null;
		$funcV2 = static function ( $oldValue, &$ttl, $setOpts, $oldAsOf )
		use ( &$wasSet, $valueV2, &$priorValue, &$priorAsOf ) {
			$priorValue = $oldValue;
			$priorAsOf = $oldAsOf;
			++$wasSet;

			return $valueV2; // new array format
		};

		// Set the main key (version N if versioned)
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $funcV1, $extOpts );
		$this->assertSame( $valueV1, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );
		$cache->getWithSetCallback( $key, 30, $funcV1, $extOpts );
		$this->assertSame( 1, $wasSet, "Value not regenerated" );
		$this->assertSame( $valueV1, $v, "Value not regenerated" );

		if ( $versioned ) {
			// Set the key for version N+1 format
			$verOpts = [ 'version' => $extOpts['version'] + 1 ];
		} else {
			// Start versioning now with the unversioned key still there
			$verOpts = [ 'version' => 1 ];
		}

		// Value goes to secondary key since V1 already used $key
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $funcV2, $verOpts + $extOpts );
		$this->assertSame( $valueV2, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );
		$this->assertSame( false, $priorValue, "Old value not given due to old format" );
		$this->assertSame( null, $priorAsOf, "Old value not given due to old format" );

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $funcV2, $verOpts + $extOpts );
		$this->assertSame( $valueV2, $v, "Value not regenerated (secondary key)" );
		$this->assertSame( 0, $wasSet, "Value not regenerated (secondary key)" );

		// Clear out the older or unversioned key
		$cache->delete( $key, 0 );

		// Set the key for next/first versioned format
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $funcV2, $verOpts + $extOpts );
		$this->assertSame( $valueV2, $v, "Value returned" );
		$this->assertSame( 1, $wasSet, "Value regenerated" );

		$v = $cache->getWithSetCallback( $key, 30, $funcV2, $verOpts + $extOpts );
		$this->assertSame( $valueV2, $v, "Value not regenerated (main key)" );
		$this->assertSame( 1, $wasSet, "Value not regenerated (main key)" );
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testInterimHoldOffCaching( array $params ) {
		[ $cache, $bag ] = $this->newWanCache( $params );

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$value = 'CRL-40-940';
		$wasCalled = 0;
		$func = static function () use ( &$wasCalled, $value ) {
			$wasCalled++;

			return $value;
		};

		$cache->useInterimHoldOffCaching( true );

		$key = wfRandomString( 32 );
		$cache->getWithSetCallback( $key, 60, $func );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 1, $wasCalled, 'Value cached' );

		$cache->delete( $key ); // no value at all anymore and still locked

		$mockWallClock++; // cached values will be newer than tombstone
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 2, $wasCalled, 'Value regenerated (got mutex)' ); // sets interim
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 2, $wasCalled, 'Value interim cached' ); // reuses interim

		$mockWallClock++; // interim key not brand new
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 3, $wasCalled, 'Value regenerated (got mutex)' ); // sets interim
		// Lock up the mutex so interim cache is used
		$this->setMutexKey( $bag, $key );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 3, $wasCalled, 'Value interim cached (failed mutex)' );
		$this->clearMutexKey( $bag, $key );

		$cache->useInterimHoldOffCaching( false );

		$wasCalled = 0;
		$key = wfRandomString( 32 );
		$cache->getWithSetCallback( $key, 60, $func );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 1, $wasCalled, 'Value cached' );

		$cache->delete( $key ); // no value at all anymore and still locked

		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 2, $wasCalled, 'Value regenerated (got mutex)' );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 3, $wasCalled, 'Value still regenerated (got mutex)' );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 4, $wasCalled, 'Value still regenerated (got mutex)' );
		// Lock up the mutex so interim cache is used
		$this->setMutexKey( $bag, $key );
		$cache->getWithSetCallback( $key, 60, $func );
		$this->assertSame( 5, $wasCalled, 'Value still regenerated (failed mutex)' );
	}

	public function testTouchKeys() {
		[ $cache ] = $this->newWanCache();
		$key = wfRandomString();

		$mockWallClock = 1549343530.0;
		$priorTime = floor( $mockWallClock ); // reference time
		$cache->setMockTime( $mockWallClock );

		$t0 = $cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t0, 'Check key auto-created' );

		$mockWallClock += 1.100;
		$priorTime = floor( $mockWallClock );
		$cache->touchCheckKey( $key );
		$t1 = $cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check key created' );

		$mockWallClock += 1.100;
		$t2 = $cache->getCheckKeyTime( $key );
		$this->assertSame( $t1, $t2, 'Check key time did not change' );

		$mockWallClock += 1.100;
		$cache->touchCheckKey( $key );
		$t3 = $cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t2, $t3, 'Check key time increased' );

		$mockWallClock += 1.100;
		$t4 = $cache->getCheckKeyTime( $key );
		$this->assertSame( $t3, $t4, 'Check key time did not change' );

		$mockWallClock += 1.100;
		$cache->resetCheckKey( $key );
		$t5 = $cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t4, $t5, 'Check key time increased' );

		$mockWallClock += 1.100;
		$t6 = $cache->getCheckKeyTime( $key );
		$this->assertSame( $t5, $t6, 'Check key time did not change' );
	}

	/**
	 * @param array $params
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 */
	public function testGetWithSeveralCheckKeys( array $params ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$key = wfRandomString();
		$tKey1 = wfRandomString();
		$tKey2 = wfRandomString();
		$value = 'meow';

		$mockWallClock = 1549343530.0;
		$priorTime = $mockWallClock; // reference time
		$cache->setMockTime( $mockWallClock );

		// Two check keys are newer (given hold-off) than $key, another is older
		$this->setCheckKey( $bag, $tKey2, $priorTime - 3 );
		$this->setCheckKey( $bag, $tKey2, $priorTime - 5 );
		$this->setCheckKey( $bag, $tKey1, $priorTime - 30 );
		$cache->set( $key, $value, 30 );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL, [ $tKey1, $tKey2 ] );
		$this->assertSame( $value, $v, "Value matches" );
		$this->assertLessThan( -4.9, $curTTL, "Correct CTL" );
		$this->assertGreaterThan( -5.1, $curTTL, "Correct CTL" );
	}

	public function testSetWithLag() {
		[ $cache ] = $this->newWanCache();

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$v = 1;

		$key = wfRandomString();
		$opts = [ 'lag' => 300, 'since' => $mockWallClock, 'walltime' => 0.1 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( $v, $cache->get( $key ), "Repl-lagged value written." );

		$key = wfRandomString();
		$opts = [ 'lag' => 300, 'since' => $mockWallClock ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( $v, $cache->get( $key ), "Repl-lagged value written (no walltime)." );

		$key = wfRandomString();
		$cache->get( $key );
		$mockWallClock += 15;
		$opts = [ 'lag' => 300, 'since' => $mockWallClock ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( $v, $cache->get( $key ), "Repl-lagged value written (auto-walltime)." );

		$key = wfRandomString();
		$opts = [ 'lag' => 0, 'since' => $mockWallClock - 300, 'walltime' => 0.1 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( false, $cache->get( $key ), "Trx-lagged value written." );

		$key = wfRandomString();
		$opts = [ 'lag' => 0, 'since' => $mockWallClock - 300 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( $v, $cache->get( $key ), "Trx-lagged value written (no walltime)." );

		$key = wfRandomString();
		$cache->get( $key );
		$mockWallClock += 15;
		$opts = [ 'lag' => 0, 'since' => $mockWallClock - 300 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( false, $cache->get( $key ), "Trx-lagged value not written (auto-walltime)." );

		$key = wfRandomString();
		$opts = [ 'lag' => 5, 'since' => $mockWallClock - 5, 'walltime' => 0.1 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( false, $cache->get( $key ), "Trx-lagged value written." );

		$key = wfRandomString();
		$opts = [ 'lag' => 3, 'since' => $mockWallClock - 3 ];
		$cache->set( $key, $v, 30, $opts );
		$this->assertSame( $v, $cache->get( $key ), "Lagged value written (no walltime)." );
	}

	public function testWritePending() {
		[ $cache ] = $this->newWanCache();
		$value = 1;

		$key = wfRandomString();
		$opts = [ 'pending' => true ];
		$cache->set( $key, $value, 30, $opts );
		$this->assertSame( false, $cache->get( $key ), "Pending value not written." );
	}

	public function testMcRouterSupport() {
		$localBag = $this->getMockBuilder( EmptyBagOStuff::class )
			->onlyMethods( [ 'set', 'delete' ] )->getMock();
		$localBag->expects( $this->never() )->method( 'set' );
		$localBag->expects( $this->never() )->method( 'delete' );
		$wanCache = new WANObjectCache( [
			'cache' => $localBag,
			'broadcastRoutingPrefix' => '/*/mw-wan/',
		] );
		$valFunc = static function () {
			return 1;
		};

		// None of these should use broadcasting commands (e.g. SET, DELETE)
		$wanCache->get( 'x' );
		$wanCache->get( 'x', $ctl, [ 'check1' ] );
		$wanCache->getMulti( [ 'x', 'y' ] );
		$wanCache->getMulti( [ 'x', 'y' ], $ctls, [ 'check2' ] );
		$wanCache->getWithSetCallback( 'p', 30, $valFunc );
		$wanCache->getCheckKeyTime( 'zzz' );
	}

	public function testMcRouterSupportBroadcastDelete() {
		$localBag = $this->getMockBuilder( EmptyBagOStuff::class )
			->onlyMethods( [ 'set' ] )->getMock();
		$wanCache = new WANObjectCache( [
			'cache' => $localBag,
			'broadcastRoutingPrefix' => '/*/mw-wan/',
		] );

		$localBag->expects( $this->once() )->method( 'set' )
			->with( "/*/mw-wan/WANCache:test|#|v" );

		$wanCache->delete( 'test' );
	}

	public function testMcRouterSupportBroadcastTouchCK() {
		$localBag = $this->getMockBuilder( EmptyBagOStuff::class )
			->onlyMethods( [ 'set' ] )->getMock();
		$wanCache = new WANObjectCache( [
			'cache' => $localBag,
			'broadcastRoutingPrefix' => '/*/mw-wan/',
		] );

		$localBag->expects( $this->once() )->method( 'set' )
			->with( "/*/mw-wan/WANCache:test|#|t" );

		$wanCache->touchCheckKey( 'test' );
	}

	public function testMcRouterSupportBroadcastResetCK() {
		$localBag = $this->getMockBuilder( EmptyBagOStuff::class )
			->onlyMethods( [ 'delete' ] )->getMock();
		$wanCache = new WANObjectCache( [
			'cache' => $localBag,
			'broadcastRoutingPrefix' => '/*/mw-wan/',
		] );

		$localBag->expects( $this->once() )->method( 'delete' )
			->with( "/*/mw-wan/WANCache:test|#|t" );

		$wanCache->resetCheckKey( 'test' );
	}

	public function testEpoch() {
		$bag = new HashBagOStuff();
		$cache = new WANObjectCache( [ 'cache' => $bag ] );
		$key = $cache->makeGlobalKey( 'The whole of the Law' );

		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );

		$cache->set( $key, 'Do what thou Wilt' );
		$cache->touchCheckKey( $key );

		$then = $mockWallClock;
		$mockWallClock += 30;
		$this->assertSame( 'Do what thou Wilt', $cache->get( $key ) );
		$this->assertEqualsWithDelta(
			$then,
			$cache->getCheckKeyTime( $key ),
			0.01,
			'Check key init'
		);

		$cache = new WANObjectCache( [
			'cache' => $bag,
			'epoch' => $mockWallClock - 3600
		] );
		$cache->setMockTime( $mockWallClock );

		$this->assertSame( 'Do what thou Wilt', $cache->get( $key ) );
		$this->assertEqualsWithDelta(
			$then,
			$cache->getCheckKeyTime( $key ),
			0.01,
			'Check key kept'
		);

		$mockWallClock += 30;
		$cache = new WANObjectCache( [
			'cache' => $bag,
			'epoch' => $mockWallClock + 3600
		] );
		$cache->setMockTime( $mockWallClock );

		$this->assertSame( false, $cache->get( $key ), 'Key rejected due to epoch' );
		$this->assertEqualsWithDelta(
			$mockWallClock,
			$cache->getCheckKeyTime( $key ),
			0.01,
			'Check key reset'
		);
	}

	/**
	 * @dataProvider provideAdaptiveTTL
	 * @param float|int $ago
	 * @param int $maxTTL
	 * @param int $minTTL
	 * @param float $factor
	 * @param int $adaptiveTTL
	 */
	public function testAdaptiveTTL( $ago, $maxTTL, $minTTL, $factor, $adaptiveTTL ) {
		[ $cache ] = $this->newWanCache();
		$mtime = $ago ? time() - $ago : $ago;
		$margin = 5;
		$ttl = $cache->adaptiveTTL( $mtime, $maxTTL, $minTTL, $factor );

		$this->assertGreaterThanOrEqual( $adaptiveTTL - $margin, $ttl );
		$this->assertLessThanOrEqual( $adaptiveTTL + $margin, $ttl );

		$ttl = $cache->adaptiveTTL( (string)$mtime, $maxTTL, $minTTL, $factor );

		$this->assertGreaterThanOrEqual( $adaptiveTTL - $margin, $ttl );
		$this->assertLessThanOrEqual( $adaptiveTTL + $margin, $ttl );
	}

	public static function provideAdaptiveTTL() {
		return [
			[ 3600, 900, 30, 0.2, 720 ],
			[ 3600, 500, 30, 0.2, 500 ],
			[ 3600, 86400, 800, 0.2, 800 ],
			[ false, 86400, 800, 0.2, 800 ],
			[ null, 86400, 800, 0.2, 800 ]
		];
	}

	public function testNewEmpty() {
		$this->assertInstanceOf(
			WANObjectCache::class,
			WANObjectCache::newEmpty()
		);
	}

	public function testSetLogger() {
		[ $cache ] = $this->newWanCache();
		$this->assertSame( null, $cache->setLogger( new NullLogger ) );
	}

	public function testGetQoS() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'getQoS' ] )->getMock();
		$backend->expects( $this->once() )->method( 'getQoS' )
			->willReturn( BagOStuff::QOS_UNKNOWN );
		$wanCache = new WANObjectCache( [ 'cache' => $backend ] );

		$this->assertSame(
			BagOStuff::QOS_UNKNOWN,
			$wanCache->getQoS( BagOStuff::ATTR_DURABILITY )
		);
	}

	public function testMakeKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeKey' ] )->getMock();
		$backend->expects( $this->once() )->method( 'makeKey' )
			->willReturn( 'special' );

		$wanCache = new WANObjectCache( [
			'cache' => $backend
		] );

		$this->assertSame( 'special', $wanCache->makeKey( 'a', 'b' ) );
	}

	public function testMakeGlobalKey() {
		$backend = $this->getMockBuilder( HashBagOStuff::class )
			->onlyMethods( [ 'makeGlobalKey' ] )->getMock();
		$backend->expects( $this->once() )->method( 'makeGlobalKey' )
			->willReturn( 'special' );

		$wanCache = new WANObjectCache( [
			'cache' => $backend
		] );

		$this->assertSame( 'special', $wanCache->makeGlobalKey( 'a', 'b' ) );
	}

	public static function statsKeyProvider() {
		return [
			[ 'domain:page:5', 'page' ],
			[ 'domain:main-key', 'main-key' ],
			[ 'domain:page:history', 'page' ],
			// Regression test for T232907
			[ 'domain:foo-bar-1.2:abc:v2', 'foo-bar-1_2' ],
			[ 'missingdomainkey', 'missingdomainkey' ]
		];
	}

	/**
	 * @dataProvider statsKeyProvider
	 * @param string $key
	 * @param string $class
	 */
	public function testStatsKeyClass( $key, $class ) {
		/** @var WANObjectCache $wanCache */
		$wanCache = TestingAccessWrapper::newFromObject( new WANObjectCache( [
			'cache' => new HashBagOStuff
		] ) );

		$this->assertSame( $class, $wanCache->determineKeyGroupForStats( $key ) );
	}

	public function testMakeMultiKeys() {
		[ $cache ] = $this->newWanCache();

		$ids = [ 1, 2, 3, 4, 4, 5, 6, 6, 7, 7 ];
		$keyCallback = static function ( $id, WANObjectCache $cache ) {
			return $cache->makeKey( 'key', $id );
		};
		$keyedIds = $cache->makeMultiKeys( $ids, $keyCallback );

		$expected = [
			"local:key:1" => 1,
			"local:key:2" => 2,
			"local:key:3" => 3,
			"local:key:4" => 4,
			"local:key:5" => 5,
			"local:key:6" => 6,
			"local:key:7" => 7
		];
		$this->assertSame( $expected, iterator_to_array( $keyedIds ) );

		$ids = [ '1', '2', '3', '4', '4', '5', '6', '6', '7', '7' ];
		$keyCallback = static function ( $id, WANObjectCache $cache ) {
			return $cache->makeGlobalKey( 'key', $id, 'a', $id, 'b' );
		};
		$keyedIds = $cache->makeMultiKeys( $ids, $keyCallback );

		$expected = [
			"global:key:1:a:1:b" => '1',
			"global:key:2:a:2:b" => '2',
			"global:key:3:a:3:b" => '3',
			"global:key:4:a:4:b" => '4',
			"global:key:5:a:5:b" => '5',
			"global:key:6:a:6:b" => '6',
			"global:key:7:a:7:b" => '7'
		];
		$this->assertSame( $expected, iterator_to_array( $keyedIds ) );
	}

	public function testMakeMultiKeysIntString() {
		[ $cache ] = $this->newWanCache();
		$ids = [ 1, 2, 3, 4, '4', 5, 6, 6, 7, '7' ];
		$keyCallback = static function ( $id, WANObjectCache $cache ) {
			return $cache->makeGlobalKey( 'key', $id, 'a', $id, 'b' );
		};

		$keyedIds = $cache->makeMultiKeys( $ids, $keyCallback );

		$expected = [
			"global:key:1:a:1:b" => 1,
			"global:key:2:a:2:b" => 2,
			"global:key:3:a:3:b" => 3,
			"global:key:4:a:4:b" => 4,
			"global:key:5:a:5:b" => 5,
			"global:key:6:a:6:b" => 6,
			"global:key:7:a:7:b" => 7
		];
		$this->assertSame( $expected, iterator_to_array( $keyedIds ) );
	}

	public function testMakeMultiKeysLongId() {
		[ $wanCache ] = $this->newWanCache( [
			'cache' => new ShortKeyHashBagOStuff()
		] );
		$longX = str_repeat( 'x', 190 );

		$keyedIds = $wanCache->makeMultiKeys(
			[ 1, $longX, 3 ],
			static fn ( $id ) => $wanCache->makeKey( 'maybelong', $id )
		);
		$expected = [
			"local:maybelong:1" => 1,
			"local:maybelong:#8964c94cdfeedf6d13b9878a0ed0d32a531019e75b0d914d61ba8d23641a9d9b" => $longX,
			"local:maybelong:3" => 3
		];
		$this->assertSame( $expected, iterator_to_array( $keyedIds ) );
	}

	public function testMakeMultiKeysCollision() {
		[ $cache ] = $this->newWanCache();
		$ids = [ 1, 2, 3, 4, '4', 5, 6, 6, 7 ];

		$this->expectException( UnexpectedValueException::class );
		$cache->makeMultiKeys(
			$ids,
			static function ( $id ) {
				return "keymod:" . $id % 3;
			}
		);
	}

	public function testMultiRemap() {
		[ $cache ] = $this->newWanCache();

		$ids = [ 'a', 'b', 'c' ];
		$res = [ 'keyA' => 1, 'keyB' => 2, 'keyC' => 3 ];
		$this->assertSame(
			[ 'a' => 1, 'b' => 2, 'c' => 3 ],
			$cache->multiRemap( $ids, $res )
		);

		$ids = [ 'd', 'c' ];
		$res = [ 'keyD' => 40, 'keyC' => 30 ];
		$this->assertSame(
			[ 'd' => 40, 'c' => 30 ],
			$cache->multiRemap( $ids, $res )
		);
	}

	public function testHash256() {
		[ $cache ] = $this->newWanCache( [ 'epoch' => 5 ] );
		$this->assertEquals(
			'f402bce76bfa1136adc705d8d5719911ce1fe61f0ad82ddf79a15f3c4de6ec4c',
			$cache->hash256( 'x' )
		);

		[ $cache ] = $this->newWanCache( [ 'epoch' => 50 ] );
		$this->assertSame(
			'f79a126722f0a682c4c500509f1b61e836e56c4803f92edc89fc281da5caa54e',
			$cache->hash256( 'x' )
		);

		[ $cache ] = $this->newWanCache( [ 'secret' => 'garden' ] );
		$this->assertSame(
			'48cd57016ffe29981a1114c45e5daef327d30fc6206cb73edc3cb94b4d8fe093',
			$cache->hash256( 'x' )
		);

		[ $cache ] = $this->newWanCache( [ 'secret' => 'garden', 'epoch' => 3 ] );
		$this->assertSame(
			'48cd57016ffe29981a1114c45e5daef327d30fc6206cb73edc3cb94b4d8fe093',
			$cache->hash256( 'x' )
		);
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 * @param array $params
	 * @param string|null $keyNeedle
	 */
	public function testCoalesceKeys( array $params, $keyNeedle ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$key = wfRandomString();
		$callback = static function () {
			return 2020;
		};

		$cache->getWithSetCallback( $key, 60, $callback );
		$wrapper = TestingAccessWrapper::newFromObject( $bag );
		foreach ( $wrapper->bag as $bagKey => $_ ) {
			if ( $keyNeedle === null ) {
				$this->assertDoesNotMatchRegularExpression( '/[#{}]/', $bagKey, 'Respects "coalesceKeys"' );
			} else {
				$this->assertStringContainsString(
					$keyNeedle,
					$bagKey,
					'Respects "coalesceKeys"'
				);
			}
		}
	}

	/**
	 * @dataProvider provideCoalesceAndMcrouterSettings
	 * @param array $params
	 * @param string|null $keyNeedle
	 */
	public function testSegmentableValues( array $params, $keyNeedle ) {
		[ $cache, $bag ] = $this->newWanCache( $params );
		$mockWallClock = 1549343530.0;
		$cache->setMockTime( $mockWallClock );
		$key = $cache->makeGlobalKey( 'z', wfRandomString() );

		$tiny = 418;
		$small = wfRandomString( 32 );
		// 64 * 8 * 32768 = 16 MiB, which will trigger segmentation
		// assuming segmentationSize at default of 8 MiB.
		$big = str_repeat( wfRandomString( 32 ) . '-' . wfRandomString( 32 ), 32768 );

		$cases = [ 'tiny' => $tiny, 'small' => $small, 'big' => $big ];
		foreach ( $cases as $case => $value ) {
			$cache->set( $key, $value, 10, [ 'segmentable' => 1 ] );
			$this->assertEquals( $value, $cache->get( $key ), "get $case" );
			$this->assertEquals( [ $key => $value ], $cache->getMulti( [ $key ] ), "get $case" );

			$this->assertTrue( $cache->delete( $key ), "delete $case" );
			$this->assertFalse( $cache->get( $key ), "deleted $case" );
			$this->assertEquals( [], $cache->getMulti( [ $key ] ), "deleted $case" );
			$mockWallClock += 40;

			$v = $cache->getWithSetCallback(
				$key,
				10,
				static function ( $cache, $key, $oldValue ) use ( $value ) {
					return "@$value";
				},
				[ 'segmentable' => 1 ]
			);
			$this->assertEquals( "@$value", $v, "get $case" );
			$this->assertEquals( "@$value", $cache->get( $key ), "get $case" );

			$this->assertTrue(
				$cache->delete( $key ),
				"prune $case"
			);
			$this->assertFalse( $cache->get( $key ), "pruned $case" );
			$this->assertEquals( [], $cache->getMulti( [ $key ] ), "pruned $case" );
			$mockWallClock += 40;
		}
	}
}

class McrouterHashBagOStuff extends HashBagOStuff {
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		// Convert mcrouter broadcast keys to regular keys in HashBagOStuff::set() calls
		// https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup
		if ( preg_match( '#^/\*/[^/]+/(.*)$#', $key, $m ) ) {
			$key = $m[1];
		}

		return parent::set( $key, $value, $exptime, $flags );
	}

	public function delete( $key, $flags = 0 ) {
		// Convert mcrouter broadcast keys to regular keys in HashBagOStuff::delete() calls
		// https://github.com/facebook/mcrouter/wiki/Multi-cluster-broadcast-setup
		if ( preg_match( '#^/\*/[^/]+/(.*)$#', $key, $m ) ) {
			$key = $m[1];
		}

		return parent::delete( $key, $flags );
	}
}

class ShortKeyHashBagOStuff extends HashBagOStuff {
	protected function makeKeyInternal( $keyspace, $components ) {
		$key = parent::makeKeyInternal( $keyspace, $components );
		return $this->makeFallbackKey( $key, 205 );
	}
}

class NearExpiringWANObjectCache extends WANObjectCache {
	private const CLOCK_SKEW = 1;

	protected function worthRefreshExpiring( $curTTL, $logicalTTL, $lowTTL ) {
		return ( $curTTL > 0 && ( $curTTL + self::CLOCK_SKEW ) < $lowTTL );
	}
}

class PopularityRefreshingWANObjectCache extends WANObjectCache {
	protected function worthRefreshPopular( $asOf, $ageNew, $timeTillRefresh, $now ) {
		return ( ( $now - $asOf ) > $timeTillRefresh );
	}
}

class SerialHashBagOStuff extends HashBagOStuff {
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$serialized = parent::doGet( $key, $flags, $casToken );

		return ( $serialized !== false ) ? $this->unserialize( $serialized ) : false;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$serialized = $this->getSerialized( $value, $key );

		return parent::doSet( $key, $serialized, $exptime, $flags );
	}
}
