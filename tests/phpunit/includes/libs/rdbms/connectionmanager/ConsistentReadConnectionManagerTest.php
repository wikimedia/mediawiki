<?php

namespace Wikimedia\Rdbms;

use IDatabase;
use LoadBalancer;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @covers Wikimedia\Rdbms\SessionConsistentConnectionManager
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class ConsistentReadConnectionManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @return IDatabase|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getIDatabaseMock() {
		return $this->getMock( IDatabase::class );
	}

	/**
	 * @return LoadBalancer|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getLoadBalancerMock() {
		$lb = $this->getMockBuilder( LoadBalancer::class )
			->disableOriginalConstructor()
			->getMock();

		return $lb;
	}

	public function testGetReadConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA )
			->will( $this->returnValue( $database ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnectionReturnsWriteDbOnForceMatser() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER )
			->will( $this->returnValue( $database ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->prepareForUpdates();
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER )
			->will( $this->returnValue( $database ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testForceMaster() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_MASTER )
			->will( $this->returnValue( $database ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->prepareForUpdates();
		$manager->getReadConnection();
	}

	public function testReleaseConnection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->will( $this->returnValue( null ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->releaseConnection( $database );
	}

	public function testBeginAtomicSection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->exactly( 2 ) )
			->method( 'getConnection' )
			->with( DB_MASTER )
			->will( $this->returnValue( $database ) );

		$database->expects( $this->once() )
			->method( 'startAtomic' )
			->will( $this->returnValue( null ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->beginAtomicSection( 'TEST' );

		// Should also ask for a DB_MASTER connection.
		// This is asserted by the $lb mock.
		$manager->getReadConnection();
	}

	public function testCommitAtomicSection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->will( $this->returnValue( null ) );

		$database->expects( $this->once() )
			->method( 'endAtomic' )
			->will( $this->returnValue( null ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->commitAtomicSection( $database, 'TEST' );
	}

	public function testRollbackAtomicSection() {
		$database = $this->getIDatabaseMock();
		$lb = $this->getLoadBalancerMock();

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->will( $this->returnValue( null ) );

		$database->expects( $this->once() )
			->method( 'rollback' )
			->will( $this->returnValue( null ) );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->rollbackAtomicSection( $database, 'TEST' );
	}

}
