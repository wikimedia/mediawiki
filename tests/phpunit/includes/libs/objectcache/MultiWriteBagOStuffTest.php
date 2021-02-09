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
	 */
	public function testSet() {
		$key = 'key';
		$value = 'value';
		$this->cache->set( $key, $value );

		// Set in tier 1
		$this->assertSame( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Set in tier 2
		$this->assertSame( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	/**
	 * @covers MultiWriteBagOStuff::add
	 */
	public function testAdd() {
		$key = 'key';
		$value = 'value';
		$ok = $this->cache->add( $key, $value );

		$this->assertTrue( $ok );
		// Set in tier 1
		$this->assertSame( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Set in tier 2
		$this->assertSame( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}

	/**
	 * @covers MultiWriteBagOStuff
	 */
	public function testSyncMerge() {
		$key = 'keyA';
		$value = 'value';
		$func = static function () use ( $value ) {
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
		$cache1->expects( $this->never() )->method( 'makeKey' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeKey' );

		$cache = new MultiWriteBagOStuff( [
			'keyspace' => 'generic',
			'caches' => [ $cache1, $cache2 ]
		] );

		$this->assertSame( 'generic:a:b', $cache->makeKey( 'a', 'b' ) );
	}

	/**
	 * @covers MultiWriteBagOStuff::makeGlobalKey
	 */
	public function testMakeGlobalKey() {
		$cache1 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache1->expects( $this->never() )->method( 'makeGlobalKey' );

		$cache2 = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'makeGlobalKey' ] )->getMock();
		$cache2->expects( $this->never() )->method( 'makeGlobalKey' );

		$cache = new MultiWriteBagOStuff( [ 'caches' => [ $cache1, $cache2 ] ] );

		$this->assertSame( 'global:a:b', $cache->makeGlobalKey( 'a', 'b' ) );
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

	/**
	 * @covers MultiWriteBagOStuff::incr
	 */
	public function testIncr() {
		$key = $this->cache->makeKey( 'key' );

		$this->cache->add( $key, 7, 30 );

		$value = $this->cache->incr( $key );
		$this->assertSame( 8, $value, 'Value after incrementing' );

		$value = $this->cache->get( $key );
		$this->assertSame( 8, $value, 'Value after incrementing' );
	}

	/**
	 * @covers MultiWriteBagOStuff::decr
	 */
	public function testDecr() {
		$key = $this->cache->makeKey( 'key' );

		$this->cache->add( $key, 10, 30 );

		$value = $this->cache->decr( $key );
		$this->assertSame( 9, $value, 'Value after decrementing' );

		$value = $this->cache->get( $key );
		$this->assertSame( 9, $value, 'Value after decrementing' );
	}

	/**
	 * @covers MultiWriteBagOStuff::incrWithInit
	 */
	public function testIncrWithInit() {
		$key = $this->cache->makeKey( 'key' );
		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertSame( 3, $val, "Correct init value" );

		$val = $this->cache->incrWithInit( $key, 0, 1, 3 );
		$this->assertSame( 4, $val, "Correct init value" );
		$this->cache->delete( $key );

		$val = $this->cache->incrWithInit( $key, 0, 5 );
		$this->assertSame( 5, $val, "Correct init value" );
	}
}
