<?php

require_once __DIR__ . "/../../../maintenance/getSlaveServer.php";

/**
 * Tests for getSlaveServer
 *
 * @group Database
 * @covers GetSlaveServer
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
	private function getServerRE() {
		if ( $this->db->getType() === 'sqlite' ) {
			// for SQLite, only the empty string is a good server name
			return '';
		}

		$octet = '([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])';
		$ip = "(($octet\.){3}$octet)";

		$label = '([a-zA-Z]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)';
		$hostname = "($label(\.$label)*)";

		return "($ip|$hostname)(:[0-9]{1,5})?";
	}

	function testPlain() {
		$gss = new GetSlaveServer();
		$gss->execute();

		$this->expectOutputRegex( "/^" . self::getServerRE() . "\n$/D" );
	}

	function testXmlDumpsBackupUseCase() {
		global $wgDBprefix;

		global $argv;
		$argv = array( null, "--globals" );

		$gss = new GetSlaveServer();
		$gss->loadParamsAndArgs();
		$gss->execute();
		$gss->globals();

		// The main answer
		$output = $this->getActualOutput();
		$firstLineEndPos = strpos( $output, "\n" );
		if ( $firstLineEndPos === false ) {
			$this->fail( "Could not find end of first line of output" );
		}
		$firstLine = substr( $output, 0, $firstLineEndPos );
		$this->assertRegExp( "/^" . self::getServerRE() . "$/D",
			$firstLine, "DB Server" );

		// xmldumps-backup relies on the wgDBprefix in the output.
		$this->expectOutputRegex( "/^[[:space:]]*\[wgDBprefix\][[:space:]]*=> "
			. $wgDBprefix . "$/m" );
	}
}
