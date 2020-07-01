<?php

/**
 * @group Database
 */
class MultiWriteBagOStuffTest extends MediaWikiIntegrationTestCase {
	/** @var HashBagOStuff */
	private $cache1;
	/** @var HashBagOStuff */
	private $cache2;
	/** @var MultiWriteBagOStuff */
	private $cache;

	protected function setUp() : void {
		parent::setUp();

		$this->cache1 = new HashBagOStuff();
		$this->cache2 = new HashBagOStuff();
		$this->cache = new MultiWriteBagOStuff( [
			'caches' => [ $this->cache1, $this->cache2 ],
			'replication' => 'async',
			'asyncHandler' => 'DeferredUpdates::addCallableUpdate'
		] );
	}

	/**
	 * @covers MultiWriteBagOStuff::set
	 * @covers MultiWriteBagOStuff::doWrite
	 */
	public function testSetImmediate() {
		$key = 'key';
		$value = 'value';
		$this->cache->set( $key, $value );

		// Set in tier 1
		$this->assertEquals( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	/**
	 * @covers MultiWriteBagOStuff
	 */
	public function testSyncMerge() {
		$key = 'keyA';
		$value = 'value';
		$func = function () use ( $value ) {
			return $value;
		};

		// XXX: DeferredUpdates bound to transactions in CLI mode
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$this->cache->merge( $key, $func );

		// Set in tier 1
		$this->assertEquals( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Not yet set in tier 2
		$this->assertFalse( $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();

		// Set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );

		$key = 'keyB';

		$dbw->begin();
		$this->cache->merge( $key, $func, 0, 1, BagOStuff::WRITE_SYNC );

		// Set in tier 1
		$this->assertEquals( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Also set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );

		$dbw->commit();
	}

	/**
	 * @covers MultiWriteBagOStuff::set
	 */
	public function testSetDelayed() {
		$key = 'key';
		$value = (object)[ 'v' => 'saved value' ];
		$expectValue = clone $value;

		// XXX: DeferredUpdates bound to transactions in CLI mode
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$this->cache->set( $key, $value );

		// Test that later changes to $value don't affect the saved value (e.g. T168040)
		$value->v = 'other value';

		// Set in tier 1
		$this->assertEquals( $expectValue, $this->cache1->get( $key ), 'Written to tier 1' );
		// Not yet set in tier 2
		$this->assertFalse( $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();

		// Set in tier 2
		$this->assertEquals( $expectValue, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	/**
	 * @covers MultiWriteBagOStuff::makeKey
	 */
	public function testMakeKey() {
		$cache1 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeKey' ] )->getMock();
		$cache1->expects( $this->once() )->method( 'makeKey' )
			->willReturn( 'special' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeKey' );

		$cache = new MultiWriteBagOStuff( [ 'caches' => [ $cache1, $cache2 ] ] );
		$this->assertSame( 'special', $cache->makeKey( 'a', 'b' ) );
	}

	/**
	 * @covers MultiWriteBagOStuff::makeGlobalKey
	 */
	public function testMakeGlobalKey() {
		$cache1 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache1->expects( $this->once() )->method( 'makeGlobalKey' )
			->willReturn( 'special' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeGlobalKey' );

		$cache = new MultiWriteBagOStuff( [ 'caches' => [ $cache1, $cache2 ] ] );

		$this->assertSame( 'special', $cache->makeGlobalKey( 'a', 'b' ) );
	}

	/**
	 * @covers MultiWriteBagOStuff::add
	 */
	public function testDuplicateStoreAdd() {
		$bag = new HashBagOStuff();
		$cache = new MultiWriteBagOStuff( [
			'caches' => [ $bag, $bag ],
		] );

		$this->assertTrue( $cache->add( 'key', 1, 30 ) );
	}
}
