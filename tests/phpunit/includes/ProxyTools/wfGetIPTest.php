<?php
/*
 * Unit tests for wfGetIP()
 * 
 * FIXME: r94558 cause some test to throw an exception cause
 * wgCommandLine is not set :-)  Resetting the static variable
 * enlight a bug that is hidden by a previous call to wfGetIP().
 *
 * TODO: need to cover the part dealing with XFF headers.
 */
class wfGetIPTest extends MediaWikiTestCase {

	/** helper */
	public function assertIP( $expected, $msg = '' ) {
		$this->assertEquals(
			$expected,
			wfGetIP( 'reset' ),
			$msg );
	}

	/** helper */
	public function assertIPNot( $expected, $msg = '' ) {
		$this->assertNotEquals(
			$expected,
			wfGetIP( 'reset' ),
			$msg );
	}

	function testGetLoopbackAddressWhenInCommandLine() {
		global $wgCommandLineMode;
		$save = $wgCommandLineMode;

		$wgCommandLineMode = true;
		$this->assertIP( '127.0.0.1' );

		# restore global
		$wgCommandLineMode = $save;
	}

	function testGetFromServerRemoteAddr() {
		global $_SERVER;
		$save = null;
		if( array_key_exists( 'REMOTE_ADDR', $_SERVER ) ) {
			$save = $_SERVER['REMOTE_ADDR'];
		} else {
			$clearRemoteAddr = true;
		}

		# Starting assertions
		$_SERVER['REMOTE_ADDR'] = '192.0.2.77'; # example IP - RFC 5737
		$this->assertIP( '192.0.2.77' );

		/*
		#### wfGetIP() does not support IPv6 yet :((
		$_SERVER['REMOTE_ADDR'] = '2001:db8:0:77';
		$this->assertIP( '2001:db8:0:77' );
		*/

		# restore global
		if( $clearRemoteAddr ) {
			unset( $_SERVER['REMOTE_ADDR'] );
		} else {
			$_SERVER['REMOTE_ADDR'] = $save;
		}
		# Restore internal static altered above
		wfGetIP( 'reset' );
	}

	/**
	 * @expectedException MWException
	 * @group Broken
	 * This is broken since it alter $wgCommandLineMode which is needed
	 * by our exception handler.  Until someone find a correct way to handle
	 * this case, the test should stay in the Broken group.
	 * 
	 */
	function testLackOfRemoteAddrThrowAnException() {
		global $wgCommandLineMode;
		$save = $wgCommandLineMode;

		# PHPUnit runs from the command line
		$wgCommandLineMode = false;
		# Next call throw an exception about lacking an IP
		wfGetIP( 'reset' );

		$wgCommandLineMode = $save;
	}
}
