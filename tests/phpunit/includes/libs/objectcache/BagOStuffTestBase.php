<?php

use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Matthias Mullie <mmullie@wikimedia.org>
 * @group BagOStuff
 * @covers BagOStuff
 */
abstract class BagOStuffTestBase extends MediaWikiIntegrationTestCase {
	/** @var BagOStuff */
	private $cache;

	private const TEST_KEY = 'test';

	protected function setUp() : void {
		parent::setUp();

		$this->cache = $this->newCacheInstance();

		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) );
		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) . ':lock' );
	}

	/**
	 * @return BagOStuff
	 */
	abstract protected function newCacheInstance();

	/**
	 * @covers MediumSpecificBagOStuff::makeGlobalKey
	 * @covers MediumSpecificBagOStuff::makeKeyInternal
	 */
	public function testMakeKey() {
		$cache = new HashBagOStuff();

		$localKey = $cache->makeKey( 'first', 'second', 'third' );
		$globalKey = $cache->makeGlobalKey( 'first', 'second', 'third' );

		$this->assertStringMatchesFormat(
			'%Sfirst%Ssecond%Sthird%S',
			$localKey,
			'Local key interpolates parameters'
		);

		$this->assertStringMatchesFormat(
			'global%Sfirst%Ssecond%Sthird%S',
			$globalKey,
			'Global key interpolates parameters and contains global prefix'
		);

		$this->assertNotEquals(
			$localKey,
			$globalKey,
			'Local key and global key with same parameters should not be equal'
		);

		$this->assertNotEquals(
			$cache->makeKeyInternal( 'prefix', [ 'a', 'bc:', 'de' ] ),
			$cache->makeKeyInternal( 'prefix', [ 'a', 'bc', ':de' ] )
		);
	}

	/**
	 * @covers MediumSpecificBagOStuff::merge
	 * @covers MediumSpecificBagOStuff::mergeViaCas
	 */
	public function testMerge() {
		$key = $this->cache->makeKey( self::TEST_KEY );

		$calls = 0;
		$casRace = false; // emulate a race
		$callback = function ( BagOStuff $cache, $key, $oldVal, &$expiry ) use ( &$calls, &$casRace ) {
			++$calls;
			if ( $casRace ) {
				// Uses CAS instead?
				$cache->set( $key, 'conflict', 5 );
			}

			return ( $oldVal === false ) ? 'merged' : $oldVal . 'merged';
		};

		// merge on non-existing value
		$merged = $this->cache->merge( $key, $callback, 5 );
		$this->assertTrue( $merged );
		$this->assertEquals( 'merged', $this->cache->get( $key ) );

		// merge on existing value
		$merged = $this->cache->merge( $key, $callback, 5 );
		$this->assertTrue( $merged );
		$this->assertEquals( 'mergedmerged', $this->cache->get( $key ) );

		$calls = 0;
		$casRace = true;
		$this->assertFalse(
			$this->cache->merge( $key, $callback, 5, 1 ),
			'Non-blocking merge (CAS)'
		);

		if ( $this->cache instanceof MultiWriteBagOStuff ) {
			$wrapper = TestingAccessWrapper::newFromObject( $this->cache );
			$this->assertEquals( count( $wrapper->caches ), $calls );
		} else {
			$this->assertSame( 1, $calls );
		}
	}

	/**
	 * @covers MediumSpecificBagOStuff::changeTTL
	 */
	public function testChangeTTLRenew() {
		$now = microtime( true ); // need real time
		$this->cache->setMockTime( $now );

		$key = $this->cache->makeKey( self::TEST_KEY );
		$value = 'meow';

		$this->cache->add( $key, $value, 60 );
		$this->assertEquals( $value, $this->cache->get( $key ) );
		$this->assertTrue( $this->cache->changeTTL( $key, 120 ) );
		$this->assertTrue( $this->cache->changeTTL( $key, 120 ) );
		$this->assertTrue( $this->cache->changeTTL( $key, 0 ) );
		$this->assertEquals( $this->cache->get( $key ), $value );

		$this->cache->delete( $key );
		$this->assertFalse( $this->cache->changeTTL( $key, 15 ) );
	}

	/**
	 * @covers MediumSpecificBagOStuff::changeTTL
	 */
	public function testChangeTTLExpireRel() {
		$now = microtime( true ); // need real time
		$this->cache->setMockTime( $now );

		$key = $this->cache->makeKey( self::TEST_KEY );
		$value = 'meow';

		$this->cache->add( $key, $value, 5 );
		$this->assertTrue( $this->cache->changeTTL( $key, -3600 ) );
		$this->assertFalse( $this->cache->get( $key ) );
	}

	/**
	 * @covers MediumSpecificBagOStuff::changeTTL
	 */
	public function testChangeTTLExpireAbs() {
		$now = microtime( true ); // need real time
		$this->cache->setMockTime( $now );

		$key = $this->cache->makeKey( self::TEST_KEY );
		$value = 'meow';

		$this->cache->add( $key, $value, 5 );
		$this->assertTrue( $this->cache->changeTTL( $key, $now - 3600 ) );
		$this->assertFalse( $this->cache->get( $key ) );
	}

	/**
	 * @covers MediumSpecificBagOStuff::changeTTLMulti
	 */
	public function testChangeTTLMulti() {
		$now = 1563892142;
		$this->cache->setMockTime( $now );

		$key1 = $this->cache->makeKey( 'test-key1' );
		$key2 = $this->cache->makeKey( 'test-key2' );
		$key3 = $this->cache->makeKey( 'test-key3' );
		$key4 = $this->cache->makeKey( 'test-key4' );

		// cleanup
		$this->cache->deleteMulti( [ $key1, $key2, $key3, $key4 ] );

		$ok = $this->cache->changeTTLMulti( [ $key1, $key2, $key3 ], 30 );
		$this->assertFalse( $ok, "No keys found" );
		$this->assertFalse( $this->cache->get( $key1 ) );
		$this->assertFalse( $this->cache->get( $key2 ) );
		$this->assertFalse( $this->cache->get( $key3 ) );

		$ok = $this->cache->setMulti( [ $key1 => 1, $key2 => 2, $key3 => 3 ] );
		$this->assertTrue( $ok, "setMulti() succeeded" );
		$this->assertCount( 3, $this->cache->getMulti( [ $key1, $key2, $key3 ] ),
			"setMulti() succeeded via getMulti() check" );

		$ok = $this->cache->changeTTLMulti( [ $key1, $key2, $key3 ], 300 );
		$this->assertTrue( $ok, "TTL bumped for all keys" );
		$this->assertSame( 1, $this->cache->get( $key1 ) );
		$this->assertEquals( 2, $this->cache->get( $key2 ) );
		$this->assertEquals( 3, $this->cache->get( $key3 ) );

		$ok = $this->cache->changeTTLMulti( [ $key1, $key2, $key3, $key4 ], 300 );
		$this->assertFalse( $ok, "One key missing" );
		$this->assertSame( 1, $this->cache->get( $key1 ), "Key still live" );

		$now = microtime( true ); // real time
		$ok = $this->cache->setMulti( [ $key1 => 1, $key2 => 2, $key3 => 3 ] );
		$this->assertTrue( $ok, "setMulti() succeeded" );

		$ok = $this->cache->changeTTLMulti( [ $key1, $key2, $key3 ], $now + 86400 );
		$this->assertTrue( $ok, "Expiry set for all keys" );
		$this->assertSame( 1, $this->cache->get( $key1 ), "Key still live" );

		$this->assertEquals( 2, $this->cache->incr( $key1 ) );
		$this->assertEquals( 3, $this->cache->incr( $key2 ) );
		$this->assertEquals( 4, $this->cache->incr( $key3 ) );

		// cleanup
		$this->cache->deleteMulti( [ $key1, $key2, $key3, $key4 ] );
	}

	/**
	 * @covers MediumSpecificBagOStuff::add
	 */
	public function testAdd() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$this->assertFalse( $this->cache->get( $key ) );
		$this->assertTrue( $this->cache->add( $key, 'test', 5 ) );
		$this->assertFalse( $this->cache->add( $key, 'test', 5 ) );
	}

	/**
	 * @covers MediumSpecificBagOStuff::get
	 */
	public function testGet() {
		$value = [ 'this' => 'is', 'a' => 'test' ];

		$key = $this->cache->makeKey( self::TEST_KEY );
		$this->cache->add( $key, $value, 5 );
		$this->assertEquals( $this->cache->get( $key ), $value );
	}

	/**
	 * @covers MediumSpecificBagOStuff::get
	 * @covers MediumSpecificBagOStuff::set
	 * @covers MediumSpecificBagOStuff::getWithSetCallback
	 */
	public function testGetWithSetCallback() {
		$now = 1563892142;
		$cache = new HashBagOStuff( [] );
		$cache->setMockTime( $now );
		$key = $cache->makeKey( self::TEST_KEY );

		$this->assertFalse( $cache->get( $key ), "No value" );

		$value = $cache->getWithSetCallback(
			$key,
			30,
			function ( &$ttl ) {
				$ttl = 10;

				return 'hello kitty';
			}
		);

		$this->assertEquals( 'hello kitty', $value );
		$this->assertEquals( $value, $cache->get( $key ), "Value set" );

		$now += 11;

		$this->assertFalse( $cache->get( $key ), "Value expired" );
	}

	/**
	 * @covers MediumSpecificBagOStuff::incr
	 */
	public function testIncr() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$this->cache->add( $key, 0, 5 );
		$this->cache->incr( $key );
		$expectedValue = 1;
		$actualValue = $this->cache->get( $key );
		$this->assertEquals( $expectedValue, $actualValue, 'Value should be 1 after incrementing' );
	}

	/**
	 * @covers MediumSpecificBagOStuff::incrWithInit
	 */
	public function testIncrWithInit() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertEquals( 3, $val, "Correct init value" );

		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertEquals( 4, $val, "Correct init value" );
		$this->cache->delete( $key );

		$val = $this->cache->incrWithInit( $key, 0, 5 );
		$this->assertEquals( 5, $val, "Correct init value" );
	}

	/**
	 * @covers MediumSpecificBagOStuff::getMulti
	 */
	public function testGetMulti() {
		$value1 = [ 'this' => 'is', 'a' => 'test' ];
		$value2 = [ 'this' => 'is', 'another' => 'test' ];
		$value3 = [ 'testing a key that may be encoded when sent to cache backend' ];
		$value4 = [ 'another test where chars in key will be encoded' ];

		$key1 = $this->cache->makeKey( 'test-1' );
		$key2 = $this->cache->makeKey( 'test-2' );
		// internally, MemcachedBagOStuffs will encode to will-%25-encode
		$key3 = $this->cache->makeKey( 'will-%-encode' );
		$key4 = $this->cache->makeKey(
			'flowdb:flow_ref:wiki:by-source:v3:Parser\'s_"broken"_+_(page)_&_grill:testwiki:1:4.7'
		);

		// cleanup
		$this->cache->delete( $key1 );
		$this->cache->delete( $key2 );
		$this->cache->delete( $key3 );
		$this->cache->delete( $key4 );

		$this->cache->add( $key1, $value1, 5 );
		$this->cache->add( $key2, $value2, 5 );
		$this->cache->add( $key3, $value3, 5 );
		$this->cache->add( $key4, $value4, 5 );

		$this->assertEquals(
			[ $key1 => $value1, $key2 => $value2, $key3 => $value3, $key4 => $value4 ],
			$this->cache->getMulti( [ $key1, $key2, $key3, $key4 ] )
		);

		// cleanup
		$this->cache->delete( $key1 );
		$this->cache->delete( $key2 );
		$this->cache->delete( $key3 );
		$this->cache->delete( $key4 );
	}

	/**
	 * @covers MediumSpecificBagOStuff::setMulti
	 * @covers MediumSpecificBagOStuff::deleteMulti
	 */
	public function testSetDeleteMulti() {
		$map = [
			$this->cache->makeKey( 'test-1' ) => 'Siberian',
			$this->cache->makeKey( 'test-2' ) => [ 'Huskies' ],
			$this->cache->makeKey( 'test-3' ) => [ 'are' => 'the' ],
			$this->cache->makeKey( 'test-4' ) => (object)[ 'greatest' => 'animal' ],
			$this->cache->makeKey( 'test-5' ) => 4,
			$this->cache->makeKey( 'test-6' ) => 'ever'
		];

		$this->assertTrue( $this->cache->setMulti( $map ) );
		$this->assertEquals(
			$map,
			$this->cache->getMulti( array_keys( $map ) )
		);

		$this->assertTrue( $this->cache->deleteMulti( array_keys( $map ) ) );

		$this->assertEquals(
			[],
			$this->cache->getMulti( array_keys( $map ), BagOStuff::READ_LATEST )
		);
		$this->assertEquals(
			[],
			$this->cache->getMulti( array_keys( $map ) )
		);
	}

	/**
	 * @covers MediumSpecificBagOStuff::get
	 * @covers MediumSpecificBagOStuff::getMulti
	 * @covers MediumSpecificBagOStuff::merge
	 * @covers MediumSpecificBagOStuff::delete
	 */
	public function testSetSegmentable() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$tiny = 418;
		$small = wfRandomString( 32 );
		// 64 * 8 * 32768 = 16777216 bytes
		$big = str_repeat( wfRandomString( 32 ) . '-' . wfRandomString( 32 ), 32768 );

		$callback = function ( $cache, $key, $oldValue ) {
			return $oldValue . '!';
		};

		$cases = [ 'tiny' => $tiny, 'small' => $small, 'big' => $big ];
		foreach ( $cases as $case => $value ) {
			$this->cache->set( $key, $value, 10, BagOStuff::WRITE_ALLOW_SEGMENTS );
			$this->assertEquals( $value, $this->cache->get( $key ), "get $case" );
			$this->assertEquals( $value, $this->cache->getMulti( [ $key ] )[$key], "get $case" );

			$this->assertTrue(
				$this->cache->merge( $key, $callback, 5, 1, BagOStuff::WRITE_ALLOW_SEGMENTS ),
				"merge $case"
			);
			$this->assertEquals(
				"$value!",
				$this->cache->get( $key ),
				"merged $case"
			);
			$this->assertEquals(
				"$value!",
				$this->cache->getMulti( [ $key ] )[$key],
				"merged $case"
			);

			$this->assertTrue( $this->cache->deleteMulti( [ $key ] ), "delete $case" );
			$this->assertFalse( $this->cache->get( $key ), "deleted $case" );
			$this->assertEquals( [], $this->cache->getMulti( [ $key ] ), "deletd $case" );

			$this->cache->set( $key, "@$value", 10, BagOStuff::WRITE_ALLOW_SEGMENTS );
			$this->assertEquals( "@$value", $this->cache->get( $key ), "get $case" );
			$this->assertTrue(
				$this->cache->delete( $key, BagOStuff::WRITE_PRUNE_SEGMENTS ),
				"prune $case"
			);
			$this->assertFalse( $this->cache->get( $key ), "pruned $case" );
			$this->assertEquals( [], $this->cache->getMulti( [ $key ] ), "pruned $case" );
		}

		$this->cache->set( $key, 666, 10, BagOStuff::WRITE_ALLOW_SEGMENTS );

		$this->assertEquals( 666, $this->cache->get( $key ) );
		$this->assertEquals( 667, $this->cache->incr( $key ) );
		$this->assertEquals( 667, $this->cache->get( $key ) );

		$this->assertEquals( 664, $this->cache->decr( $key, 3 ) );
		$this->assertEquals( 664, $this->cache->get( $key ) );

		$this->assertTrue( $this->cache->delete( $key ) );
		$this->assertFalse( $this->cache->get( $key ) );
	}

	/**
	 * @covers MediumSpecificBagOStuff::getScopedLock
	 */
	public function testGetScopedLock() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$value1 = $this->cache->getScopedLock( $key, 0 );
		$value2 = $this->cache->getScopedLock( $key, 0 );

		$this->assertInstanceOf( ScopedCallback::class, $value1, 'First call returned lock' );
		$this->assertNull( $value2, 'Duplicate call returned no lock' );

		unset( $value1 );

		$value3 = $this->cache->getScopedLock( $key, 0 );
		$this->assertInstanceOf( ScopedCallback::class, $value3, 'Lock returned callback after release' );
		unset( $value3 );

		$value1 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );
		$value2 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );

		$this->assertInstanceOf( ScopedCallback::class, $value1, 'First reentrant call returned lock' );
		$this->assertInstanceOf( ScopedCallback::class, $value2, 'Second reentrant call returned lock' );
	}

	/**
	 * @covers MediumSpecificBagOStuff::__construct
	 * @covers MediumSpecificBagOStuff::trackDuplicateKeys
	 */
	public function testReportDupes() {
		$logger = $this->createMock( Psr\Log\NullLogger::class );
		$logger->expects( $this->once() )
			->method( 'warning' )
			->with( 'Duplicate get(): "{key}" fetched {count} times', [
				'key' => 'foo',
				'count' => 2,
			] );

		$cache = new HashBagOStuff( [
			'reportDupes' => true,
			'asyncHandler' => 'DeferredUpdates::addCallableUpdate',
			'logger' => $logger,
		] );
		$cache->get( 'foo' );
		$cache->get( 'bar' );
		$cache->get( 'foo' );

		DeferredUpdates::doUpdates();
	}

	/**
	 * @covers MediumSpecificBagOStuff::lock()
	 * @covers MediumSpecificBagOStuff::unlock()
	 */
	public function testLocking() {
		$key = 'test';
		$this->assertTrue( $this->cache->lock( $key ) );
		$this->assertFalse( $this->cache->lock( $key ) );
		$this->assertTrue( $this->cache->unlock( $key ) );

		$key2 = 'test2';
		$this->assertTrue( $this->cache->lock( $key2, 5, 5, 'rclass' ) );
		$this->assertTrue( $this->cache->lock( $key2, 5, 5, 'rclass' ) );
		$this->assertTrue( $this->cache->unlock( $key2 ) );
		$this->assertTrue( $this->cache->unlock( $key2 ) );
	}

	protected function tearDown() : void {
		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) );
		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) . ':lock' );

		parent::tearDown();
	}
}
