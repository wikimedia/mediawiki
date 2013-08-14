<?php

/**
 * @group DataStore
 * @group Database
 */
class SqlDataStoreTest extends MediaWikiTestCase {
	/** @var SqlDataStore */
	private $store;

	public function setUp() {
		parent::setUp();
		$this->store = new SqlDataStore( array( 'dbName' => false ) );
		$this->store->set( 'unittest:foo', 'foo' );
		$this->store->setMulti( array( 'unittest:bar' => 'bar', 'unittest:baz' => 1 ) );
	}

	public function tearDown() {
		$this->store->deleteByPrefix( 'unittest:' );
		parent::tearDown();
	}

	public function testRoundTrip() {
		$this->assertEquals( 'foo', $this->store->get( 'unittest:foo' ) );
		$this->assertEquals( 'bar', $this->store->get( 'unittest:bar' ) );
		$this->assertEquals( 1, $this->store->get( 'unittest:baz' ) );
		$this->assertArrayEquals(
			array( 'unittest:foo' => 'foo', 'unittest:baz' => 1 ),
			$this->store->getMulti( array( 'unittest:foo', 'unittest:baz', 'unittest:nonexistent' ) ),
			false, // $ordered
			true // $named
		);
	}

	public function testNonexistentValue() {
		$this->assertNull( $this->store->get( 'unittest:nonexistent-' . mt_rand() ) );
	}

	public function testDeleteByPrefix() {
		$this->store->deleteByPrefix( 'unittest:b' );
		$this->assertNull( $this->store->get( 'unittest:bar' ) );
		$this->assertNull( $this->store->get( 'unittest:baz' ) );
		$this->assertNotNull( $this->store->get( 'unittest:foo' ) );
	}

	/**
	 * @expectedException MWException
	 */
	public function testFoolproofness() {
		$this->store->deleteByPrefix( '' );
	}
}