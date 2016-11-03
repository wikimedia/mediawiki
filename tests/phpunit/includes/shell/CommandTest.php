<?php

use MediaWiki\Shell\Command;

/**
 * @group Shell
 */
class CommandTest extends PHPUnit_Framework_TestCase {
	public function setUp() {
		parent::setUp();

		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testDestruct() {
		if ( defined( 'HHVM_VERSION' ) ) {
			$this->markTestSkipped( 'destructors are unreliable in HHVM' );
		}
		$command = new Command( 'true' );
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( $commandInput, $expectedExitCode, $expectedOutput ) {
		$command = new Command( $commandInput );
		$result = $command->execute();
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
		$command = new Command( [ 'printenv', 'FOO' ] );
		$result = $command->environment( [ 'FOO' => 'bar' ] )->execute();
		$this->assertSame( "bar\n", $result->getStdout() );
	}

	public function test() {
		global $IP;

		$command = new Command( [ 'ls', "$IP/index.php" ] );
		$result = $command->execute();
		$this->assertSame( "$IP/index.php", trim( $result->getStdout() ) );

		$command = new Command( [ 'ls', 'index.php', 'no-such-file' ] );
		$result = $command->includeStderr()->execute();
		$this->assertRegExp( '/^.+no-such-file.*$/m', $result->getStdout() );
	}

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
