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
			'keyspace' => 'repl_local',
			'writeFactory' => $this->writeCache,
			'readFactory' => $this->readCache,
		] );
	}

	/**
	 * @covers ReplicatedBagOStuff::set
	 */
	public function testSet() {
		$key = $this->cache->makeKey( 'a', 'key' );
		$value = 'a value';

		$this->cache->set( $key, $value );

		$this->assertSame( $value, $this->writeCache->get( $key ), 'Written' );
		$this->assertFalse( $this->readCache->get( $key ), 'Async replication' );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGet() {
		$key = $this->cache->makeKey( 'a', 'key' );

		$write = 'new value';
		$this->writeCache->set( $key, $write );
		$read = 'old value';
		$this->readCache->set( $key, $read );

		$this->assertSame( $read, $this->cache->get( $key ), 'Async replication' );
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 */
	public function testGetAbsent() {
		$key = $this->cache->makeKey( 'a', 'key' );
		$value = 'a value';
		$this->writeCache->set( $key, $value );

		$this->assertFalse( $this->cache->get( $key ), 'Async replication' );
	}

	/**
	 * @covers ReplicatedBagOStuff::setMulti
	 * @covers ReplicatedBagOStuff::getMulti
	 */
	public function testGetSetMulti() {
		$keyA = $this->cache->makeKey( 'key', 'a' );
		$keyB = $this->cache->makeKey( 'key', 'b' );
		$valueAOld = 'one old value';
		$valueBOld = 'another old value';
		$valueANew = 'one new value';
		$valueBNew = 'another new value';

		$this->writeCache->setMulti( [ $keyA => $valueANew, $keyB => $valueBNew ] );
		$this->readCache->setMulti( [ $keyA => $valueAOld, $keyB => $valueBOld ] );

		$this->assertEquals(
			[ $keyA => $valueAOld, $keyB => $valueBOld ],
			$this->cache->getMulti( [ $keyA, $keyB ] ),
			'Async replication'
		);
	}

	/**
	 * @covers ReplicatedBagOStuff::get
	 * @covers ReplicatedBagOStuff::set
	 */
	public function testGetSetRaw() {
		$key = 'a:key';
		$value = 'a value';
		$this->cache->set( $key, $value );

		// Write to master.
		$this->assertEquals( $value, $this->writeCache->get( $key ) );
		// Don't write to replica. Replication is deferred to backend.
		$this->assertFalse( $this->readCache->get( $key ) );
	}
}
