<?php

namespace Wikimedia\Tests\Rdbms;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\ConnectionManager;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers Wikimedia\Rdbms\ConnectionManager
 *
 * @author Daniel Kinzler
 */
class ConnectionManagerTest extends TestCase {
	/**
	 * @return IDatabase|MockObject
	 */
	private function getIDatabaseMock() {
		return $this->getMockBuilder( IDatabase::class )
			->getMock();
	}

	/**
	 * @return LoadBalancer|MockObject
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
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnection_withGroups() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName' )
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnection( [ 'group2' ] );

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER, [ 'group1' ], 'someDbName' )
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testReleaseConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->will( $this->returnValue( null ) );

		$manager = new ConnectionManager( $lb );
		$manager->releaseConnection( $database );
	}

	public function testGetReadConnectionRef_nullGroups() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group1' ], 'someDbName' )
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnectionRef();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnectionRef_withGroups() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_REPLICA, [ 'group2' ], 'someDbName' )
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getReadConnectionRef( [ 'group2' ] );

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnectionRef() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnectionRef' )
			->with( DB_MASTER, [ 'group1' ], 'someDbName' )
			->will( $this->returnValue( $database ) );

		$manager = new ConnectionManager( $lb, 'someDbName', [ 'group1' ] );
		$actual = $manager->getWriteConnectionRef();

		$this->assertSame( $database, $actual );
	}

}
