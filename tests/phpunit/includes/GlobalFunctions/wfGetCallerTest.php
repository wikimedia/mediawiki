<?php

class wfGetCaller extends MediaWikiTestCase {

	function testZero() {
		$this->assertEquals( __METHOD__, wfGetCaller( 1 ) );
	}

	function callerOne() {
		return wfGetCaller();
	}

	function testOne() {
		$this->assertEquals( "wfGetCaller::testOne", self::callerOne() );
	}

	function intermediateFunction( $level = 2, $n = 0 ) {
		if ( $n > 0 )
			return self::intermediateFunction( $level, $n - 1 );
		return wfGetCaller( $level );
	}

	function testTwo() {
		$this->assertEquals( "wfGetCaller::testTwo", self::intermediateFunction() );
	}

	function testN() {
		$this->assertEquals( "wfGetCaller::testN", self::intermediateFunction( 2, 0 ) );
		$this->assertEquals( "wfGetCaller::intermediateFunction", self::intermediateFunction( 1, 0 ) );

		for ($i=0; $i < 10; $i++)
			$this->assertEquals( "wfGetCaller::intermediateFunction", self::intermediateFunction( $i + 1, $i ) );
	}
}

