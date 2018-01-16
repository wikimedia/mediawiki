<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\NameTableSqlStore;
use MediaWikiTestCase;
use Psr\Log\NullLogger;
use WANObjectCache;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

/**
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

	public function provideReacquireAcquire() {
		return [
			'no wancache, empty table' =>
				[ false, 1, 2, [], 'foo', 1 ],
			'no wancache, one matching value' =>
				[ false, 0, 2, [ 'foo' ], 'foo', 1 ],
			'no wancache, one not matching value' =>
				[ false, 1, 2, [ 'bar' ], 'foo', 2 ],
			'no wancache, multiple, one matching value' =>
				[ false, 0, 2, [ 'foo', 'bar' ], 'bar', 2 ],
			'no wancache, multiple, no matching value' =>
				[ false, 1, 2, [ 'foo', 'bar' ], 'baz', 3 ],
			'wancache, empty table' =>
				[ true, 1, 1, [], 'foo', 1 ],
			'wancache, one matching value' =>
				[ true, 0, 1, [ 'foo' ], 'foo', 1 ],
			'wancache, one not matching value' =>
				[ true, 1, 1, [ 'bar' ], 'foo', 2 ],
			'wancache, multiple, one matching value' =>
				[ true, 0, 1, [ 'foo', 'bar' ], 'bar', 2 ],
			'wancache, multiple, no matching value' =>
				[ true, 1, 1, [ 'foo', 'bar' ], 'baz', 3 ],
		];
	}

	/**
	 * @dataProvider provideReacquireAcquire
	 * @param bool $wanCache Should a real WANObjectCache be used or one backed by an EmptyBagOStuff
	 * @param int $insertCalls Number of times the insert DB method will be called
	 * @param int $selectCalls Number of times the select DB method will be called
	 * @param string[] $existingValues to be added to the db table
	 * @param string $name name to acquire
	 * @param int $expectedId the id we expect the name to have
	 */
	public function testReacquireAcquire(
		$wanCache,
		$insertCalls,
		$selectCalls,
		$existingValues,
		$name,
		$expectedId
	) {
		$this->populateTable( $existingValues );
		$store = $this->getNameTableSqlStore( $wanCache, $insertCalls, $selectCalls );

		// Some names will not initially exist
		$result = $store->reacquire( $name );
		if ( !$insertCalls > 0 ) {
			$this->assertSame( $expectedId, $result );
		} else {
			$this->assertNull( $result );
		}

		// All names should return their id here
		$this->assertSame( $expectedId, $store->acquire( $name ) );

		// acquire inserted these names, so now everything should exist with reacquire
		$this->assertSame( $expectedId, $store->reacquire( $name ) );

		// If we reset the class cache, this second reacquire will either hit the WAN cache or DB
		TestingAccessWrapper::newFromObject( $store )->tableCache = null;
		$this->assertSame( $expectedId, $store->reacquire( $name ) );
	}

}
