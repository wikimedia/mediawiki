<?php
/**
 * This class will test BagOStuff.
 *
 * @author     Matthias Mullie <mmullie@wikimedia.org>
 */
class BagOStuffTest extends MediaWikiTestCase {
	private $cache;

	protected function setUp() {
		parent::setUp();

		// type defined through parameter
		if ( $this->getCliArg( 'use-bagostuff=' ) ) {
			$name = $this->getCliArg( 'use-bagostuff=' );

			$this->cache = ObjectCache::newFromId( $name );
		} else {
			// no type defined - use simple hash
			$this->cache = new HashBagOStuff;
		}

		$this->cache->delete( wfMemcKey( 'test' ) );
	}

	protected function tearDown() {
	}

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
		$fork = (bool)$this->getCliArg( 'use-bagostuff=' );
		$fork &= function_exists( 'pcntl_fork' );
		$fork &= !$this->cache instanceof HashBagOStuff;
		$fork &= !$this->cache instanceof EmptyBagOStuff;
		$fork &= !$this->cache instanceof MultiWriteBagOStuff;
		if ( $fork ) {
			// callback should take awhile now so that we can test concurrent merge attempts
			$usleep = 5000;

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

	public function testGetMulti() {
		$value1 = array( 'this' => 'is', 'a' => 'test' );
		$value2 = array( 'this' => 'is', 'another' => 'test' );

		$key1 = wfMemcKey( 'test1' );
		$key2 = wfMemcKey( 'test2' );

		$this->cache->add( $key1, $value1 );
		$this->cache->add( $key2, $value2 );

		$this->assertEquals( $this->cache->getMulti( array( $key1, $key2 ) ), array( $key1 => $value1, $key2 => $value2 ) );

		// cleanup
		$this->cache->delete( $key1 );
		$this->cache->delete( $key2 );
	}
}
