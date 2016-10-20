<?php

class WANObjectCacheTest extends PHPUnit_Framework_TestCase  {
	/** @var WANObjectCache */
	private $cache;
	/**@var BagOStuff */
	private $internalCache;

	protected function setUp() {
		parent::setUp();

		$this->cache = new WANObjectCache( [
			'cache' => new HashBagOStuff(),
			'pool' => 'testcache-hash',
			'relayer' => new EventRelayerNull( [] )
		] );

		$wanCache = TestingAccessWrapper::newFromObject( $this->cache );
		/** @noinspection PhpUndefinedFieldInspection */
		$this->internalCache = $wanCache->cache;
	}

	/**
	 * @dataProvider provideSetAndGet
	 * @covers WANObjectCache::set()
	 * @covers WANObjectCache::get()
	 * @covers WANObjectCache::makeKey()
	 * @param mixed $value
	 * @param integer $ttl
	 */
	public function testSetAndGet( $value, $ttl ) {
		$curTTL = null;
		$asOf = null;
		$key = $this->cache->makeKey( 'x', wfRandomString() );

		$this->cache->get( $key, $curTTL, [], $asOf );
		$this->assertNull( $curTTL, "Current TTL is null" );
		$this->assertNull( $asOf, "Current as-of-time is infinite" );

		$t = microtime( true );
		$this->cache->set( $key, $value, $ttl );

		$this->assertEquals( $value, $this->cache->get( $key, $curTTL, [], $asOf ) );
		if ( is_infinite( $ttl ) || $ttl == 0 ) {
			$this->assertTrue( is_infinite( $curTTL ), "Current TTL is infinite" );
		} else {
			$this->assertGreaterThan( 0, $curTTL, "Current TTL > 0" );
			$this->assertLessThanOrEqual( $ttl, $curTTL, "Current TTL < nominal TTL" );
		}
		$this->assertGreaterThanOrEqual( $t - 1, $asOf, "As-of-time in range of set() time" );
		$this->assertLessThanOrEqual( $t + 1, $asOf, "As-of-time in range of set() time" );
	}

	public static function provideSetAndGet() {
		return [
			[ 14141, 3 ],
			[ 3535.666, 3 ],
			[ [], 3 ],
			[ null, 3 ],
			[ '0', 3 ],
			[ (object)[ 'meow' ], 3 ],
			[ INF, 3 ],
			[ '', 3 ],
			[ 'pizzacat', INF ],
		];
	}

	/**
	 * @covers WANObjectCache::get()
	 * @covers WANObjectCache::makeGlobalKey()
	 */
	public function testGetNotExists() {
		$key = $this->cache->makeGlobalKey( 'y', wfRandomString(), 'p' );
		$curTTL = null;
		$value = $this->cache->get( $key, $curTTL );

		$this->assertFalse( $value, "Non-existing key has false value" );
		$this->assertNull( $curTTL, "Non-existing key has null current TTL" );
	}

	/**
	 * @covers WANObjectCache::set()
	 */
	public function testSetOver() {
		$key = wfRandomString();
		for ( $i = 0; $i < 3; ++$i ) {
			$value = wfRandomString();
			$this->cache->set( $key, $value, 3 );

			$this->assertEquals( $this->cache->get( $key ), $value );
		}
	}

	/**
	 * @covers WANObjectCache::set()
	 */
	public function testStaleSet() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->cache->set( $key, $value, 3, [ 'since' => microtime( true ) - 30 ] );

