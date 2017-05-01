<?php

/**
 * @author Addshore
 */
class JobTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideTestToString
	 *
	 * @param Job $job
	 * @param string $expected
	 *
	 * @covers Job::toString
	 */
	public function testToString( $job, $expected ) {
		$this->assertEquals( $expected, $job->toString() );
	}

	public function provideTestToString() {
		$mockToStringObj = $this->getMockBuilder( 'stdClass' )
			->setMethods( [ '__toString' ] )->getMock();
		$mockToStringObj->expects( $this->any() )
			->method( '__toString' )
			->will( $this->returnValue( '{STRING_OBJ_VAL}' ) );

		$requestId = 'requestId=' . WebRequest::getRequestId();

		return [
			[
				$this->getMockJob( false ),
				'someCommand  ' . $requestId
			],
			[
				$this->getMockJob( [ 'key' => 'val' ] ),
				'someCommand  key=val ' . $requestId
			],
			[
				$this->getMockJob( [ 'key' => [ 'inkey' => 'inval' ] ] ),
				'someCommand  key={"inkey":"inval"} ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1' ] ),
				'someCommand  0=val1 ' . $requestId
			],
			[
				$this->getMockJob( [ 'val1', 'val2' ] ),
				'someCommand  0=val1 1=val2 ' . $requestId
			],
			[
				$this->getMockJob( [ new stdClass() ] ),
				'someCommand  0=object(stdClass) ' . $requestId
			],
			[
				$this->getMockJob( [ $mockToStringObj ] ),
				'someCommand  0={STRING_OBJ_VAL} ' . $requestId
			],
			[
				$this->getMockJob( [
					"pages" => [
						"932737" => [
							0,
							"Robert_James_Waller"
						]
					],
					"rootJobSignature" => "45868e99bba89064e4483743ebb9b682ef95c1a7",
					"rootJobTimestamp" => "20160309110158",
					"masterPos" => [
						"file" => "db1023-bin.001288",
						"pos" => "308257743",
						"asOfTime" => 1457521464.3814
					],
					"triggeredRecursive" => true
				] ),
				'someCommand  pages={"932737":[0,"Robert_James_Waller"]} ' .
				'rootJobSignature=45868e99bba89064e4483743ebb9b682ef95c1a7 ' .
				'rootJobTimestamp=20160309110158 masterPos=' .
				'{"file":"db1023-bin.001288","pos":"308257743","asOfTime":1457521464.3814} ' .
				'triggeredRecursive=1 ' .
				$requestId
			],
		];
	}

	public function getMockJob( $params ) {
		$mock = $this->getMockForAbstractClass(
			'Job',
			[ 'someCommand', new Title(), $params ],
			'SomeJob'
		);
		return $mock;
	}

}
