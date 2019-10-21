<?php

use MediaWiki\Shell\Command;
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

	/**
	 * Test that null values are skipped by params() and unsafeParams()
	 */
	public function testNullsAreSkipped() {
		$command = TestingAccessWrapper::newFromObject( new Command );
		$command->params( 'echo', 'a', null, 'b' );
		$command->unsafeParams( 'c', null, 'd' );
		$this->assertEquals( "'echo' 'a' 'b' c d", $command->command );
	}

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
			$command = new Command();
			$output = $command->unsafeParams( 'printf "%-333333s" "*"' )
				->execute()
				->getStdout();
			$this->assertEquals( 333333, strlen( $output ) );
		}
	}

	public function testLogStderr() {
		$this->requirePosix();

		$logger = new TestLogger( true, function ( $message, $level, $context ) {
			return $level === Psr\Log\LogLevel::ERROR ? '1' : null;
		}, true );
		$command = new Command();
		$command->setLogger( $logger );
		$command->params( 'bash', '-c', 'echo ThisIsStderr 1>&2' );
		$command->execute();
		$this->assertEmpty( $logger->getBuffer() );

		$command = new Command();
		$command->setLogger( $logger );
		$command->logStderr();
		$command->params( 'bash', '-c', 'echo ThisIsStderr 1>&2' );
		$command->execute();
		$this->assertSame( 1, count( $logger->getBuffer() ) );
		$this->assertSame( trim( $logger->getBuffer()[0][2]['error'] ), 'ThisIsStderr' );
	}

	public function testInput() {
		$this->requirePosix();

		$command = new Command();
		$command->params( 'cat' );
		$command->input( 'abc' );
		$result = $command->execute();
		$this->assertSame( 'abc', $result->getStdout() );

		// now try it with something that does not fit into a single block
		$command = new Command();
		$command->params( 'cat' );
		$command->input( str_repeat( '!', 1000000 ) );
		$result = $command->execute();
		$this->assertSame( 1000000, strlen( $result->getStdout() ) );

		// And try it with empty input
		$command = new Command();
		$command->params( 'cat' );
		$command->input( '' );
		$result = $command->execute();
		$this->assertSame( '', $result->getStdout() );
	}
}
