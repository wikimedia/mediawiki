<?php

/**
 * @group Database
 */
class MultiWriteBagOStuffTest extends MediaWikiTestCase {
	/** @var HashBagOStuff */
	private $cache1;
	/** @var HashBagOStuff */
	private $cache2;
	/** @var MultiWriteBagOStuff */
	private $cache;

	protected function setUp() {
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
		$key = wfRandomString();
		$value = wfRandomString();
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
		$key = wfRandomString();
		$value = wfRandomString();
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
		$this->assertEquals( false, $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();

		// Set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );

		$key = wfRandomString();

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
		$key = wfRandomString();
		$value = wfRandomString();

		// XXX: DeferredUpdates bound to transactions in CLI mode
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$this->cache->set( $key, $value );

		// Set in tier 1
		$this->assertEquals( $value, $this->cache1->get( $key ), 'Written to tier 1' );
		// Not yet set in tier 2
		$this->assertEquals( false, $this->cache2->get( $key ), 'Not written to tier 2' );

		$dbw->commit();

		// Set in tier 2
		$this->assertEquals( $value, $this->cache2->get( $key ), 'Written to tier 2' );
	}
}
