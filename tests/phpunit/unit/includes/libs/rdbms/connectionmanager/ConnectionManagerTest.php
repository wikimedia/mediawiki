<?php

namespace Wikimedia\Tests\Rdbms;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\ConnectionManager;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers \Wikimedia\Rdbms\ConnectionManager
 *
 * @author Daniel Kinzler
 */
class ConnectionManagerTest extends TestCase {
	/**
	 * @return IDatabase&MockObject
	 */
	private function getIDatabaseMock() {
		return $this->createMock( IDatabase::class );
	}

	/**
	 * @return DBConnRef&MockObject
	 */
	private function getDBConnRefMock() {
		return $this->createMock( DBConnRef::class );
	}

	/**
	 * @return LoadBalancer&MockObject
	 */
	private function getLoadBalancerMock() {
		return $this->createMock( LoadBalancer::class );
	}

	public function testGetReadConnection_nullGroups() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnection_withGroupsAndFlags() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName', ILoadBalancer::CONN_SILENCE_ERRORS )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection( [ 'group2' ], ILoadBalancer::CONN_SILENCE_ERRORS );

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection_withFlags() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName', ILoadBalancer::CONN_TRX_AUTOCOMMIT )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnection( ILoadBalancer::CONN_TRX_AUTOCOMMIT );

		$this->assertSame( $database, $actual );
	}

	public function testReleaseConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->willReturn( null );

		$manager = new ConnectionManager( $lb );
		$manager->releaseConnection( $database );
	}

	public function testGetReadConnectionRef_nullGroups() {
		$database = $this->getDBConnRefMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnectionRef();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnectionRef_withGroups() {
		$database = $this->getDBConnRefMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnectionRef( [ 'group2' ] );

		$this->assertSame( $database, $actual );
	}

	public function testGetLazyWriteConnectionRef() {
		$database = $this->createMock( DBConnRef::class );
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getLazyWriteConnectionRef();

		$this->assertSame( $database, $actual );
	}

	public function testGetLazyReadConnectionRef_nullGroups() {
		$database = $this->createMock( DBConnRef::class );
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getLazyReadConnectionRef();

		$this->assertSame( $database, $actual );
	}

	public function testGetLazyReadConnectionRef_withGroups() {
		$database = $this->createMock( DBConnRef::class );
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getLazyReadConnectionRef( [ 'group2' ] );

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnectionRef() {
		$database = $this->getDBConnRefMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_PRIMARY, [ 'group1' ], 'someDbName' )
			->willReturn( $database );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnectionRef();

		$this->assertSame( $database, $actual );
	}

}
