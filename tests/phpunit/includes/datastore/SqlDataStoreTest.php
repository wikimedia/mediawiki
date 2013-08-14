<?php

/**
 * @group DataStore
 * @group Database
 */
class SqlDataStoreTest extends MediaWikiTestCase {
	/** @var SqlDataStore */
	private $store;

	function __construct( $name = null, array $data = array(), $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
		$this->tablesUsed[] = 'store';
	}

	public function setUp() {
		parent::setUp();
		$this->store = new SqlDataStore( array( 'dbName' => false, 'batchSize' => 2 ) );
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

	public function testGetByPrefix_empty() {
		$this->assertFalse( $this->store->getByPrefix( 'unittest:nonexistent' )->valid() );
	}

	/**
	 * @dataProvider provideGetByPrefix
	 * @param array $expected
	 * @param string $prefix
	 */
	public function testGetByPrefix( array $expected, $prefix ) {
		$iter = $this->store->getByPrefix( $prefix );
		$res = array();
		foreach ( $iter as $key => $value ) {
			$this->assertFalse( isset( $res[$key] ), 'Duplicate key returned' );
			$res[$key] = $value;
		}
		$this->assertArrayEquals(
			$expected,
			$res,
			true, // $ordered
			true // $named
		);
	}

	public function provideGetByPrefix() {
		return array(
			array(
				array( 'unittest:bar' => 'bar', 'unittest:baz' => 1, 'unittest:foo' => 'foo' ),
				'unittest:',
			),
			array(
				array( 'unittest:bar' => 'bar', 'unittest:baz' => 1 ),
				'unittest:b',
			),
			array(
				array( 'unittest:bar' => 'bar' ),
				'unittest:bar',
			),
		);
	}
}