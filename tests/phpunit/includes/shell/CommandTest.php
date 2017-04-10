<?php

use MediaWiki\Shell\Command;

/**
 * @group Shell
 */
class CommandTest extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testDestruct() {
		if ( defined( 'HHVM_VERSION' ) ) {
			$this->markTestSkipped( 'destructors are unreliable in HHVM' );
		}
		$command = new Command();
		$command->params( 'true' );
	}

	private function requirePosix() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( $commandInput, $expectedExitCode, $expectedOutput ) {
		$this->requirePosix();

		$command = new Command();
		$result = $command
			->params( $commandInput )
			->execute();

		$this->assertSame( $expectedExitCode, $result->getExitCode() );
		$this->assertSame( $expectedOutput, $result->getStdout() );
	}

	public function provideExecute() {
		return [
			'success status' => [ 'true', 0, '' ],
			'failure status' => [ 'false', 1, '' ],
			'output' => [ [ 'echo', '-n', 'x', '>', 'y' ], 0, 'x > y' ],
		];
	}

	public function testEnvironment() {
		$this->requirePosix();

		$command = new Command();
		$result = $command
			->params( [ 'printenv', 'FOO' ] )
			->environment( [ 'FOO' => 'bar' ] )
			->execute();
		$this->assertSame( "bar\n", $result->getStdout() );
	}

	public function testOutput() {
		global $IP;

		$this->requirePosix();

		$command = new Command();
		$result = $command
			->params( [ 'ls', "$IP/index.php" ] )
			->execute();
		$this->assertSame( "$IP/index.php", trim( $result->getStdout() ) );

		$command = new Command();
		$result = $command
			->params( [ 'ls', 'index.php', 'no-such-file' ] )
			->includeStderr()
			->execute();
		$this->assertRegExp( '/^.+no-such-file.*$/m', $result->getStdout() );
	}

	public function testT69870() {
		$commandLine = wfIsWindows()
			// 333 = 331 + CRLF
			? ( 'for /l %i in (1, 1, 1001) do @echo ' . str_repeat( '*', 331 ) )
			: 'printf "%-333333s" "*"';

		// Test several times because it involves a race condition that may randomly succeed or fail
		for ( $i = 0; $i < 10; $i++ ) {
			$command = new Command();
			$output = $command->unsafeParams( $commandLine )
				->execute()
				->getStdout();
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}

	/**
	 * Stress MediaWiki\Shell\Command::execute() when there are too much opened files.
	 *
	 * WARNING: executing this test with PHP (no issue with HHVM) under the requirements (Linux and
	 * soft limit of opened files greater than 1536) and without the associated timeout from T72357
	 * will unconditionally leads to an indefinite halt of the tests execution.
	 *
	 * @requires OS Linux
	 * @medium
	 */
	public function testT72357() {
		$FD_SETSIZE = 1024; // default value in most PHP distributions (set before compilation)
		$ulimit = ( new Command() )->unsafeParams( 'ulimit -Sn' )->limits( [ 'time' => 1 ] )->execute();
		if ( $ulimit->getExitCode() == 0 ) {
			$limitFiles = intval( trim( $ulimit->getStdout() ) );
		}

		// `ulimit -Sn` must be greater than FD_SETSIZE and it is better to have some margin for
		// opened PHP files themselves
		if ( $limitFiles < $FD_SETSIZE + 512 ) {
			$this->markTestSkipped(
				'Environment of bug T72357 cannot be reproduced; you have to ' .
				'execute `ulimit -Sn ' . ( $FD_SETSIZE + 512 ) . '` before running the tests.'
			);
		}

		// Prepare the tested function
		$command = ( new Command() )->unsafeParams( 'echo' );

		// Open a lot of files
		$files = [];
		for ( $i = 0; $i < $FD_SETSIZE + 1; $i++ ) {
			$files[$i] = fopen( __FILE__, 'r' );
		}
		$numFiles = count( $files );

		// Execute the test
		// A more difficult test would be 'echo ok' - it currently does not work (see T72357)
		$result = $command->execute();

		// Close as soon as possible to avoid additional issues
		for ( $i = 0; $i < $numFiles; $i++ ) {
			fclose( $files[$i] );
		}

		$this->assertEquals( '', trim( $result->getStdout() ),
			'The Linux command `echo` shouldn’t return anything.'
		);
	}
}
