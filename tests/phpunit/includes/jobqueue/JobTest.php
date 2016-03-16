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
		$mockToStringObj = $this->getMock( 'stdClass', [ '__toString' ] );
		$mockToStringObj->expects( $this->any() )
			->method( '__toString' )
			->will( $this->returnValue( '{STRING_OBJ_VAL}' ) );

		return [
			[
				$this->getMockJob( false ),
				'someCommand '
			],
			[
				$this->getMockJob( [ 'key' => 'val' ] ),
				'someCommand  key=val'
			],
			[
				$this->getMockJob( [ 'key' => [ 'inkey' => 'inval' ] ] ),
				'someCommand  key={"inkey":"inval"}'
			],
			[
				$this->getMockJob( [ 'val1' ] ),
				'someCommand  0=val1'
			],
			[
				$this->getMockJob( [ 'val1', 'val2' ] ),
				'someCommand  0=val1 1=val2'
			],
			[
				$this->getMockJob( [ new stdClass() ] ),
				'someCommand  0=object(stdClass)'
			],
			[
				$this->getMockJob( [ $mockToStringObj ] ),
				'someCommand  0={STRING_OBJ_VAL}'
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
				'someCommand  pages={"932737":[0,"Robert_James_Waller"]} rootJobSignature=45868e99bba89064e4483743ebb9b682ef95c1a7 rootJobTimestamp=20160309110158 masterPos={"file":"db1023-bin.001288","pos":"308257743","asOfTime":1457521464.3814} triggeredRecursive=1'
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
