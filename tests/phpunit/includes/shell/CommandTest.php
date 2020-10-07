<?php

use MediaWiki\Shell\Command;
use MediaWiki\Shell\Shell;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Shell\Command
 * @group Shell
 */
class CommandTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	private function requirePosix() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( $command, $args, $expectedExitCode, $expectedOutput ) {
		$command = $this->getPhpCommand( $command );
		$result = $command
			->params( $args )
			->execute();

		$this->assertSame( $expectedExitCode, $result->getExitCode() );
		$this->assertSame( $expectedOutput, $result->getStdout() );
	}

	public function provideExecute() {
		return [
			'success status' => [ 'success_status.php', [], 0, '' ],
			'failure status' => [ 'failure_status.php', [], 1, '' ],
			'output' => [ 'echo_args.php', [ 'x', '>', 'y' ], 0, 'x > y' ],
		];
	}

	public function testEnvironment() {
		$command = $this->getPhpCommand( 'echo_env.php' );
		$result = $command
			->params( [ 'FOO' ] )
			->environment( [ 'FOO' => 'bar' ] )
			->execute();
		$this->assertSame( "bar", $result->getStdout() );
	}

	public function testStdout() {
		$command = $this->getPhpCommand( 'echo_args.php' );

		$result = $command
			->unsafeParams( 'ThisIsStderr', '1>&2' )
			->execute();

		$this->assertStringNotContainsString( 'ThisIsStderr', $result->getStdout() );
		$this->assertEquals( "ThisIsStderr", $result->getStderr() );
	}

	public function testStdoutRedirection() {
		// The double redirection doesn't work on Windows
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
		$command = $this->getPhpCommand(
			'stdout_stderr.php',
			[ 'correct stdout' ]
		);
		$result = $command->execute();
		$this->assertSame( 'correct stdout', $result->getStdout() );
		$this->assertSame( null, $result->getStderr() );

		$command = $this->getPhpCommand(
			'stdout_stderr.php',
			[ 'correct stdout ', 'correct stderr ' ]
		);
		$result = $command
			->includeStderr()
			->execute();
		$this->assertRegExp( '/correct stdout/m', $result->getStdout() );
		$this->assertRegExp( '/correct stderr/m', $result->getStdout() );
		$this->assertSame( null, $result->getStderr() );

		$command = $this->getPhpCommand(
			'stdout_stderr.php',
			[ 'correct stdout', 'correct stderr' ]
		);
		$result = $command
			->execute();
		$this->assertSame( 'correct stdout', $result->getStdout() );
		$this->assertSame( 'correct stderr', $result->getStderr() );
	}

	/**
	 * Test that null values are skipped by params() and unsafeParams()
	 */
	public function testNullsAreSkipped() {
		$command = TestingAccessWrapper::newFromObject( new Command );
		$command->params( 'echo', 'a', null, 'b' );
		$command->unsafeParams( 'c', null, 'd' );

		if ( wfIsWindows() ) {
			$this->assertEquals( '"echo" "a" "b" c d', $command->command );
		} else {
			$this->assertEquals( "'echo' 'a' 'b' c d", $command->command );
		}
	}

	public function testT69870() {
		// Testing for Bug T69870
		//     wfShellExec() cuts off stdout at multiples of 8192 bytes.

		// hangs on Windows, see Bug T199989, non-blocking pipes
		$this->requirePosix();

		// Test several times because it involves a race condition that may randomly succeed or fail
		for ( $i = 0; $i < 10; $i++ ) {
			$command = $this->getPhpCommand( 'echo_333333_stars.php' );
			$output = $command
				->execute()
				->getStdout();
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}

	public function testLogStderr() {
		$logger = new TestLogger( true, function ( $message, $level, $context ) {
			return $level === Psr\Log\LogLevel::ERROR ? '1' : null;
		}, true );
		$command = $this->getPhpCommand( 'echo_args.php' );
		$command->setLogger( $logger );
		$command->unsafeParams( 'ThisIsStderr', '1>&2' );
		$command->execute();
		$this->assertSame( [], $logger->getBuffer() );

		$command = $this->getPhpCommand( 'echo_args.php' );
		$command->setLogger( $logger );
		$command->logStderr();
		$command->unsafeParams( 'ThisIsStderr', '1>&2' );
		$command->execute();
		$this->assertCount( 1, $logger->getBuffer() );
		$this->assertSame( trim( $logger->getBuffer()[0][2]['error'] ), 'ThisIsStderr' );
	}

	public function testInput() {
		// hangs on Windows, see Bug T199989, non-blocking pipes
		$this->requirePosix();

		$command = $this->getPhpCommand( 'echo_stdin.php' );
		$command->input( 'abc' );
		$result = $command->execute();
		$this->assertSame( 'abc', $result->getStdout() );

		// now try it with something that does not fit into a single block
		$command = $this->getPhpCommand( 'echo_stdin.php' );
		$command->input( str_repeat( '!', 1000000 ) );
		$result = $command->execute();
		$this->assertSame( 1000000, strlen( $result->getStdout() ) );

		// And try it with empty input
		$command = $this->getPhpCommand( 'echo_stdin.php' );
		$command->input( '' );
		$result = $command->execute();
		$this->assertSame( '', $result->getStdout() );
	}

	/**
	 * Ensure that it's possible to disable the default shell restrictions
	 * @see T257278
	 */
	public function testDisablingRestrictions() {
		$command = TestingAccessWrapper::newFromObject( new Command() );
		// As CommandFactory does for the firejail case:
		$command->restrict( Shell::RESTRICT_DEFAULT );
		// Disable restrictions
		$command->restrict( Shell::RESTRICT_NONE );
		$this->assertSame( 0, $command->restrictions );
	}

	/**
	 * Creates a command that will execute one of the PHP test scripts by its
	 * file name, using the current PHP_BIN binary.
	 *
	 * NOTE: the PHP test scripts are located in the sub directory
	 * "bin".
	 *
	 * @param string $fileName  a file name in the "bin" sub-directory
	 * @param array $args       an array of arguments to pass to the PHP script
	 *
	 * @return Command  a command instance pointing to the right script
	 */
	private function getPhpCommand( $fileName, array $args = [] ) {
		$command = new Command;
		$params = [
			PHP_BINARY,
			__DIR__
				 . DIRECTORY_SEPARATOR
				 . 'bin'
				 . DIRECTORY_SEPARATOR
				 . $fileName
		];
		$params = array_merge( $params, $args );

		$command->params( $params );
		$command->limits( [ 'memory' => 0 ] );
		return $command;
	}
}
