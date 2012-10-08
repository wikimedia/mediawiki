<?php

/**
 * Test for ProcessCacheLRU class.
 *
 * Note that it uses the ProcessCacheLRUTestable class which extends some
 * properties and methods visibility. That class is defined at the end of the
 * file containing this class.
 *
 * @group Cache
 */
class ProcessCacheLRUTest extends MediaWikiTestCase {

	/**
	 * Helper to verify emptiness of a cache object.
	 * Compare against an array so we get the cache content difference.
	 */
	function assertCacheEmpty( $cache, $msg = 'Cache should be empty' ) {
		$this->assertAttributeEquals( array(), 'cache', $cache, $msg );
	}

	/**
	 * Helper to fill a cache object passed by reference
	 */
	function fillCache( &$cache, $numEntries ) {
		// Fill cache with three values
		for( $i=1; $i<=$numEntries; $i++) {
			$cache->set( "cache-key-$i", "prop-$i", "value-$i" );
		}
	}

	/**
	 * Generates an array of what would be expected in cache for a given cache
	 * size and a number of entries filled in sequentially
	 */
	function getExpectedCache( $cacheMaxEntries, $entryToFill ) {
		$expected = array();

		if( $entryToFill === 0 ) {
			# The cache is empty!
			return array();
		} elseif( $entryToFill <= $cacheMaxEntries ) {
			# Cache is not fully filled
			$firstKey = 1;
		} else {
			# Cache overflowed
			$firstKey = 1 + $entryToFill - $cacheMaxEntries;
		}

		$lastKey  = $entryToFill;

		for( $i=$firstKey; $i<=$lastKey; $i++ ) {
			$expected["cache-key-$i"] = array( "prop-$i" => "value-$i" );
		}
		return $expected;
	}

	/**
	 * Highlight diff between assertEquals and assertNotSame
	 */
	function testPhpUnitArrayEquality() {
		$one = array( 'A' => 1, 'B' => 2 );
		$two = array( 'B' => 2, 'A' => 1 );
		$this->assertEquals( $one, $two );  // ==
		$this->assertNotSame( $one, $two ); // ===
	}

	/**
	 * @dataProvider provideInvalidConstructorArg
	 * @expectedException MWException
	 */
	function testConstructorGivenInvalidValue( $maxSize ) {
		$c = new ProcessCacheLRUTestable( $maxSize );
	}

	/**
	 * Value which are forbidden by the constructor
	 */
	public static function provideInvalidConstructorArg() {
		return array(
			array( null ),
			array( array() ),
			array( new stdClass() ),
			array( 0 ),
			array( '5' ),
			array( -1 ),
		);
	}

	function testAddAndGetAKey() {
		$oneCache = new ProcessCacheLRUTestable( 1 );
		$this->assertCacheEmpty( $oneCache );

		// First set just one value
		$oneCache->set( 'cache-key', 'prop1', 'value1' );
		$this->assertEquals( 1, $oneCache->getEntriesCount() );
		$this->assertTrue( $oneCache->has( 'cache-key', 'prop1' ) );
		$this->assertEquals( 'value1', $oneCache->get( 'cache-key', 'prop1' ) );
	}

	function testDeleteOldKey() {
		$oneCache = new ProcessCacheLRUTestable( 1 );
		$this->assertCacheEmpty( $oneCache );

		$oneCache->set( 'cache-key', 'prop1', 'value1' );
		$oneCache->set( 'cache-key', 'prop1', 'value2' );
		$this->assertEquals( 'value2', $oneCache->get( 'cache-key', 'prop1' ) );
	}

