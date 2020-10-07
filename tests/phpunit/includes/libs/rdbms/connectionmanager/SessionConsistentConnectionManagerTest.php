<?php

namespace Wikimedia\Tests\Rdbms;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\SessionConsistentConnectionManager;

/**
 * @covers Wikimedia\Rdbms\SessionConsistentConnectionManager
 *
 * @author Daniel Kinzler
 */
class SessionConsistentConnectionManagerTest extends TestCase {
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
