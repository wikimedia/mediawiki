<?php

/**
 * @covers LoadMonitorMySQL
 */
class LoadMonitorMySQLTest extends PHPUnit_Framework_TestCase {
	public static function provide_testGetLagTimes() {
		return [
			[ 0, [ 0 => 0, 1 => 0 ] ],
			[ 3.5, [ 0 => 0, 1 => 3.5 ] ],
			[ false, [ 0 => 0, 1 => false ] ]
		];
	}

	/**
	 * @dataProvider provide_testGetLagTimes
	 */
	public function testGetLagTimes( $lag, $expected ) {
		$lb = $this->getMockLoadBalancer();
		/** @var IDatabase|PHPUnit_Framework_MockObject_MockObject $conn */
		$conn = $lb->getConnection( DB_REPLICA );
		$conn->expects( $this->any() )->method( 'getLag' )->will( $this->returnValue( $lag ) );
		$conn->expects( $this->any() )->method( 'query' )->with( 'SHOW STATUS' )
			->will( $this->returnValue(
				new FakeResultWrapper( [ (object)[
					'Uptime' => 1e6,
					'Innodb_buffer_pool_pages_data' => 4e5,
					'Innodb_buffer_pool_pages_total' => 5e5
				] ] )
			) );
		$lm = new LoadMonitorMySQL(
			$lb,
			new HashBagOStuff(),
			new HashBagOStuff()
		);

		$this->assertEquals( $expected, $lm->getLagTimes( [ 0, 1 ], '' ) );
	}

	public static function provide_testScaleLoads() {
		return [
			[ 10, 40000, 50000, [ 0 => 0, 1 => 450 ] ],
			[ 1000, 40000, 50000, [ 0 => 0, 1 => 463 ] ],
			[ 86400, 40000, 50000, [ 0 => 0, 1 => 500 ] ],
			[ 10, 3000, 50000, [ 0 => 0, 1 => 450 ] ],
			[ 1000, 3000, 50000, [ 0 => 0, 1 => 454 ] ],
			[ 86400, 3000, 50000, [ 0 => 0, 1 => 465 ] ],
		];
	}

	/**
	 * @dataProvider provide_testScaleLoads
	 */
	public function testScaleLoads( $uptime, $pagesUsed, $pagesAll, $expected ) {
		$lb = $this->getMockLoadBalancer();
		/** @var IDatabase|PHPUnit_Framework_MockObject_MockObject $conn */
		$conn = $lb->getConnection( DB_REPLICA );
		$conn->expects( $this->any() )->method( 'getLag' )->will( $this->returnValue( 0 ) );
		$conn->expects( $this->any() )->method( 'query' )->with( 'SHOW STATUS' )
			->will( $this->returnValue(
				new FakeResultWrapper( [ (object)[
					'Uptime' => $uptime,
					'Innodb_buffer_pool_pages_data' => $pagesUsed,
					'Innodb_buffer_pool_pages_total' => $pagesAll
				] ] )
			) );
		$lm = new LoadMonitorMySQL(
			$lb,
			new HashBagOStuff(),
			new HashBagOStuff(),
			[ 'warmCacheRatio' => .2 ]
		);

		$weightByServer = [ 0 => 0, 1 => 500 ];
		$lm->scaleLoads( $weightByServer, '' );
		$weightByServer = array_map( 'intval', $weightByServer );
		$this->assertEquals( $expected, $weightByServer );
	}

	/**
	 * @return LoadBalancerSingle|PHPUnit_Framework_MockObject_MockObject
	 */
	private function getMockLoadBalancer() {
		$conn = $this->getMockBuilder( DatabaseMysql::class )
			->disableOriginalConstructor()->getMock();
		$lb = $this->getMockBuilder( LoadBalancerSingle::class )
			->disableOriginalConstructor()->getMock();
		$lb->expects( $this->any() )->method( 'getConnection' )
			->will( $this->returnValue( $conn ) );
		$lb->expects( $this->any() )->method( 'openConnection' )
			->will( $this->returnValue( $conn ) );
		$lb->expects( $this->any() )->method( 'getAnyOpenConnection' )
			->will( $this->returnValue( $conn ) );

		return $lb;
	}
}
