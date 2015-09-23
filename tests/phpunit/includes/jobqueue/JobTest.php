<?php

/**
 * @author Adam Shorland
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
		$mockToStringObj = $this->getMock( 'stdClass', array( '__toString' ) );
		$mockToStringObj->expects( $this->any() )
			->method( '__toString' )
			->will( $this->returnValue( '{STRING_OBJ_VAL}' ) );

		return array(
			array(
				$this->getMockJob( false ),
				'someCommand '
			),
			array(
				$this->getMockJob( array( 'key' => 'val' ) ),
				'someCommand  key=val'
			),
			array(
				$this->getMockJob( array( 'key' => array( 'inkey' => 'inval' ) ) ),
				'someCommand  key={"inkey":"inval"}'
			),
			array(
				$this->getMockJob( array( 'val1' ) ),
				'someCommand  0=val1'
			),
			array(
				$this->getMockJob( array( 'val1', 'val2' ) ),
				'someCommand  0=val1 1=val2'
			),
			array(
				$this->getMockJob( array( new stdClass() ) ),
				'someCommand  0=object(stdClass)'
			),
			array(
				$this->getMockJob( array( $mockToStringObj ) ),
				'someCommand  0={STRING_OBJ_VAL}'
			),
		);
	}

	public function getMockJob( $params ) {
		$mock = $this->getMockForAbstractClass(
			'Job',
			array( 'someCommand', new Title(), $params ),
			'SomeJob'
		);
		return $mock;
	}

}
