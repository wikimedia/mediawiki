<?php

namespace MediaWiki\Tests\Maintenance;

use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \GetLagTimes
 * @group Maintenance
 */
class GetLagTimesTest extends MaintenanceBaseTestCase {

	public function getMaintenanceClass() {
		return \GetLagTimes::class;
	}

	private function getLB( $lag ) {
		$lb = $this->createMock( ILoadBalancer::class );
		$lb->method( 'getServerCount' )->willReturn( 2 );
		$lb->method( 'getLagTimes' )->willReturn( [ $lag ] );
		$lb->method( 'getServerName' )->willReturn( 'localhost' );
		return $lb;
	}

	public static function provideLagTimes() {
		return [
			'No lag' => [ 0, '/localhost\s+0 $/m' ],
			'Some lag' => [ 7, '/localhost\s+7 \*{7}$/m' ],
			'More than 40 seconds lag' => [ 41, '/localhost\s+41 \*{40}$/m' ],
			'Not replicating' => [
				false, '/localhost\s+0 replication stopped or errored$/m' ],
		];
	}

	/**
	 * @dataProvider provideLagTimes
	 */
	public function testReportedOutput( $lag, $expected ) {
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getAllMainLBs' )
			->willReturn( [
				'cluster1' => $this->getLB( $lag ),
			] );
		$lbFactory->method( 'getAllExternalLBs' )->willReturn( [] );

		$this->setService( 'DBLoadBalancerFactory', $lbFactory );

		$this->maintenance->execute();

		$this->expectOutputRegex( $expected );
	}

	public function testStats() {
		$lbFactory = $this->createMock( LBFactory::class );
		$lbFactory->method( 'getAllMainLBs' )
			->willReturn( [
				'cluster1' => $this->getLB( 1 ),
				'cluster2' => $this->getLB( false ),
			] );
		$lbFactory->method( 'getAllExternalLBs' )
			->willReturn( [
				'externalCluster' => $this->getLB( 14 ),
			] );
		$this->setService( 'DBLoadBalancerFactory', $lbFactory );

		$dummyGauge = StatsFactory::newNull()->getGauge( 'dummy' );
		$sfmock = $this->createConfiguredMock( StatsFactory::class, [
			'getGauge' => $dummyGauge
		] );
		$this->setService( 'StatsFactory', $sfmock );

		$this->maintenance->setOption( 'report', true );
		$this->maintenance->execute();

		$expectedSamples = [
			// milliseconds
			[ 'cluster1.localhost', 1000.0 ],
			[ 'cluster2.localhost', 0.0 ],
			[ 'external.localhost', 14000.0 ],
			// seconds
			[ 'cluster1.localhost', 1.0 ],
			[ 'cluster2.localhost', 0.0 ],
			[ 'external.localhost', 14.0 ],
		];
		foreach ( $dummyGauge->getSamples() as $sample ) {
			$namespaced = implode( '.', $sample->getLabelValues() );
			$this->assertContains( [ $namespaced, $sample->getValue() ], $expectedSamples );
		}
	}

}