		$this->assertFalse( $this->cache->get( $key ), "Stale set() value ignored" );
	}

	public function testProcessCache() {
		$hit = 0;
		$callback = function () use ( &$hit ) {
			++$hit;
			return 42;
		};
		$keys = [ wfRandomString(), wfRandomString(), wfRandomString() ];
		$groups = [ 'thiscache:1', 'thatcache:1', 'somecache:1' ];

		foreach ( $keys as $i => $key ) {
			$this->cache->getWithSetCallback(
				$key, 100, $callback, [ 'pcTTL' => 5, 'pcGroup' => $groups[$i] ] );
		}
		$this->assertEquals( 3, $hit );

		foreach ( $keys as $i => $key ) {
			$this->cache->getWithSetCallback(
				$key, 100, $callback, [ 'pcTTL' => 5, 'pcGroup' => $groups[$i] ] );
		}
		$this->assertEquals( 3, $hit, "Values cached" );

		foreach ( $keys as $i => $key ) {
			$this->cache->getWithSetCallback(
				"$key-2", 100, $callback, [ 'pcTTL' => 5, 'pcGroup' => $groups[$i] ] );
		}
		$this->assertEquals( 6, $hit );

		foreach ( $keys as $i => $key ) {
			$this->cache->getWithSetCallback(
				"$key-2", 100, $callback, [ 'pcTTL' => 5, 'pcGroup' => $groups[$i] ] );
		}
		$this->assertEquals( 6, $hit, "New values cached" );

		foreach ( $keys as $i => $key ) {
			$this->cache->delete( $key );
			$this->cache->getWithSetCallback(
				$key, 100, $callback, [ 'pcTTL' => 5, 'pcGroup' => $groups[$i] ] );
		}
		$this->assertEquals( 9, $hit, "Values evicted" );

		$key = reset( $keys );
		// Get into cache
		$this->cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );
		$this->cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );
		$this->assertEquals( 10, $hit, "Value cached" );
		$outerCallback = function () use ( &$callback, $key ) {
			$v = $this->cache->getWithSetCallback( $key, 100, $callback, [ 'pcTTL' => 5 ] );

			return 43 + $v;
		};
		$this->cache->getWithSetCallback( $key, 100, $outerCallback );
		$this->assertEquals( 11, $hit, "Nested callback value process cache skipped" );
	}

	/**
	 * @dataProvider getWithSetCallback_provider
	 * @covers WANObjectCache::getWithSetCallback()
	 * @covers WANObjectCache::doGetWithSetCallback()
	 * @param array $extOpts
	 * @param bool $versioned
	 */
	public function testGetWithSetCallback( array $extOpts, $versioned ) {
		$cache = $this->cache;

		$key = wfRandomString();
		$value = wfRandomString();
		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$priorValue = null;
		$priorAsOf = null;
		$wasSet = 0;
		$func = function( $old, &$ttl, &$opts, $asOf )
		use ( &$wasSet, &$priorValue, &$priorAsOf, $value )
		{
			++$wasSet;
			$priorValue = $old;
			$priorAsOf = $asOf;
			$ttl = 20; // override with another value
			return $value;
		};

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] + $extOpts );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );
		$this->assertFalse( $priorValue, "No prior value" );
		$this->assertNull( $priorAsOf, "No prior value" );

		$curTTL = null;
		$cache->get( $key, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overriden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overriden)' );

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, [
				'lowTTL' => 0,
				'lockTSE' => 5,
			] + $extOpts );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 0, $wasSet, "Value not regenerated" );

		$priorTime = microtime( true );
		usleep( 1 );
		$wasSet = 0;
		$v = $cache->getWithSetCallback(
			$key, 30, $func, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to check keys" );
		$this->assertEquals( $value, $priorValue, "Has prior value" );
		$this->assertInternalType( 'float', $priorAsOf, "Has prior value" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$priorTime = microtime( true );
		$wasSet = 0;
		$v = $cache->getWithSetCallback(
			$key, 30, $func, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL, [ $cKey1, $cKey2 ] );
		if ( $versioned ) {
			$this->assertEquals( $value, $v[$cache::VFLD_DATA], "Value returned" );
		} else {
			$this->assertEquals( $value, $v, "Value returned" );
		}
		$this->assertLessThanOrEqual( 0, $curTTL, "Value has current TTL < 0 due to check keys" );

		$wasSet = 0;
		$key = wfRandomString();
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertEquals( $value, $v, "Value returned" );
		$cache->delete( $key );
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertEquals( $value, $v, "Value still returned after deleted" );
		$this->assertEquals( 1, $wasSet, "Value process cached while deleted" );
	}

	public static function getWithSetCallback_provider() {
		return [
			[ [], false ],
			[ [ 'version' => 1 ], true ]
		];
	}

	/**
	 * @dataProvider getMultiWithSetCallback_provider
	 * @covers WANObjectCache::getMultiWithSetCallback()
	 * @covers WANObjectCache::makeMultiKeys()
	 * @param array $extOpts
	 * @param bool $versioned
	 */
	public function testGetMultiWithSetCallback( array $extOpts, $versioned ) {
		$cache = $this->cache;

		$keyA = wfRandomString();
		$keyB = wfRandomString();
		$keyC = wfRandomString();
		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$priorValue = null;
		$priorAsOf = null;
		$wasSet = 0;
		$genFunc = function ( $id, $old, &$ttl, &$opts, $asOf ) use (
			&$wasSet, &$priorValue, &$priorAsOf
		) {
			++$wasSet;
			$priorValue = $old;
			$priorAsOf = $asOf;
			$ttl = 20; // override with another value
			return "@$id$";
		};

		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyA => 3353 ] );
		$value = "@3353$";
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lockTSE' => 5 ] + $extOpts );
		$this->assertEquals( $value, $v[$keyA], "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );
		$this->assertFalse( $priorValue, "No prior value" );
		$this->assertNull( $priorAsOf, "No prior value" );

		$curTTL = null;
		$cache->get( $keyA, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overriden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overriden)' );

		$wasSet = 0;
		$value = "@efef$";
		$keyedIds = new ArrayIterator( [ $keyB => 'efef' ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lowTTL' => 0, 'lockTSE' => 5, ] + $extOpts );
		$this->assertEquals( $value, $v[$keyB], "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'lowTTL' => 0, 'lockTSE' => 5, ] + $extOpts );
		$this->assertEquals( $value, $v[$keyB], "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value not regenerated" );

		$priorTime = microtime( true );
		usleep( 1 );
		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyB => 'efef' ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertEquals( $value, $v[$keyB], "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to check keys" );
		$this->assertEquals( $value, $priorValue, "Has prior value" );
		$this->assertInternalType( 'float', $priorAsOf, "Has prior value" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$priorTime = microtime( true );
		$value = "@43636$";
		$wasSet = 0;
		$keyedIds = new ArrayIterator( [ $keyC => 43636 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'checkKeys' => [ $cKey1, $cKey2 ] ] + $extOpts
		);
		$this->assertEquals( $value, $v[$keyC], "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $keyC, $curTTL, [ $cKey1, $cKey2 ] );
		if ( $versioned ) {
			$this->assertEquals( $value, $v[$cache::VFLD_DATA], "Value returned" );
		} else {
			$this->assertEquals( $value, $v, "Value returned" );
		}
		$this->assertLessThanOrEqual( 0, $curTTL, "Value has current TTL < 0 due to check keys" );

		$wasSet = 0;
		$key = wfRandomString();
		$keyedIds = new ArrayIterator( [ $key => 242424 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertEquals( "@{$keyedIds[$key]}$", $v[$key], "Value returned" );
		$cache->delete( $key );
		$keyedIds = new ArrayIterator( [ $key => 242424 ] );
		$v = $cache->getMultiWithSetCallback(
			$keyedIds, 30, $genFunc, [ 'pcTTL' => 5 ] + $extOpts );
		$this->assertEquals( "@{$keyedIds[$key]}$", $v[$key], "Value still returned after deleted" );
		$this->assertEquals( 1, $wasSet, "Value process cached while deleted" );

		$calls = 0;
		$ids = [ 1, 2, 3, 4, 5, 6 ];
		$keyFunc = function ( $id, WANObjectCache $wanCache ) {
			return $wanCache->makeKey( 'test', $id );
		};
		$keyedIds = $cache->makeMultiKeys( $ids, $keyFunc );
		$genFunc = function ( $id, $oldValue, &$ttl, array &$setops ) use ( &$calls ) {
			++$calls;

			return "val-{$id}";
		};
		$values = $cache->getMultiWithSetCallback( $keyedIds, 10, $genFunc );

		$this->assertEquals(
			[ "val-1", "val-2", "val-3", "val-4", "val-5", "val-6" ],
			array_values( $values ),
			"Correct values in correct order"
		);
		$this->assertEquals(
			array_map( $keyFunc, $ids, array_fill( 0, count( $ids ), $this->cache ) ),
			array_keys( $values ),
			"Correct keys in correct order"
		);
		$this->assertEquals( count( $ids ), $calls );

		$cache->getMultiWithSetCallback( $keyedIds, 10, $genFunc );
		$this->assertEquals( count( $ids ), $calls, "Values cached" );
	}

	public static function getMultiWithSetCallback_provider() {
		return [
			[ [], false ],
			[ [ 'version' => 1 ], true ]
		];
	}

	/**
	 * @covers WANObjectCache::getWithSetCallback()
	 * @covers WANObjectCache::doGetWithSetCallback()
	 */
	public function testLockTSE() {
		$cache = $this->cache;
		$key = wfRandomString();
		$value = wfRandomString();

		$calls = 0;
		$func = function() use ( &$calls, $value, $cache, $key ) {
			++$calls;
			// Immediately kill any mutex rather than waiting a second
			$cache->delete( $cache::MUTEX_KEY_PREFIX . $key );
			return $value;
		};

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Value was populated' );

		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->internalCache->add( $cache::MUTEX_KEY_PREFIX . $key, 1, 0 );

		$checkKeys = [ wfRandomString() ]; // new check keys => force misses
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Old value used' );
		$this->assertEquals( 1, $calls, 'Callback was not used' );

		$cache->delete( $key );
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Callback was used; interim saved' );
		$this->assertEquals( 2, $calls, 'Callback was used; interim saved' );

		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 5, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Callback was not used; used interim' );
		$this->assertEquals( 2, $calls, 'Callback was not used; used interim' );
	}

	/**
	 * @covers WANObjectCache::getWithSetCallback()
	 * @covers WANObjectCache::doGetWithSetCallback()
	 */
	public function testLockTSESlow() {
		$cache = $this->cache;
		$key = wfRandomString();
		$value = wfRandomString();

		$calls = 0;
		$func = function( $oldValue, &$ttl, &$setOpts ) use ( &$calls, $value, $cache, $key ) {
			++$calls;
			$setOpts['since'] = microtime( true ) - 10;
			// Immediately kill any mutex rather than waiting a second
			$cache->delete( $cache::MUTEX_KEY_PREFIX . $key );
			return $value;
		};

		// Value should be marked as stale due to snapshot lag
		$curTTL = null;
		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( $value, $cache->get( $key, $curTTL ), 'Value was populated' );
		$this->assertLessThan( 0, $curTTL, 'Value has negative curTTL' );
		$this->assertEquals( 1, $calls, 'Value was generated' );

		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->internalCache->add( $cache::MUTEX_KEY_PREFIX . $key, 1, 0 );
		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Callback was not used' );
	}

	/**
	 * @covers WANObjectCache::getWithSetCallback()
	 * @covers WANObjectCache::doGetWithSetCallback()
	 */
	public function testBusyValue() {
		$cache = $this->cache;
		$key = wfRandomString();
		$value = wfRandomString();
		$busyValue = wfRandomString();

		$calls = 0;
		$func = function() use ( &$calls, $value, $cache, $key ) {
			++$calls;
			// Immediately kill any mutex rather than waiting a second
			$cache->delete( $cache::MUTEX_KEY_PREFIX . $key );
			return $value;
		};

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'busyValue' => $busyValue ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Value was populated' );

		// Acquire a lock to verify that getWithSetCallback uses busyValue properly
		$this->internalCache->add( $cache::MUTEX_KEY_PREFIX . $key, 1, 0 );

		$checkKeys = [ wfRandomString() ]; // new check keys => force misses
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Callback used' );
		$this->assertEquals( 2, $calls, 'Callback used' );

		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 30, 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Old value used' );
		$this->assertEquals( 2, $calls, 'Callback was not used' );

		$cache->delete( $key ); // no value at all anymore and still locked
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $busyValue, $ret, 'Callback was not used; used busy value' );
		$this->assertEquals( 2, $calls, 'Callback was not used; used busy value' );

		$this->internalCache->delete( $cache::MUTEX_KEY_PREFIX . $key );
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'lockTSE' => 30, 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Callback was used; saved interim' );
		$this->assertEquals( 3, $calls, 'Callback was used; saved interim' );

		$this->internalCache->add( $cache::MUTEX_KEY_PREFIX . $key, 1, 0 );
		$ret = $cache->getWithSetCallback( $key, 30, $func,
			[ 'busyValue' => $busyValue, 'checkKeys' => $checkKeys ] );
		$this->assertEquals( $value, $ret, 'Callback was not used; used interim' );
		$this->assertEquals( 3, $calls, 'Callback was not used; used interim' );
	}

	/**
	 * @covers WANObjectCache::getMulti()
	 */
	public function testGetMulti() {
		$cache = $this->cache;

		$value1 = [ 'this' => 'is', 'a' => 'test' ];
		$value2 = [ 'this' => 'is', 'another' => 'test' ];

		$key1 = wfRandomString();
		$key2 = wfRandomString();
		$key3 = wfRandomString();

		$cache->set( $key1, $value1, 5 );
		$cache->set( $key2, $value2, 10 );

		$curTTLs = [];
		$this->assertEquals(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs ),
			'Result array populated'
		);

		$this->assertEquals( 2, count( $curTTLs ), "Two current TTLs in array" );
		$this->assertGreaterThan( 0, $curTTLs[$key1], "Key 1 has current TTL > 0" );
		$this->assertGreaterThan( 0, $curTTLs[$key2], "Key 2 has current TTL > 0" );

		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$priorTime = microtime( true );
		usleep( 1 );
		$curTTLs = [];
		$this->assertEquals(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs, [ $cKey1, $cKey2 ] ),
			"Result array populated even with new check keys"
		);
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check key 1 generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check key 2 generated on miss' );
		$this->assertEquals( 2, count( $curTTLs ), "Current TTLs array set" );
		$this->assertLessThanOrEqual( 0, $curTTLs[$key1], 'Key 1 has current TTL <= 0' );
		$this->assertLessThanOrEqual( 0, $curTTLs[$key2], 'Key 2 has current TTL <= 0' );

		usleep( 1 );
		$curTTLs = [];
		$this->assertEquals(
			[ $key1 => $value1, $key2 => $value2 ],
			$cache->getMulti( [ $key1, $key2, $key3 ], $curTTLs, [ $cKey1, $cKey2 ] ),
			"Result array still populated even with new check keys"
		);
		$this->assertEquals( 2, count( $curTTLs ), "Current TTLs still array set" );
		$this->assertLessThan( 0, $curTTLs[$key1], 'Key 1 has negative current TTL' );
		$this->assertLessThan( 0, $curTTLs[$key2], 'Key 2 has negative current TTL' );
	}

	/**
	 * @covers WANObjectCache::getMulti()
	 * @covers WANObjectCache::processCheckKeys()
	 */
	public function testGetMultiCheckKeys() {
		$cache = $this->cache;

		$checkAll = wfRandomString();
		$check1 = wfRandomString();
		$check2 = wfRandomString();
		$check3 = wfRandomString();
		$value1 = wfRandomString();
		$value2 = wfRandomString();

		// Fake initial check key to be set in the past. Otherwise we'd have to sleep for
		// several seconds during the test to assert the behaviour.
		foreach ( [ $checkAll, $check1, $check2 ] as $checkKey ) {
			$cache->touchCheckKey( $checkKey, WANObjectCache::HOLDOFF_NONE );
		}
		usleep( 100 );

		$cache->set( 'key1', $value1, 10 );
		$cache->set( 'key2', $value2, 10 );

		$curTTLs = [];
		$result = $cache->getMulti( [ 'key1', 'key2', 'key3' ], $curTTLs, [
			'key1' => $check1,
			$checkAll,
			'key2' => $check2,
			'key3' => $check3,
		] );
		$this->assertEquals(
			[ 'key1' => $value1, 'key2' => $value2 ],
			$result,
			'Initial values'
		);
		$this->assertGreaterThanOrEqual( 9.5, $curTTLs['key1'], 'Initial ttls' );
		$this->assertLessThanOrEqual( 10.5, $curTTLs['key1'], 'Initial ttls' );
		$this->assertGreaterThanOrEqual( 9.5, $curTTLs['key2'], 'Initial ttls' );
		$this->assertLessThanOrEqual( 10.5, $curTTLs['key2'], 'Initial ttls' );

		$cache->touchCheckKey( $check1 );

		$curTTLs = [];
		$result = $cache->getMulti( [ 'key1', 'key2', 'key3' ], $curTTLs, [
			'key1' => $check1,
			$checkAll,
			'key2' => $check2,
			'key3' => $check3,
		] );
		$this->assertEquals(
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
		$this->assertEquals(
			[ 'key1' => $value1, 'key2' => $value2 ],
			$result,
			'All keys expired by checkAll, but value still provided'
		);
		$this->assertLessThan( 0, $curTTLs['key1'], 'key1 expired by checkAll' );
		$this->assertLessThan( 0, $curTTLs['key2'], 'key2 expired by checkAll' );
	}

	/**
	 * @covers WANObjectCache::get()
	 * @covers WANObjectCache::processCheckKeys()
	 */
	public function testCheckKeyInitHoldoff() {
		$cache = $this->cache;

		for ( $i = 0; $i < 500; ++$i ) {
			$key = wfRandomString();
			$checkKey = wfRandomString();
			// miss, set, hit
			$cache->get( $key, $curTTL, [ $checkKey ] );
			$cache->set( $key, 'val', 10 );
			$curTTL = null;
			$v = $cache->get( $key, $curTTL, [ $checkKey ] );

			$this->assertEquals( 'val', $v );
			$this->assertLessThan( 0, $curTTL, "Step $i: CTL < 0 (miss/set/hit)" );
		}

		for ( $i = 0; $i < 500; ++$i ) {
			$key = wfRandomString();
			$checkKey = wfRandomString();
			// set, hit
			$cache->set( $key, 'val', 10 );
			$curTTL = null;
			$v = $cache->get( $key, $curTTL, [ $checkKey ] );

			$this->assertEquals( 'val', $v );
			$this->assertLessThan( 0, $curTTL, "Step $i: CTL < 0 (set/hit)" );
		}
	}

	/**
	 * @covers WANObjectCache::delete()
	 */
	public function testDelete() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->cache->set( $key, $value );

		$curTTL = null;
		$v = $this->cache->get( $key, $curTTL );
		$this->assertEquals( $value, $v, "Key was created with value" );
		$this->assertGreaterThan( 0, $curTTL, "Existing key has current TTL > 0" );

		$this->cache->delete( $key );

		$curTTL = null;
		$v = $this->cache->get( $key, $curTTL );
		$this->assertFalse( $v, "Deleted key has false value" );
		$this->assertLessThan( 0, $curTTL, "Deleted key has current TTL < 0" );

		$this->cache->set( $key, $value . 'more' );
		$v = $this->cache->get( $key, $curTTL );
		$this->assertFalse( $v, "Deleted key is tombstoned and has false value" );
		$this->assertLessThan( 0, $curTTL, "Deleted key is tombstoned and has current TTL < 0" );

		$this->cache->set( $key, $value );
		$this->cache->delete( $key, WANObjectCache::HOLDOFF_NONE );

		$curTTL = null;
		$v = $this->cache->get( $key, $curTTL );
		$this->assertFalse( $v, "Deleted key has false value" );
		$this->assertNull( $curTTL, "Deleted key has null current TTL" );

		$this->cache->set( $key, $value );
		$v = $this->cache->get( $key, $curTTL );
		$this->assertEquals( $value, $v, "Key was created with value" );
		$this->assertGreaterThan( 0, $curTTL, "Existing key has current TTL > 0" );
	}

	/**
	 * @dataProvider getWithSetCallback_versions_provider
	 * @param array $extOpts
	 * @param $versioned
	 */
	public function testGetWithSetCallback_versions( array $extOpts, $versioned ) {
		$cache = $this->cache;

		$key = wfRandomString();
		$value = wfRandomString();

		$wasSet = 0;
		$func = function( $old, &$ttl ) use ( &$wasSet, $value ) {
			++$wasSet;
			return $value;
		};

		// Set the main key (version N if versioned)
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, $extOpts );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );
		$cache->getWithSetCallback( $key, 30, $func, $extOpts );
		$this->assertEquals( 1, $wasSet, "Value not regenerated" );
		// Set the key for version N+1 (if versioned)
		if ( $versioned ) {
			$verOpts = [ 'version' => $extOpts['version'] + 1 ];

			$wasSet = 0;
			$v = $cache->getWithSetCallback( $key, 30, $func, $verOpts + $extOpts );
			$this->assertEquals( $value, $v, "Value returned" );
			$this->assertEquals( 1, $wasSet, "Value regenerated" );

			$wasSet = 0;
			$v = $cache->getWithSetCallback( $key, 30, $func, $verOpts + $extOpts );
			$this->assertEquals( $value, $v, "Value returned" );
			$this->assertEquals( 0, $wasSet, "Value not regenerated" );
		}

		$wasSet = 0;
		$cache->getWithSetCallback( $key, 30, $func, $extOpts );
		$this->assertEquals( 0, $wasSet, "Value not regenerated" );

		$wasSet = 0;
		$cache->delete( $key );
		$v = $cache->getWithSetCallback( $key, 30, $func, $extOpts );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );

		if ( $versioned ) {
			$wasSet = 0;
			$verOpts = [ 'version' => $extOpts['version'] + 1 ];
			$v = $cache->getWithSetCallback( $key, 30, $func, $verOpts + $extOpts );
			$this->assertEquals( $value, $v, "Value returned" );
			$this->assertEquals( 1, $wasSet, "Value regenerated" );
		}
	}

	public static function getWithSetCallback_versions_provider() {
		return [
			[ [], false ],
			[ [ 'version' => 1 ], true ]
		];
	}

	/**
	 * @covers WANObjectCache::touchCheckKey()
	 * @covers WANObjectCache::resetCheckKey()
	 * @covers WANObjectCache::getCheckKeyTime()
	 */
	public function testTouchKeys() {
		$key = wfRandomString();

		$priorTime = microtime( true );
		usleep( 100 );
		$t0 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t0, 'Check key auto-created' );

		$priorTime = microtime( true );
		usleep( 100 );
		$this->cache->touchCheckKey( $key );
		$t1 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check key created' );

		$t2 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t1, $t2, 'Check key time did not change' );

		usleep( 100 );
		$this->cache->touchCheckKey( $key );
		$t3 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t2, $t3, 'Check key time increased' );

		$t4 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t3, $t4, 'Check key time did not change' );

		usleep( 100 );
		$this->cache->resetCheckKey( $key );
		$t5 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t4, $t5, 'Check key time increased' );

		$t6 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t5, $t6, 'Check key time did not change' );
	}

	/**
	 * @covers WANObjectCache::getMulti()
	 */
	public function testGetWithSeveralCheckKeys() {
		$key = wfRandomString();
		$tKey1 = wfRandomString();
		$tKey2 = wfRandomString();
		$value = 'meow';

		// Two check keys are newer (given hold-off) than $key, another is older
		$this->internalCache->set(
			WANObjectCache::TIME_KEY_PREFIX . $tKey2,
			WANObjectCache::PURGE_VAL_PREFIX . ( microtime( true ) - 3 )
		);
		$this->internalCache->set(
			WANObjectCache::TIME_KEY_PREFIX . $tKey2,
			WANObjectCache::PURGE_VAL_PREFIX . ( microtime( true ) - 5 )
		);
		$this->internalCache->set(
			WANObjectCache::TIME_KEY_PREFIX . $tKey1,
			WANObjectCache::PURGE_VAL_PREFIX . ( microtime( true ) - 30 )
		);
		$this->cache->set( $key, $value, 30 );

		$curTTL = null;
		$v = $this->cache->get( $key, $curTTL, [ $tKey1, $tKey2 ] );
		$this->assertEquals( $value, $v, "Value matches" );
		$this->assertLessThan( -4.9, $curTTL, "Correct CTL" );
		$this->assertGreaterThan( -5.1, $curTTL, "Correct CTL" );
	}

	/**
	 * @covers WANObjectCache::set()
	 */
	public function testSetWithLag() {
		$value = 1;

		$key = wfRandomString();
		$opts = [ 'lag' => 300, 'since' => microtime( true ) ];
		$this->cache->set( $key, $value, 30, $opts );
		$this->assertEquals( $value, $this->cache->get( $key ), "Rep-lagged value written." );

		$key = wfRandomString();
		$opts = [ 'lag' => 0, 'since' => microtime( true ) - 300 ];
		$this->cache->set( $key, $value, 30, $opts );
		$this->assertEquals( false, $this->cache->get( $key ), "Trx-lagged value not written." );

		$key = wfRandomString();
		$opts = [ 'lag' => 5, 'since' => microtime( true ) - 5 ];
		$this->cache->set( $key, $value, 30, $opts );
		$this->assertEquals( false, $this->cache->get( $key ), "Lagged value not written." );
	}

	/**
	 * @covers WANObjectCache::set()
	 */
	public function testWritePending() {
		$value = 1;

		$key = wfRandomString();
		$opts = [ 'pending' => true ];
		$this->cache->set( $key, $value, 30, $opts );
		$this->assertEquals( false, $this->cache->get( $key ), "Pending value not written." );
	}

	public function testMcRouterSupport() {
		$localBag = $this->getMock( 'EmptyBagOStuff', [ 'set', 'delete' ] );
		$localBag->expects( $this->never() )->method( 'set' );
		$localBag->expects( $this->never() )->method( 'delete' );
		$wanCache = new WANObjectCache( [
			'cache' => $localBag,
			'pool' => 'testcache-hash',
			'relayer' => new EventRelayerNull( [] )
		] );
		$valFunc = function () {
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

	/**
	 * @dataProvider provideAdaptiveTTL
	 * @covers WANObjectCache::adaptiveTTL()
	 * @param float|int $ago
	 * @param int $maxTTL
	 * @param int $minTTL
	 * @param float $factor
	 * @param int $adaptiveTTL
	 */
	public function testAdaptiveTTL( $ago, $maxTTL, $minTTL, $factor, $adaptiveTTL ) {
		$mtime = $ago ? time() - $ago : $ago;
		$margin = 5;
		$ttl = $this->cache->adaptiveTTL( $mtime, $maxTTL, $minTTL, $factor );

		$this->assertGreaterThanOrEqual( $adaptiveTTL - $margin, $ttl );
		$this->assertLessThanOrEqual( $adaptiveTTL + $margin, $ttl );

		$ttl = $this->cache->adaptiveTTL( (string)$mtime, $maxTTL, $minTTL, $factor );

		$this->assertGreaterThanOrEqual( $adaptiveTTL - $margin, $ttl );
		$this->assertLessThanOrEqual( $adaptiveTTL + $margin, $ttl );
	}

	public static function provideAdaptiveTTL() {
		return [
			[ 3600, 900, 30, .2, 720 ],
			[ 3600, 500, 30, .2, 500 ],
			[ 3600, 86400, 800, .2, 800 ],
			[ false, 86400, 800, .2, 800 ],
			[ null, 86400, 800, .2, 800 ]
		];
	}
}
