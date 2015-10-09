<?php

/**
 * @group GlobalFunctions
 * @covers ::wfGetCaller
 */
class WfGetCallerTest extends MediaWikiTestCase {
	public function testZero() {
		$this->assertEquals( 'WfGetCallerTest->testZero', wfGetCaller( 1 ) );
	}

	function callerOne() {
		return wfGetCaller();
	}

	public function testOne() {
		$this->assertEquals( 'WfGetCallerTest->testOne', self::callerOne() );
	}

	static function intermediateFunction( $level = 2, $n = 0 ) {
		if ( $n > 0 ) {
			return self::intermediateFunction( $level, $n - 1 );
		}

		return wfGetCaller( $level );
	}

	public function testTwo() {
		$this->assertEquals( 'WfGetCallerTest->testTwo', self::intermediateFunction() );
	}

	public function testN() {
		$this->assertEquals( 'WfGetCallerTest->testN', self::intermediateFunction( 2, 0 ) );
		$this->assertEquals(
			'WfGetCallerTest::intermediateFunction',
			self::intermediateFunction( 1, 0 )
		);

		for ( $i = 0; $i < 10; $i++ ) {
			$this->assertEquals(
				'WfGetCallerTest::intermediateFunction',
				self::intermediateFunction( $i + 1, $i )
			);
		}
	}
}
