<?php

/**
 * @group GlobalFunctions
 * @covers ::wfShellExec
 */
class WfShellExecTest extends MediaWikiTestCase {
	public function testBug67870() {
		if ( wfIsWindows() ) {
			$this->markTestIncomplete( __METHOD__ . 'This test only works in POSIX environments' );
		}
		// Test several times because it involves a race condition that may randomly succeed or fail
		for ( $i = 0; $i < 10; $i++ ) {
			$output = wfShellExec( 'printf "%-333333s" "*"' );
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}
}
