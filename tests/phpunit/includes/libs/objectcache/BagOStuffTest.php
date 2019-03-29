<?php

use Wikimedia\ScopedCallback;

/**
 * @author Matthias Mullie <mmullie@wikimedia.org>
 * @group BagOStuff
 */
class BagOStuffTest extends MediaWikiTestCase {
	/** @var BagOStuff */
	private $cache;

	const TEST_KEY = 'test';

	protected function setUp() {
		parent::setUp();

		// type defined through parameter
		if ( $this->getCliArg( 'use-bagostuff' ) !== null ) {
			$name = $this->getCliArg( 'use-bagostuff' );

			$this->cache = ObjectCache::newFromId( $name );
		} else {
			// no type defined - use simple hash
			$this->cache = new HashBagOStuff;
		}

		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) );
		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) . ':lock' );
	}

	/**
	 * @covers BagOStuff::makeGlobalKey
	 * @covers BagOStuff::makeKeyInternal
	 */
	public function testMakeKey() {
		$cache = ObjectCache::newFromId( 'hash' );

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
	 * @covers BagOStuff::merge
	 * @covers BagOStuff::mergeViaCas
	 */
	public function testMerge() {
		$key = $this->cache->makeKey( self::TEST_KEY );

		$calls = 0;
		$casRace = false; // emulate a race
		$callback = function ( BagOStuff $cache, $key, $oldVal ) use ( &$calls, &$casRace ) {
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
			$wrapper = \Wikimedia\TestingAccessWrapper::newFromObject( $this->cache );
			$n = count( $wrapper->caches );
		} else {
			$n = 1;
		}
		$this->assertEquals( $n, $calls );
	}

	/**
	 * @covers BagOStuff::changeTTL
	 */
	public function testChangeTTL() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$value = 'meow';

		$this->cache->add( $key, $value, 5 );
		$this->assertTrue( $this->cache->changeTTL( $key, 5 ) );
		$this->assertEquals( $this->cache->get( $key ), $value );
		$this->cache->delete( $key );
		$this->assertFalse( $this->cache->changeTTL( $key, 5 ) );
	}

	/**
	 * @covers BagOStuff::add
	 */
	public function testAdd() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$this->assertTrue( $this->cache->add( $key, 'test', 5 ) );
	}

	/**
	 * @covers BagOStuff::get
	 */
	public function testGet() {
		$value = [ 'this' => 'is', 'a' => 'test' ];

		$key = $this->cache->makeKey( self::TEST_KEY );
		$this->cache->add( $key, $value, 5 );
		$this->assertEquals( $this->cache->get( $key ), $value );
	}

	/**
	 * @covers BagOStuff::get
	 * @covers BagOStuff::set
	 * @covers BagOStuff::getWithSetCallback
	 */
	public function testGetWithSetCallback() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$value = $this->cache->getWithSetCallback(
			$key,
			30,
			function () {
				return 'hello kitty';
			}
		);

		$this->assertEquals( 'hello kitty', $value );
		$this->assertEquals( $value, $this->cache->get( $key ) );
	}

	/**
	 * @covers BagOStuff::incr
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
	 * @covers BagOStuff::incrWithInit
	 */
	public function testIncrWithInit() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertEquals( 3, $val, "Correct init value" );

		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertEquals( 4, $val, "Correct init value" );
	}

	/**
	 * @covers BagOStuff::getMulti
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
	 * @covers BagOStuff::setMulti
	 * @covers BagOStuff::deleteMulti
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

		$this->cache->setMulti( $map, 5 );
		$this->assertEquals(
			$map,
			$this->cache->getMulti( array_keys( $map ) )
		);

		$this->assertTrue( $this->cache->deleteMulti( array_keys( $map ), 5 ) );

		$this->assertEquals(
			[],
			$this->cache->getMulti( array_keys( $map ) )
		);
	}

	/**
	 * @covers BagOStuff::getScopedLock
	 */
	public function testGetScopedLock() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$value1 = $this->cache->getScopedLock( $key, 0 );
		$value2 = $this->cache->getScopedLock( $key, 0 );

		$this->assertType( ScopedCallback::class, $value1, 'First call returned lock' );
		$this->assertNull( $value2, 'Duplicate call returned no lock' );

		unset( $value1 );

		$value3 = $this->cache->getScopedLock( $key, 0 );
		$this->assertType( ScopedCallback::class, $value3, 'Lock returned callback after release' );
		unset( $value3 );

		$value1 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );
		$value2 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );

		$this->assertType( ScopedCallback::class, $value1, 'First reentrant call returned lock' );
		$this->assertType( ScopedCallback::class, $value1, 'Second reentrant call returned lock' );
	}

	/**
	 * @covers BagOStuff::__construct
	 * @covers BagOStuff::trackDuplicateKeys
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
	 * @covers BagOStuff::lock()
	 * @covers BagOStuff::unlock()
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
}
