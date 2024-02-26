<?php

namespace MediaWiki\Tests\Maintenance;

use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers \DatabaseLag
 * @group Maintenance
 */
class DatabaseLagTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return \DatabaseLag::class;
	}

	public static function provideLagTimes() {
		return [
			'No lag' => [ 0, 'db-nolag-01', '/^db-nolag-01\s+0$/m' ],
			'Some lag' => [ 42, 'db-somelag-02', '/^db-somelag-02\s+42$/m' ],
			'Not replicating' => [
				false, 'db-not-replicating-03',
				'/db-not-replicating-03\s+replication stopped or errored$/m'
			],
		];
	}

	/**
	 * @dataProvider provideLagTimes
	 */
	public function testReportedOutput( $lag, $servername, $expected ) {
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getLagTimes' )->willReturn( [ $lag ] );
		$lb->method( 'getServerName' )->willReturn( $servername );

		$this->setService( 'DBLoadBalancer', $lb );

		$this->maintenance->setOption( '-r', false );
		$this->maintenance->execute();

		$this->maintenance->setOption( '-r', true );
		$this->maintenance->stopReporting = true;
		$this->maintenance->execute();

		$this->expectOutputRegex( $expected );
	}

}
