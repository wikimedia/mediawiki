<?php

class WANObjectCacheTest extends MediaWikiTestCase {
	/** @var WANObjectCache */
	private $cache;
	/**@var BagOStuff */
	private $internalCache;

	protected function setUp() {
		parent::setUp();

		if ( $this->getCliArg( 'use-wanobjectcache' ) ) {
			$name = $this->getCliArg( 'use-wanobjectcache' );

			$this->cache = ObjectCache::getWANInstance( $name );
		} else {
			$this->cache = new WANObjectCache( [
				'cache' => new HashBagOStuff(),
				'pool' => 'testcache-hash',
				'relayer' => new EventRelayerNull( [] )
			] );
		}

		$wanCache = TestingAccessWrapper::newFromObject( $this->cache );
		$this->internalCache = $wanCache->cache;
	}

	/**
	 * @dataProvider provideSetAndGet
	 * @covers WANObjectCache::set()
	 * @covers WANObjectCache::get()
	 * @param mixed $value
	 * @param integer $ttl
	 */
	public function testSetAndGet( $value, $ttl ) {
		$key = wfRandomString();
		$this->cache->set( $key, $value, $ttl );

		$curTTL = null;
		$this->assertEquals( $value, $this->cache->get( $key, $curTTL ) );
		if ( is_infinite( $ttl ) || $ttl == 0 ) {
			$this->assertTrue( is_infinite( $curTTL ), "Current TTL is infinite" );
		} else {
			$this->assertGreaterThan( 0, $curTTL, "Current TTL > 0" );
			$this->assertLessThanOrEqual( $ttl, $curTTL, "Current TTL < nominal TTL" );
		}
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
	 */
	public function testGetNotExists() {
		$key = wfRandomString();
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

	/**
	 * @covers WANObjectCache::getWithSetCallback()
	 * @covers WANObjectCache::doGetWithSetCallback()
	 */
	public function testGetWithSetCallback() {
		$cache = $this->cache;

		$key = wfRandomString();
		$value = wfRandomString();
		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();

		$wasSet = 0;
		$func = function( $old, &$ttl ) use ( &$wasSet, $value ) {
			++$wasSet;
			$ttl = 20; // override with another value
			return $value;
		};

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );

		$curTTL = null;
		$cache->get( $key, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overriden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overriden)' );

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func, [
			'lowTTL' => 0,
			'lockTSE' => 5,
		] );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 0, $wasSet, "Value not regenerated" );

		$priorTime = microtime( true );
		usleep( 1 );
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func,
			[ 'checkKeys' => [ $cKey1, $cKey2 ] ] );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$priorTime = microtime( true );
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, 30, $func,
			[ 'checkKeys' => [ $cKey1, $cKey2 ] ] );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL, [ $cKey1, $cKey2 ] );
		$this->assertEquals( $value, $v, "Value returned" );
		$this->assertLessThanOrEqual( 0, $curTTL, "Value has current TTL < 0 due to check keys" );

		$wasSet = 0;
		$key = wfRandomString();
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] );
		$this->assertEquals( $value, $v, "Value returned" );
		$cache->delete( $key );
		$v = $cache->getWithSetCallback( $key, 30, $func, [ 'pcTTL' => 5 ] );
		$this->assertEquals( $value, $v, "Value still returned after deleted" );
		$this->assertEquals( 1, $wasSet, "Value process cached while deleted" );
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
		$func = function() use ( &$calls, $value ) {
			++$calls;
			return $value;
		};

		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Value was populated' );

		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->internalCache->lock( $key, 0 );
		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Callback was not used' );
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
		$func = function( $oldValue, &$ttl, &$setOpts ) use ( &$calls, $value ) {
			++$calls;
			$setOpts['since'] = microtime( true ) - 10;
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
		$this->internalCache->lock( $key, 0 );
		$ret = $cache->getWithSetCallback( $key, 30, $func, [ 'lockTSE' => 5 ] );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Callback was not used' );
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
}
