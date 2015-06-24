<?php

class ReplicatedBagOStuffTest extends MediaWikiTestCase {
	/** @var ReplicatedBagOStuff */
	private $cache;
	/** @var HashBagOStuff */
	private $readCache;
	/** @var HashBagOStuff */
	private $writeCache;

	protected function setUp() {
		parent::setUp();

		$this->readCache = new HashBagOStuff();
		$this->writeCache = new HashBagOStuff();
		$this->cache = new ReplicatedBagOStuff( array(
			'writeFactory' => $this->writeCache,
			'readFactory' => $this->readCache,
		) );
	}

	public function testSet() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->cache->set( $key, $value );

		// Write to master.
		$this->assertEquals( $this->writeCache->get( $key ), $value );
		// Don't write to slave. Replication is deferred to backend.
		$this->assertEquals( $this->readCache->get( $key ), false );
	}

	public function testGet() {
		$key = wfRandomString();

		$write = wfRandomString();
		$this->readCache->set( $key, $write );
		$read = wfRandomString();
		$this->writeCache->set( $key, $read );

		// Read from slave.
		$this->assertEquals( $this->cache->get( $key ), $read );
	}

	public function testGetAbsent() {
		$key = wfRandomString();
		$value = wfRandomString();
		$this->writeCache->set( $key, $value );

		// Don't read from master. No failover if value is absent.
		$this->assertEquals( $this->cache->get( $key ), false );
	}
}
