<?php

namespace Wikimedia\Tests\Rdbms;

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

	public function testGetReadConnection() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_REPLICA )
			->willReturn( $database );

		$manager = new SessionConsistentConnectionManager( $lb );
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetReadConnectionReturnsWriteDbOnForceMaster() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY )
			->willReturn( $database );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->prepareForUpdates();
		$actual = $manager->getReadConnection();

		$this->assertSame( $database, $actual );
	}

	public function testGetWriteConnection() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY )
			->willReturn( $database );

		$manager = new SessionConsistentConnectionManager( $lb );
		$actual = $manager->getWriteConnection();

		$this->assertSame( $database, $actual );
	}

	public function testForceMaster() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'getConnection' )
			->with( DB_PRIMARY )
			->willReturn( $database );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->prepareForUpdates();
		$manager->getReadConnection();
	}

	public function testReleaseConnection() {
		$database = $this->createMock( IDatabase::class );
		$lb = $this->createMock( LoadBalancer::class );

		$lb->expects( $this->once() )
			->method( 'reuseConnection' )
			->with( $database )
			->willReturn( null );

		$manager = new SessionConsistentConnectionManager( $lb );
		$manager->releaseConnection( $database );
	}
}
