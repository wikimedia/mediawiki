<?php

class WANObjectCacheTest extends MediaWikiTestCase {
	/** @var WANObjectCache */
	private $cache;

	protected function setUp() {
		parent::setUp();

		if ( $this->getCliArg( 'use-wanobjectcache' ) ) {
			$name = $this->getCliArg( 'use-wanobjectcache' );

			$this->cache = ObjectCache::getWANInstance( $name );
		} else {
			$this->cache = new WANObjectCache( array(
				'cache' => new HashBagOStuff(),
				'pool' => 'testcache-hash',
				'relayer' => new EventRelayerNull( array() )
			) );
		}

		$wanCache = TestingAccessWrapper::newFromObject( $this->cache );
		$this->internalCache = $wanCache->cache;
	}

	/**
	 * @dataProvider provider_testSetAndGet
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

	public static function provider_testSetAndGet() {
		return array(
			array( 14141, 3 ),
			array( 3535.666, 3 ),
			array( array(), 3 ),
			array( null, 3 ),
			array( '0', 3 ),
			array( (object)array( 'meow' ), 3 ),
			array( INF, 3 ),
			array( '', 3 ),
			array( 'pizzacat', INF ),
		);
	}

	public function testGetNotExists() {
		$key = wfRandomString();
		$curTTL = null;
		$value = $this->cache->get( $key, $curTTL );

		$this->assertFalse( $value, "Non-existing key has false value" );
		$this->assertNull( $curTTL, "Non-existing key has null current TTL" );
	}

	public function testSetOver() {
		$key = wfRandomString();
		for ( $i = 0; $i < 3; ++$i ) {
			$value = wfRandomString();
			$this->cache->set( $key, $value, 3 );

			$this->assertEquals( $this->cache->get( $key ), $value );
		}
	}

	/**
	 * @covers WANObjectCache::getWithSetCallback()
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
		$v = $cache->getWithSetCallback( $key, $func, 30, array(), array( 'lockTSE' => 5 ) );
		$this->assertEquals( $v, $value );
		$this->assertEquals( 1, $wasSet, "Value regenerated" );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL );
		$this->assertLessThanOrEqual( 20, $curTTL, 'Current TTL between 19-20 (overriden)' );
		$this->assertGreaterThanOrEqual( 19, $curTTL, 'Current TTL between 19-20 (overriden)' );

		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, $func, 30, array(), array( 'lockTSE' => 5 ) );
		$this->assertEquals( $v, $value );
		$this->assertEquals( 0, $wasSet, "Value not regenerated" );

		$priorTime = microtime( true );
		usleep( 1 );
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, $func, 30, array( $cKey1, $cKey2 ) );
		$this->assertEquals( $v, $value );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check keys generated on miss' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertGreaterThanOrEqual( $priorTime, $t2, 'Check keys generated on miss' );

		$priorTime = microtime( true );
		$wasSet = 0;
		$v = $cache->getWithSetCallback( $key, $func, 30, array( $cKey1, $cKey2 ) );
		$this->assertEquals( $v, $value );
		$this->assertEquals( 1, $wasSet, "Value regenerated due to still-recent check keys" );
		$t1 = $cache->getCheckKeyTime( $cKey1 );
		$this->assertLessThanOrEqual( $priorTime, $t1, 'Check keys did not change again' );
		$t2 = $cache->getCheckKeyTime( $cKey2 );
		$this->assertLessThanOrEqual( $priorTime, $t2, 'Check keys did not change again' );

		$curTTL = null;
		$v = $cache->get( $key, $curTTL, array( $cKey1, $cKey2 ) );
		$this->assertEquals( $v, $value );
		$this->assertLessThanOrEqual( 0, $curTTL, "Value has current TTL < 0 due to check keys" );
	}

	/**
	 * @covers WANObjectCache::getWithSetCallback()
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

		$cache->delete( $key );
		$ret = $cache->getWithSetCallback( $key, 30, $func, array(), array( 'lockTSE' => 5 ) );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Value was populated' );

		// Acquire a lock to verify that getWithSetCallback uses lockTSE properly
		$this->internalCache->lock( $key, 0 );
		$ret = $cache->getWithSetCallback( $key, 30, $func, array(), array( 'lockTSE' => 5 ) );
		$this->assertEquals( $value, $ret );
		$this->assertEquals( 1, $calls, 'Callback was not used' );
	}

	/**
	 * @covers WANObjectCache::getMulti()
	 */
	public function testGetMulti() {
		$cache = $this->cache;

		$value1 = array( 'this' => 'is', 'a' => 'test' );
		$value2 = array( 'this' => 'is', 'another' => 'test' );

		$key1 = wfRandomString();
		$key2 = wfRandomString();
		$key3 = wfRandomString();

		$cache->set( $key1, $value1, 5 );
		$cache->set( $key2, $value2, 10 );

		$curTTLs = array();
		$this->assertEquals(
			array( $key1 => $value1, $key2 => $value2 ),
			$cache->getMulti( array( $key1, $key2, $key3 ), $curTTLs )
		);

		$this->assertEquals( 2, count( $curTTLs ), "Two current TTLs in array" );
		$this->assertGreaterThan( 0, $curTTLs[$key1], "Key 1 has current TTL > 0" );
		$this->assertGreaterThan( 0, $curTTLs[$key2], "Key 2 has current TTL > 0" );

		$cKey1 = wfRandomString();
		$cKey2 = wfRandomString();
		$curTTLs = array();
		$this->assertEquals(
			array( $key1 => $value1, $key2 => $value2 ),
			$cache->getMulti( array( $key1, $key2, $key3 ), $curTTLs ),
			'Result array populated'
		);

		$priorTime = microtime( true );
		usleep( 1 );
		$curTTLs = array();
		$this->assertEquals(
			array( $key1 => $value1, $key2 => $value2 ),
			$cache->getMulti( array( $key1, $key2, $key3 ), $curTTLs, array( $cKey1, $cKey2 ) ),
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
		$curTTLs = array();
		$this->assertEquals(
			array( $key1 => $value1, $key2 => $value2 ),
			$cache->getMulti( array( $key1, $key2, $key3 ), $curTTLs, array( $cKey1, $cKey2 ) ),
			"Result array still populated even with new check keys"
		);
		$this->assertEquals( 2, count( $curTTLs ), "Current TTLs still array set" );
		$this->assertLessThan( 0, $curTTLs[$key1], 'Key 1 has negative current TTL' );
		$this->assertLessThan( 0, $curTTLs[$key2], 'Key 2 has negative current TTL' );
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
		$this->assertFalse( $v, "Deleted key is tombstoned and has false value" );
		$this->assertLessThan( 0, $curTTL, "Deleted key is tombstoned and has current TTL < 0" );
	}

	/**
	 * @covers WANObjectCache::touchCheckKey()
	 * @covers WANObjectCache::resetCheckKey()
	 * @covers WANObjectCache::getCheckKeyTime()
	 */
	public function testTouchKeys() {
		$key = wfRandomString();

		$priorTime = microtime( true );
		usleep( 1 );
		$t0 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t0, 'Check key auto-created' );

		$priorTime = microtime( true );
		usleep( 1 );
		$this->cache->touchCheckKey( $key );
		$t1 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThanOrEqual( $priorTime, $t1, 'Check key created' );

		$t2 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t1, $t2, 'Check key time did not change' );

		usleep( 1 );
		$this->cache->touchCheckKey( $key );
		$t3 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t2, $t3, 'Check key time increased' );

		$t4 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t3, $t4, 'Check key time did not change' );

		usleep( 1 );
		$this->cache->resetCheckKey( $key );
		$t5 = $this->cache->getCheckKeyTime( $key );
		$this->assertGreaterThan( $t4, $t5, 'Check key time increased' );

		$t6 = $this->cache->getCheckKeyTime( $key );
		$this->assertEquals( $t5, $t6, 'Check key time did not change' );
	}
}