	/**
	 * This test that we properly overflow when filling a cache with
	 * a sequence of always different cache-keys. Meant to verify we correclty
	 * delete the older key.
	 *
	 * @dataProvider provideCacheFilling
	 * @param $cacheMaxEntries Maximum entry the created cache will hold
	 * @param $entryToFill Number of entries to insert in the created cache.
	 */
	function testFillingCache( $cacheMaxEntries, $entryToFill, $msg = '' ) {
		$cache = new ProcessCacheLRUTestable( $cacheMaxEntries );
		$this->fillCache( $cache, $entryToFill);

		$this->assertSame(
			$this->getExpectedCache( $cacheMaxEntries, $entryToFill ),
			$cache->getCache(),
			"Filling a $cacheMaxEntries entries cache with $entryToFill entries"
		);

	}

	/**
	 * Provider for testFillingCache
	 */
	public static function provideCacheFilling() {
		// ($cacheMaxEntries, $entryToFill, $msg='')
		return array(
			array( 1,  0 ),
			array( 1,  1 ),
			array( 1,  2 ), # overflow
			array( 5, 33 ), # overflow
		);

	}

	/**
	 * Create a cache with only one remaining entry then update
	 * the first inserted entry. Should bump it to the top.
	 */
	function testReplaceExistingKeyShouldBumpEntryToTop() {
		$maxEntries = 3;

		$cache = new ProcessCacheLRUTestable( $maxEntries );
		// Fill cache leaving just one remaining slot
		$this->fillCache( $cache, $maxEntries - 1 );

		// Set an existing cache key
		$cache->set( "cache-key-1", "prop-1", "new-value-for-1" );

		$this->assertSame(
			array(
				'cache-key-2' => array( 'prop-2' => 'value-2' ),
				'cache-key-1' => array( 'prop-1' => 'new-value-for-1' ),
			),
			$cache->getCache()
		);
	}

	function testRecentlyAccessedKeyStickIn() {
		$cache = new ProcessCacheLRUTestable( 2 );
		$cache->set( 'first' , 'prop1', 'value1' );
		$cache->set( 'second', 'prop2', 'value2' );

		// Get first
		$cache->get( 'first', 'prop1' );
		// Cache a third value, should invalidate the least used one
		$cache->set( 'third', 'prop3', 'value3' );

		$this->assertFalse( $cache->has( 'second', 'prop2' ) );
	}

	/**
	 * This first create a full cache then update the value for the 2nd
	 * filled entry.
	 * Given a cache having 1,2,3 as key, updating 2 should bump 2 to
	 * the top of the queue with the new value: 1,3,2* (* = updated).
	 */
	function testReplaceExistingKeyInAFullCacheShouldBumpToTop() {
		$maxEntries = 3;

		$cache = new ProcessCacheLRUTestable( $maxEntries );
		$this->fillCache( $cache, $maxEntries );

		// Set an existing cache key
		$cache->set( "cache-key-2", "prop-2", "new-value-for-2" );
		$this->assertSame(
			array(
				'cache-key-1' => array( 'prop-1' => 'value-1' ),
				'cache-key-3' => array( 'prop-3' => 'value-3' ),
				'cache-key-2' => array( 'prop-2' => 'new-value-for-2' ),
			),
			$cache->getCache()
		);
		$this->assertEquals( 'new-value-for-2',
			$cache->get( 'cache-key-2', 'prop-2' )
		);
	}

	function testBumpExistingKeyToTop() {
		$cache = new ProcessCacheLRUTestable( 3 );
		$this->fillCache( $cache, 3 );

		// Set the very first cache key to a new value
		$cache->set( "cache-key-1", "prop-1", "new value for 1" );
		$this->assertEquals(
			array(
				'cache-key-2' => array( 'prop-2' => 'value-2' ),
				'cache-key-3' => array( 'prop-3' => 'value-3' ),
				'cache-key-1' => array( 'prop-1' => 'new value for 1' ),
			),
			$cache->getCache()
		);

	}

}

/**
 * Overrides some ProcessCacheLRU methods and properties accessibility.
 */
class ProcessCacheLRUTestable extends ProcessCacheLRU {
	public $cache = array();

	public function getCache() {
		return $this->cache;
	}
	public function getEntriesCount() {
		return count( $this->cache );
	}
}
