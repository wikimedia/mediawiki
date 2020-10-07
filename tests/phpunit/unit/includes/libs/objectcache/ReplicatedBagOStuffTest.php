<?php

class ReplicatedBagOStuffTest extends \MediaWikiUnitTestCase {
	/** @var HashBagOStuff */
	private $writeCache;
	/** @var HashBagOStuff */
	private $readCache;
	/** @var ReplicatedBagOStuff */
	private $cache;

	protected function setUp() : void {
		parent::setUp();

		$this->writeCache = new HashBagOStuff();
		$this->readCache = new HashBagOStuff();
		$this->cache = new ReplicatedBagOStuff( [
			'writeFactory' => $this->writeCache,
			'readFactory' => $this->readCache,
		] );
	}

	/**
	 * @covers ReplicatedBagOStuff::set
	 */
	public function testSet() {
		$key = 'a key';
		$value = 'a value';
		$this->cache->set( $key, $value );

		// Write to master.
		$this->assertEquals( $value, $this->writeCache->get( $key ) );
		// Don't write to replica. Replication is deferred to backend.
		$this->assertFalse( $this->readCache->get( $key ) );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGet() {
		$key = 'a key';

		$write = 'one value';
		$this->writeCache->set( $key, $write );
		$read = 'another value';
		$this->readCache->set( $key, $read );

		// Read from replica.
		$this->assertEquals( $read, $this->cache->get( $key ) );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGetAbsent() {
		$key = 'a key';
		$value = 'a value';
		$this->writeCache->set( $key, $value );

		// Don't read from master. No failover if value is absent.
		$this->assertFalse( $this->cache->get( $key ) );
	}
}
