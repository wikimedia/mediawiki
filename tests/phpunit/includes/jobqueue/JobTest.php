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
