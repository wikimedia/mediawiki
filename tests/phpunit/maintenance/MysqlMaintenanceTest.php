<?php

namespace MediaWiki\Tests\Maintenance;

use MysqlMaintenance;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers \MysqlMaintenance
 * @group Database
 * @author Dreamy Jazz
 */
class MysqlMaintenanceTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return MysqlMaintenance::class;
	}

	public function setUp(): void {
		parent::setUp();
		if ( $this->getDb()->getType() !== 'mysql' ) {
			$this->markTestSkipped( 'This test requires the DB to be either MariaDB or MySQL' );
		}
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Host unknown' => [ [ 'host' => 'unknown-host' ], '/Error: Host not configured/' ],
			'Cluster unknown' => [ [ 'cluster' => 'unknown-cluster' ], '/Error: invalid cluster/' ],
		];
	}

	public function testExecuteWhenFailsToGetReaderIndex() {
		// Mock LoadBalancer::getReaderIndex to always return false, to test when no reader index could be obtained.
		$mockLoadBalancer = $this->createMock( LoadBalancer::class );
		$mockLoadBalancer->method( 'getReaderIndex' )
			->willReturn( false );
		$mockLoadBalancerFactory = $this->createMock( LBFactory::class );
		$mockLoadBalancerFactory->method( 'getMainLB' )
			->willReturn( $mockLoadBalancer );
		$this->setService( 'DBLoadBalancerFactory', $mockLoadBalancerFactory );

		$this->testExecuteForFatalError( [], '/Error: unable to get reader index/' );
	}

	public function testExecuteForListHosts() {
		$this->maintenance->setOption( 'list-hosts', 1 );
		$this->maintenance->execute();
		$actualOutput = $this->getActualOutputForAssertion();

		$lb = $this->getServiceContainer()->getDBLoadBalancer();
		$serverCount = $lb->getServerCount();
		for ( $index = 0; $index < $serverCount; ++$index ) {
			$this->assertStringContainsString( $lb->getServerName( $index ), $actualOutput );
		}
	}

	public function testExecuteWhenDBTypeNotMysql() {
		// Mock LoadBalancer::getServerType to return postgres to simulate not using a MySQL DB
		$mockLoadBalancer = $this->createMock( LoadBalancer::class );
		$mockLoadBalancer->method( 'getServerType' )
			->willReturn( 'postgres' );
		$mockLoadBalancer->method( 'getReaderIndex' )
			->willReturn( $this->getDb()->getServerName() );
		$mockLoadBalancerFactory = $this->createMock( LBFactory::class );
		$mockLoadBalancerFactory->method( 'getMainLB' )
			->willReturn( $mockLoadBalancer );
		$this->setService( 'DBLoadBalancerFactory', $mockLoadBalancerFactory );

		$this->testExecuteForFatalError( [], '/Error: this script only works with MySQL\/MariaDB/' );
	}
}
