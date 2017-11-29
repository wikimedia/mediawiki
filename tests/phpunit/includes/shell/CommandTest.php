<?php

use MediaWiki\Shell\Command;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Shell
 */
class CommandTest extends PHPUnit_Framework_TestCase {
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
