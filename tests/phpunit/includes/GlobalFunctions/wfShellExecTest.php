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
}
