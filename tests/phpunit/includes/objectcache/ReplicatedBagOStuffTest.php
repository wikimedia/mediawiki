<?php

class ReplicatedBagOStuffTest extends MediaWikiTestCase {
	/** @var HashBagOStuff */
	private $writeCache;
	/** @var HashBagOStuff */
	private $readCache;
	/** @var ReplicatedBagOStuff */
	private $cache;

	protected function setUp() {
		parent::setUp();

		$this->writeCache = new HashBagOStuff();
		$this->readCache = new HashBagOStuff();
		$this->cache = new ReplicatedBagOStuff( array(
			'writeFactory' => $this->writeCache,
			'readFactory' => $this->readCache,
		) );
	}

	/**
	 * @covers ReplicatedBagOStuff::set
	 */
	public function testSet() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->cache->set( $key, $value );

		// Write to master.
		$this->assertEquals( $this->writeCache->get( $key ), $value );
		// Don't write to slave. Replication is deferred to backend.
		$this->assertEquals( $this->readCache->get( $key ), false );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGet() {
		$key = wfRandomString();

		$write = wfRandomString();
		$this->writeCache->set( $key, $write );
		$read = wfRandomString();
		$this->readCache->set( $key, $read );

		// Read from slave.
		$this->assertEquals( $this->cache->get( $key ), $read );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGetAbsent() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->writeCache->set( $key, $value );

		// Don't read from master. No failover if value is absent.
		$this->assertEquals( $this->cache->get( $key ), false );
	}
}
