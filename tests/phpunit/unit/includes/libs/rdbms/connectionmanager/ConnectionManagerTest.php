<?php

namespace Wikimedia\Tests\Rdbms;

use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\ConnectionManager;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers \Wikimedia\Rdbms\ConnectionManager
 *
 * @author Daniel Kinzler
 */
class ConnectionManagerTest extends MediaWikiUnitTestCase {

	public function testGetReadConnection_nullGroups() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnection_withGroupsAndFlags() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName', ILoadBalancer::CONN_SILENCE_ERRORS )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection( [ 'group2' ], ILoadBalancer::CONN_SILENCE_ERRORS );

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection_withFlags() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName', ILoadBalancer::CONN_TRX_AUTOCOMMIT )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnection( ILoadBalancer::CONN_TRX_AUTOCOMMIT );

		$this->assertSame( $database, $actual );
	}

}
