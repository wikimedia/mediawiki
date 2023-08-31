<?php

use MediaWiki\MediaWikiServices;

/**
 * Verify that the database is unavailable in integration tests which didn't
 * indicate otherwise.
 * @see MediaWikiIntegrationTestCase::needsDB()
 * @coversNothing
 */
class MediaWikiIntegrationTestCaseNoDbTest extends MediaWikiIntegrationTestCase {

	public function testDBLoadBalancerFactory() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Database backend disabled' );
		MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->getReplicaDatabase();
	}

	public function testDBLoadBalancer() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Database backend disabled' );
		MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
	}

	public function testServiceContainer() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'Database backend disabled' );
		$this->getServiceContainer()->getDBLoadBalancer()->getConnection( DB_REPLICA );
	}

	public function testGetDb() {
		$this->expectException( LogicException::class );
		$this->getDb();
	}

	public function testDbProp() {
		$this->assertNull( $this->db );
	}

	public function testAllowedMethods() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->getMainLB();
		$lbFactory->getLocalDomainID();
		MediaWikiServices::getInstance()->getDBLoadBalancer()->getLocalDomainID();
		$this->getServiceContainer()->getReadOnlyMode()->isReadOnly();
		$this->addToAssertionCount( 1 );
	}
}
