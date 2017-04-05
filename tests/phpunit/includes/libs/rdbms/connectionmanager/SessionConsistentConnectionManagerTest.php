<?php

namespace Wikimedia\Tests\Rdbms;

use IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use PHPUnit_Framework_MockObject_MockObject;
use Wikimedia\Rdbms\SessionConsistentConnectionManager;

/**
 * @covers Wikimedia\Rdbms\SessionConsistentConnectionManager
 *
 * @license GPL-2.0+
 * @author Daniel Kinzler
 */
class SessionConsistentConnectionManagerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @return IDatabase|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getIDatabaseMock() {
		return $this->getMockBuilder( IDatabase::class )
			->getMock();
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
}
