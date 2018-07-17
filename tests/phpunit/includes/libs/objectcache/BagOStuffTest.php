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
		if ( $this->getCliArg( 'use-bagostuff' ) ) {
			$name = $this->getCliArg( 'use-bagostuff' );

			$this->cache = ObjectCache::newFromId( $name );
		} else {
			// no type defined - use simple hash
			$this->cache = new HashBagOStuff;
		}

		$this->cache->delete( $this->cache->makeKey( self::TEST_KEY ) );
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
	 * @covers BagOStuff::mergeViaLock
	 * @covers BagOStuff::mergeViaCas
	 */
	public function testMerge() {
		$calls = 0;
		$key = $this->cache->makeKey( self::TEST_KEY );
		$callback = function ( BagOStuff $cache, $key, $oldVal ) use ( &$calls ) {
			++$calls;

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
		$this->cache->lock( $key );
		$this->assertFalse( $this->cache->merge( $key, $callback, 1 ), 'Non-blocking merge' );
		$this->cache->unlock( $key );
		$this->assertEquals( 0, $calls );
	}

	/**
	 * @covers BagOStuff::merge
	 * @covers BagOStuff::mergeViaLock
	 */
	public function testMerge_fork() {
		$key = $this->cache->makeKey( self::TEST_KEY );
		$callback = function ( BagOStuff $cache, $key, $oldVal ) {
			return ( $oldVal === false ) ? 'merged' : $oldVal . 'merged';
		};
		/*
		 * Test concurrent merges by forking this process, if:
		 * - not manually called with --use-bagostuff
		 * - pcntl_fork is supported by the system
		 * - cache type will correctly support calls over forks
		 */
		$fork = (bool)$this->getCliArg( 'use-bagostuff' );
		$fork &= function_exists( 'pcntl_fork' );
		$fork &= !$this->cache instanceof HashBagOStuff;
		$fork &= !$this->cache instanceof EmptyBagOStuff;
		$fork &= !$this->cache instanceof MultiWriteBagOStuff;
		if ( $fork ) {
			$pid = null;
			// Function to start merge(), run another merge() midway through, then finish
			$outerFunc = function ( BagOStuff $cache, $key, $oldVal ) use ( $callback, &$pid ) {
				$pid = pcntl_fork();
				if ( $pid == -1 ) {
					return false;
				} elseif ( $pid ) {
					pcntl_wait( $status );

					return $callback( $cache, $key, $oldVal );
				} else {
					$this->cache->merge( $key, $callback, 0, 1 );
					// Bail out of the outer merge() in the child process since it does not
					// need to attempt to write anything. Success is checked by the parent.
					parent::tearDown(); // avoid phpunit notices
					exit;
				}
			};

			// attempt a merge - this should fail
			$merged = $this->cache->merge( $key, $outerFunc, 0, 1 );

			if ( $pid == -1 ) {
				return; // can't fork, ignore this test...
			}

			// merge has failed because child process was merging (and we only attempted once)
			$this->assertFalse( $merged );

			// make sure the child's merge is completed and verify
			$this->assertEquals( $this->cache->get( $key ), 'mergedmerged' );
		} else {
			$this->markTestSkipped( 'No pcntl methods available' );
		}
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
