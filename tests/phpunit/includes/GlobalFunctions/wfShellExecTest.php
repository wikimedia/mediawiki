<?php

/**
 * @group GlobalFunctions
 * @covers ::wfShellExec
 */
class WfShellExecTest extends MediaWikiTestCase {
	public function testBug67870() {
		$command = wfIsWindows()
			// 333 = 331 + CRLF
			? ( 'for /l %i in (1, 1, 1001) do @echo ' . str_repeat( '*', 331 ) )
			: 'printf "%-333333s" "*"';

		// Test several times because it involves a race condition that may randomly succeed or fail
		for ( $i = 0; $i < 10; $i++ ) {
			$output = wfShellExec( $command );
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}

	/**
	 * Stress wfShellExec when there are too much opened files.
	 *
	 * WARNING: executing this test with PHP (no issue with HHVM) under the requirements (Linux and
	 * soft limit of opened files greater than 1536) and without the associated timeout from T72357
	 * will unconditionally leads to an indefinite halt of the tests execution.
	 *
	 * @requires OS Linux
	 */
	public function testBugT72357() {
		$FD_SETSIZE = 1024; // default value in most PHP distributions (set before compilation)
		$limitFiles = intval( trim( wfShellExec( 'ulimit -Sn' ) ) );

		// `ulimit -Sn` must be greater than FD_SETSIZE and it is better to have some margin for
		// opened PHP files themselves
		if( $limitFiles < $FD_SETSIZE + 512 ) {
			$this->markTestSkipped(
				'Environment of bug T72357 cannot be reproduced; you have to ' .
				'execute `ulimit -Sn ' . ($FD_SETSIZE+512) . '` before running the tests.'
			);
		}

		$files = array();
		for( $i = 0; $i < $FD_SETSIZE + 1; $i++ ) {
			$files[$i] = fopen( __FILE__, 'r' );
		}

		// Tested function
		// A more difficult test would be 'echo ok' - it currently does not work (see T72357)
		$result = wfShellExec( 'echo' );

		// Close as soon as possible to avoid additional issues
		for( $i = 0; $i < count( $files ); $i++ ) {
			fclose( $files[$i] );
		}

		$this->assertEquals( '', trim( $result ) );
	}
}
