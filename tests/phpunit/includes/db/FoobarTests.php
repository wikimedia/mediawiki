<?php
class FoobarTest extends MediaWikiTestCase {

	public function testPtHeartBeat() {

		$expectedLag = 3;

		$ret = new stdClass();
		$ret->time = "-{$expectedLag} seconds";

		$mockResult = $this->getMockBuilder( 'ResultWrapper' )
			->disableOriginalConstructor()
			->getMock();
		$mockResult->expects( $this->any() )
			->method( 'fetchObject' )
			->will( $this->returnValue( $ret ) );

        $mockDB = $this->getMockBuilder( 'DatabaseMysql' )
            ->disableOriginalConstructor()
			->setMethods( array( 'query', 'getLBInfo', 'buildLike', 'anyString' ) )
            ->getMock();
        $mockDB->expects( $this->any() )
            ->method( 'query' )
			->will(
				$this->returnValue( $mockResult )
			);
		$mockDB->expects( $this->any() )
			->method( 'getLBInfo' )
			->will( $this->returnValue( 'masterhost' ) );

		$mockDB->lagDetectionMethod = 'pt-heartbeat';

		$lag = $mockDB->getLag();
		$this->assertGreaterThan( $expectedLag + '0.1', $lag );
		$this->assertLessThan( $expectedLag + '0.1', $lag );

	}

}
