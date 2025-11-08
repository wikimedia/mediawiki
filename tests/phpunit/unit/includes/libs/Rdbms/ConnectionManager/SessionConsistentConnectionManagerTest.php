<?php

namespace Wikimedia\Tests\Rdbms;

use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\SessionConsistentConnectionManager;

/**
 * @covers \Wikimedia\Rdbms\SessionConsistentConnectionManager
 *
 * @author Daniel Kinzler
 */
class SessionConsistentConnectionManagerTest extends MediaWikiUnitTestCase {

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
}
