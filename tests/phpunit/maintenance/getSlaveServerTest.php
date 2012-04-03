<?php
global $IP;
require_once( "$IP/maintenance/getSlaveServer.php" );

/**
 * Mock for the input/output of GetSlaveServer
 *
 * GetSlaveServer internally tries to access stdout directly. We mock those aspects
 * for testing.
 */
class SemiMockedGetSlaveServer extends GetSlaveServer {

	/**
	 * @var String The concatenated output that got passed to the output method
	 */
	private $mockAccumulatedOutput = "";

	/**
	 * Gets the accumulated output that has been passed through the output
	 * method.
	 *
	 * @return String
	 */
	function mockGetAccumulatedOutput()
	{
		return $this->mockAccumulatedOutput;
	}

	// -----------------------------------------------------------------
	// Mocked functions from GetSlaveServer follow.

	function output( $out, $channel = null )
	{
		if ( $channel !== null ) {
			throw new PHPUnit_Framework_ExpectationFailedException(
				"Tried to write to non-null channel" );
		}

		print( $out );

		$this->mockAccumulatedOutput .= $out;
	}

}

/**
 * Tests for getSlaveServer
 *
 * @group Database
 */
class GetSlaveServerTest extends MediaWikiTestCase {

	/**
	 * Yields a regular expression that matches a good DB server name
	 *
	 * It matches IPs or hostnames, both optionally followed by a
	 * port specification
	 *
	 * @return String the regular expression
	 */
	private static function getServerRE() {
		$octet = '([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])';
		$ip = "(($octet\.){3}$octet)";

		$label = '([a-zA-Z]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)';
		$hostname = "($label(\.$label)*)";

		return "($ip|$hostname)(:[0-9]{1,5})?";
	}

	function testPlain() {
		$gss = new SemiMockedGetSlaveServer();
		$gss->execute();

		$this->assertEquals( $gss->mockGetAccumulatedOutput(),
			$this->getActualOutput(), "All output happened via output method" );

		$this->expectOutputRegex( "/^" . self::getServerRE() . "\n$/D" );
	}

	function testXmlDumpsBackupUseCase() {
		global $wgDBprefix;

		global $argv;
		$argv = array( null, "--globals" );

		$gss = new SemiMockedGetSlaveServer();
		$gss->loadParamsAndArgs();
		$gss->execute();
		$gss->globals();

		// The main answer
		$this->assertRegExp( "/^" . self::getServerRE() . "\n$/D",
			$gss->mockGetAccumulatedOutput(), "DB Server" );

		// xmldumps-backup relies on the wgDBprefix in the output.
		$this->expectOutputRegex( "/^[[:space:]]*\[wgDBprefix\][[:space:]]*=> "
			. $wgDBprefix . "$/m" );
	}


}
