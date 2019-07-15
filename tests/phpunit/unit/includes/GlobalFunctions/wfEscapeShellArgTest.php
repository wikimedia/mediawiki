<?php

/**
 * @group GlobalFunctions
 * @covers ::wfEscapeShellArg
 */
class WfEscapeShellArgTest extends MediaWikiUnitTestCase {
	public function testSingleInput() {
		if ( wfIsWindows() ) {
			$expected = '"blah"';
		} else {
			$expected = "'blah'";
		}

		$actual = wfEscapeShellArg( 'blah' );

		$this->assertEquals( $expected, $actual );
	}

	public function testMultipleArgs() {
		if ( wfIsWindows() ) {
			$expected = '"foo" "bar" "baz"';
		} else {
			$expected = "'foo' 'bar' 'baz'";
		}

		$actual = wfEscapeShellArg( 'foo', 'bar', 'baz' );

		$this->assertEquals( $expected, $actual );
	}

	public function testMultipleArgsAsArray() {
		if ( wfIsWindows() ) {
			$expected = '"foo" "bar" "baz"';
		} else {
			$expected = "'foo' 'bar' 'baz'";
		}

		$actual = wfEscapeShellArg( [ 'foo', 'bar', 'baz' ] );

		$this->assertEquals( $expected, $actual );
	}
}
