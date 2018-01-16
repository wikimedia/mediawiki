<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableSqlStore;
use MediaWikiTestCase;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Addshore
 * @group Database
 * @covers \MediaWiki\Storage\NameTableSqlStore
 */
class NameTableSqlStoreTest extends MediaWikiTestCase {

	public function setUp() {
		$this->tablesUsed[] = 'slot_roles';
		parent::setUp();
	}

	private function populateTable( $values ) {
		$insertValues = [];
		foreach ( $values as $name ) {
			$insertValues[] = [ 'role_name' => $name ];
		}
		$this->db->insert( 'slot_roles', $insertValues );
	}

	private function getHashWANObjectCache( $wanCache ) {
		return $wanCache
			? new WANObjectCache( [ 'cache' => new \HashBagOStuff() ] )
			: new WANObjectCache( [ 'cache' => new \EmptyBagOStuff() ] );
	}

	/**
	 * @param $db
	 * @return \PHPUnit_Framework_MockObject_MockObject|LoadBalancer
	 */
	private function getMockLoadBalancer( $db ) {
		$mock = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->any() )
			->method( 'getConnection' )
			->willReturn( $db );
		return $mock;
	}

	private function getCallCheckingDb( $insertCalls, $selectCalls ) {
		$mock = $this->getMockBuilder( Database::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->exactly( $insertCalls ) )
			->method( 'insert' )
			->willReturnCallback( function () {
				return call_user_func_array( [ $this->db, 'insert' ], func_get_args() );
			} );
		$mock->expects( $this->exactly( $selectCalls ) )
			->method( 'select' )
			->willReturnCallback( function () {
				return call_user_func_array( [ $this->db, 'select' ], func_get_args() );
			} );
		$mock->expects( $this->exactly( $insertCalls ) )
			->method( 'affectedRows' )
			->willReturnCallback( function () {
				return call_user_func_array( [ $this->db, 'affectedRows' ], func_get_args() );
			} );
		$mock->expects( $this->exactly( $insertCalls ) )
			->method( 'insertId' )
			->willReturnCallback( function () {
				return call_user_func_array( [ $this->db, 'insertId' ], func_get_args() );
			} );
		return $mock;
	}

	private function getNameTableSqlStore( $wanCache, $insertCalls, $selectCalls ) {
		return new NameTableSqlStore(
			$this->getMockLoadBalancer( $this->getCallCheckingDb( $insertCalls, $selectCalls ) ),
			$this->getHashWANObjectCache( $wanCache ),
			new NullLogger(),
			'slot_roles', 'role_id', 'role_name'
		);
	}

	public function provideGetAndAcquireId() {
		return [
			'no wancache, empty table' =>
				[ false, true, 2, [], 'foo', 1 ],
			'no wancache, one matching value' =>
				[ false, false, 2, [ 'foo' ], 'foo', 1 ],
			'no wancache, one not matching value' =>
				[ false, true, 2, [ 'bar' ], 'foo', 2 ],
			'no wancache, multiple, one matching value' =>
				[ false, false, 2, [ 'foo', 'bar' ], 'bar', 2 ],
			'no wancache, multiple, no matching value' =>
				[ false, true, 2, [ 'foo', 'bar' ], 'baz', 3 ],
			'wancache, empty table' =>
				[ true, true, 1, [], 'foo', 1 ],
			'wancache, one matching value' =>
				[ true, false, 1, [ 'foo' ], 'foo', 1 ],
			'wancache, one not matching value' =>
				[ true, true, 1, [ 'bar' ], 'foo', 2 ],
			'wancache, multiple, one matching value' =>
				[ true, false, 1, [ 'foo', 'bar' ], 'bar', 2 ],
			'wancache, multiple, no matching value' =>
				[ true, true, 1, [ 'foo', 'bar' ], 'baz', 3 ],
		];
	}

	/**
	 * @dataProvider provideGetAndAcquireId
	 * @param bool $wanCache Should a real WANObjectCache be used or one backed by an EmptyBagOStuff
	 * @param bool $needsInsert Does the value we are testing need to be inserted?
	 * @param int $selectCalls Number of times the select DB method will be called
	 * @param string[] $existingValues to be added to the db table
	 * @param string $name name to acquire
	 * @param int $expectedId the id we expect the name to have
	 */
	public function testGetAndAcquireId(
		$wanCache,
		$needsInsert,
		$selectCalls,
		$existingValues,
		$name,
		$expectedId
	) {
		$this->populateTable( $existingValues );
		$store = $this->getNameTableSqlStore( $wanCache, (int)$needsInsert, $selectCalls );

		// Some names will not initially exist
		try {
			$result = $store->getId( $name );
			$this->assertSame( $expectedId, $result );
		} catch ( NameTableAccessException $e ) {
			if( $needsInsert ) {
				$this->assertTrue( true ); // Expected exception
			} else {
				$this->fail( 'Did not expect an exception, but got one: ' . $e->getMessage() );
			}
		}

		// All names should return their id here
		$this->assertSame( $expectedId, $store->acquireId( $name ) );

		// acquireId inserted these names, so now everything should exist with getId
		$this->assertSame( $expectedId, $store->getId( $name ) );

		// If we reset the class cache, this second getId will either hit the WAN cache or DB
		TestingAccessWrapper::newFromObject( $store )->tableCache = null;
		$this->assertSame( $expectedId, $store->getId( $name ) );
	}

	public function provideGetName() {
		return [
			[ true, 3, 1 ],
			[ false, 3, 3 ],
		];
	}

	/**
	 * @dataProvider provideGetName
	 */
	public function testGetName( $wanCache, $insertCalls, $selectCalls ) {
		$store = $this->getNameTableSqlStore( $wanCache, $insertCalls, $selectCalls );

		// Get 1 ID and make sure getName returns correctly
		$fooId = $store->acquireId( 'foo' );
		$this->assertSame( 'foo', $store->getName( $fooId ) );

		// Get another ID and make sure getName returns correctly
		$barId = $store->acquireId( 'bar' );
		$this->assertSame( 'bar', $store->getName( $barId ) );

		// Blitz the cache and make sure it still returns
		TestingAccessWrapper::newFromObject( $store )->tableCache = null;
		$this->assertSame( 'foo', $store->getName( $fooId ) );
		$this->assertSame( 'bar', $store->getName( $barId ) );


		// Blitz the cache again and get another ID and make sure getName returns correctly
		TestingAccessWrapper::newFromObject( $store )->tableCache = null;
		$bazId = $store->acquireId( 'baz' );
		$this->assertSame( 'baz', $store->getName( $bazId ) );
		$this->assertSame( 'baz', $store->getName( $bazId ) );
	}

	public function testGetTable_empty() {
		$this->populateTable( [] );
		$store = $this->getNameTableSqlStore( true, 0, 1 );
		$table = $store->getTable();
		$this->assertSame( [], $table );
	}

	public function testGetTable_twoValues() {
		$this->populateTable( [ 'foo', 'bar' ] );
		$store = $this->getNameTableSqlStore( true, 0, 1 );

		// We are using a cache, so 2 calls should only result in 1 select on the db
		$store->getTable();
		$table = $store->getTable();

		$expected = [ 2 => 'bar', 1 => 'foo' ];
		$this->assertSame( $expected, $table );
		// Make sure the table returned is the same as the cached table
		$this->assertSame( $expected, TestingAccessWrapper::newFromObject( $store )->tableCache );
	}

}
