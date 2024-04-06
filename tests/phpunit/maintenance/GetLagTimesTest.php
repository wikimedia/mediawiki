<?php

namespace MediaWiki\Tests\Maintenance;

use IBufferingStatsdDataFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;

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

	public function testSendsToStatsd() {
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

		// The Statsd service
		$gaugeArgs = [
			[ 'loadbalancer.lag.cluster1.localhost', 1000 ],
			[ 'loadbalancer.lag.cluster2.localhost', 0 ],
			[ 'loadbalancer.lag.external.localhost', 14000 ],
		];
		$stats = $this->createMock( IBufferingStatsdDataFactory::class );
		$stats->expects( $this->exactly( 3 ) )
			->method( 'gauge' )
			->willReturnCallback( function ( $key, $value ) use ( &$gaugeArgs ): void {
				[ $nextKey, $nextValue ] = array_shift( $gaugeArgs );
				$this->assertSame( $nextKey, $key );
				$this->assertSame( $nextValue, $value );
			} );

		$this->setService( 'StatsdDataFactory', $stats );

		$this->maintenance->setOption( 'report', true );
		$this->maintenance->execute();
	}

}
