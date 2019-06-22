<?php

/**
 * @group GlobalFunctions
 * @covers ::wfShellExec
 */
class WfShellExecTest extends MediaWikiTestCase {
	public function testT69870() {
		if ( wfIsWindows() ) {
			// T209159: Anonymous pipe under Windows does not support asynchronous read and write,
			// and the default buffer is too small (~4K), it is easy to be blocked.
			$this->markTestSkipped(
				'T209159: Anonymous pipe under Windows cannot withstand such a large amount of data'
			);
		}

		// Test several times because it involves a race condition that may randomly succeed or fail
		for ( $i = 0; $i < 10; $i++ ) {
			$output = wfShellExec( 'printf "%-333333s" "*"' );
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}
}
