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

	public function testStdout() {
		$this->requirePosix();

		$command = new Command();

		$result = $command
			->params( 'bash', '-c', 'echo ThisIsStderr 1>&2' )
			->execute();

		$this->assertNotContains( 'ThisIsStderr', $result->getStdout() );
		$this->assertEquals( "ThisIsStderr\n", $result->getStderr() );
	}

	public function testStdoutRedirection() {
		$this->requirePosix();

		$command = new Command();

		$result = $command
			->params( 'bash', '-c', 'echo ThisIsStderr 1>&2' )
			->includeStderr( true )
			->execute();

		$this->assertEquals( "ThisIsStderr\n", $result->getStdout() );
		$this->assertNull( $result->getStderr() );
	}

	public function testOutput() {
		global $IP;

		$this->requirePosix();
		chdir( $IP );

		$command = new Command();
		$result = $command
			->params( [ 'ls', 'index.php' ] )
			->execute();
		$this->assertRegExp( '/^index.php$/m', $result->getStdout() );
		$this->assertSame( null, $result->getStderr() );

		$command = new Command();
		$result = $command
			->params( [ 'ls', 'index.php', 'no-such-file' ] )
			->includeStderr()
			->execute();
		$this->assertRegExp( '/^index.php$/m', $result->getStdout() );
		$this->assertRegExp( '/^.+no-such-file.*$/m', $result->getStdout() );
		$this->assertSame( null, $result->getStderr() );

		$command = new Command();
		$result = $command
			->params( [ 'ls', 'index.php', 'no-such-file' ] )
			->execute();
		$this->assertRegExp( '/^index.php$/m', $result->getStdout() );
		$this->assertRegExp( '/^.+no-such-file.*$/m', $result->getStderr() );
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
}
