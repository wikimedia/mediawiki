<?php
/**
 * @author Matthias Mullie <mmullie@wikimedia.org>
 * @group BagOStuff
 */
class BagOStuffTest extends MediaWikiTestCase {
	/** @var BagOStuff */
	private $cache;

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

		$this->cache->delete( wfMemcKey( 'test' ) );
	}

	/**
	 * @covers BagOStuff::merge
	 * @covers BagOStuff::mergeViaLock
	 */
	public function testMerge() {
		$key = wfMemcKey( 'test' );

		$usleep = 0;

		/**
		 * Callback method: append "merged" to whatever is in cache.
		 *
		 * @param BagOStuff $cache
		 * @param string $key
		 * @param int $existingValue
		 * @use int $usleep
		 * @return int
		 */
		$callback = function ( BagOStuff $cache, $key, $existingValue ) use ( &$usleep ) {
			// let's pretend this is an expensive callback to test concurrent merge attempts
			usleep( $usleep );

			if ( $existingValue === false ) {
				return 'merged';
			}

			return $existingValue . 'merged';
		};

		// merge on non-existing value
		$merged = $this->cache->merge( $key, $callback, 0 );
		$this->assertTrue( $merged );
		$this->assertEquals( $this->cache->get( $key ), 'merged' );

		// merge on existing value
		$merged = $this->cache->merge( $key, $callback, 0 );
		$this->assertTrue( $merged );
		$this->assertEquals( $this->cache->get( $key ), 'mergedmerged' );

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
			// callback should take awhile now so that we can test concurrent merge attempts
			$pid = pcntl_fork();
			if ( $pid == -1 ) {
				// can't fork, ignore this test...
			} elseif ( $pid ) {
				// wait a little, making sure that the child process is calling merge
				usleep( 3000 );

				// attempt a merge - this should fail
				$merged = $this->cache->merge( $key, $callback, 0, 1 );

				// merge has failed because child process was merging (and we only attempted once)
				$this->assertFalse( $merged );

				// make sure the child's merge is completed and verify
				usleep( 3000 );
				$this->assertEquals( $this->cache->get( $key ), 'mergedmergedmerged' );
			} else {
				$this->cache->merge( $key, $callback, 0, 1 );

				// Note: I'm not even going to check if the merge worked, I'll
				// compare values in the parent process to test if this merge worked.
				// I'm just going to exit this child process, since I don't want the
				// child to output any test results (would be rather confusing to
				// have test output twice)
				exit;
			}
		}
	}

	/**
	 * @covers BagOStuff::add
	 */
	public function testAdd() {
		$key = wfMemcKey( 'test' );
		$this->assertTrue( $this->cache->add( $key, 'test' ) );
	}

	public function testGet() {
		$value = array( 'this' => 'is', 'a' => 'test' );

		$key = wfMemcKey( 'test' );
		$this->cache->add( $key, $value );
		$this->assertEquals( $this->cache->get( $key ), $value );
	}

	/**
	 * @covers BagOStuff::incr
	 */
	public function testIncr() {
		$key = wfMemcKey( 'test' );
		$this->cache->add( $key, 0 );
		$this->cache->incr( $key );
		$expectedValue = 1;
		$actualValue = $this->cache->get( $key );
		$this->assertEquals( $expectedValue, $actualValue, 'Value should be 1 after incrementing' );
	}

	/**
	 * @covers BagOStuff::getMulti
	 */
	public function testGetMulti() {
		$value1 = array( 'this' => 'is', 'a' => 'test' );
		$value2 = array( 'this' => 'is', 'another' => 'test' );
		$value3 = array( 'testing a key that may be encoded when sent to cache backend' );

		$key1 = wfMemcKey( 'test1' );
		$key2 = wfMemcKey( 'test2' );
		$key3 = wfMemcKey( 'will-%-encode' ); // internally, MemcachedBagOStuffs will encode to will-%25-encode

		$this->cache->add( $key1, $value1 );
		$this->cache->add( $key2, $value2 );
		$this->cache->add( $key3, $value3 );

		$this->assertEquals(
			array( $key1 => $value1, $key2 => $value2, $key3 => $value3 ),
			$this->cache->getMulti( array( $key1, $key2, $key3 ) )
		);

		// cleanup
		$this->cache->delete( $key1 );
		$this->cache->delete( $key2 );
		$this->cache->delete( $key3 );
	}

	/**
	 * @covers BagOStuff::getScopedLock
	 */
	public function testGetScopedLock() {
		$key = wfMemcKey( 'test' );
		$value1 = $this->cache->getScopedLock( $key, 0 );
		$value2 = $this->cache->getScopedLock( $key, 0 );

		$this->assertType( 'ScopedCallback', $value1, 'First call returned lock' );
		$this->assertNull( $value2, 'Duplicate call returned no lock' );

		unset( $value1 );

		$value3 = $this->cache->getScopedLock( $key, 0 );
		$this->assertType( 'ScopedCallback', $value3, 'Lock returned callback after release' );
		unset( $value3 );

		$value1 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );
		$value2 = $this->cache->getScopedLock( $key, 0, 5, 'reentry' );

		$this->assertType( 'ScopedCallback', $value1, 'First reentrant call returned lock' );
		$this->assertType( 'ScopedCallback', $value1, 'Second reentrant call returned lock' );
	}
}
