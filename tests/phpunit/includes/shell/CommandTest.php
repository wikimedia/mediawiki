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
	public function testExecute( $commandString, $expectedExitCode, $expectedOutput ) {
		$command = new Command( $commandString );
		$result = $command->execute();
		$this->assertSame( $expectedExitCode, $result->getStatusCode() );
		$this->assertSame( $expectedOutput, $result->getStdout() );
	}

	public function provideExecute() {
		return [
			'success status' => [ 'true', 0, '' ],
			'failure status' => [ 'false', 1, '' ],
			'output' => [ [ 'echo', '-n', 'x' ], 0, 'x' ],
		];
	}

	public function testEnvironment() {
		$command = new Command( [ 'printenv', 'FOO' ] );
		$result = $command->environment( [ 'FOO' => 'bar' ] )->execute();
		$this->assertSame( "bar\n", $result->getStdout() );
	}

	public function testIncludeStderr() {
		global $IP;

		$command = new Command( [ 'ls', "$IP/index.php" ] );
		$result = $command->execute();
		$this->assertSame( "$IP/index.php", trim( $result->getStdout() ) );

		$command = new Command( [ 'ls', 'index.php', 'no-such-file' ] );
		$result = $command->includeStderr()->execute();
		$this->assertRegExp( '/^.+no-such-file.*$/m', $result->getStdout() );
	}
}
