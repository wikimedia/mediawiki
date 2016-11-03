<?php

use MediaWiki\ShellCommand;

/**
 * Class ShellCommandTest
 */
class ShellCommandTest extends PHPUnit_Framework_TestCase {
	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testDestruct() {
		$command = new ShellCommand( 'true' );
	}

	/**
	 * @dataProvider provideExecute
	 */
	public function testExecute( $commandString, $expectedExitCode, $expectedOutput ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}

		$command = new ShellCommand( $commandString );
		$command->execute();
		$this->assertSame( $expectedExitCode, $command->getExitCode() );
		$this->assertSame( $expectedOutput, $command->getOutput() );
	}

	public function provideExecute() {
		return [
			'success status' => [ 'true', 0, '' ],
			'failure status' => [ 'false', 1, '' ],
			'output' => [ [ 'echo', '-n', 'x' ], 0, 'x' ],
		];
	}

	public function testEnvironment() {
		$command = new ShellCommand( [ 'printenv', 'FOO' ] );
		$command->environment( [ 'FOO' => 'bar' ] )->execute();
		$this->assertSame( "bar\n", $command->getOutput() );
	}

	public function test() {
		$command = new ShellCommand( [ 'ls', 'index.php', 'no-such-file' ] );
		$command->execute();
		$this->assertSame( "index.php\n", $command->getOutput() );
		$this->assertRegExp( '/^.+no-such-file.*$/', $command->getErrorOutput() );

		$command = new ShellCommand( [ 'ls', 'index.php', 'no-such-file' ] );
		$command->includeStderr()->execute();
		$this->assertRegExp( '/^index.php$/m', $command->getOutput() );
		$this->assertRegExp( '/^.+no-such-file.*$/m', $command->getOutput() );
		$this->assertEquals( '', $command->getErrorOutput() );
	}
}
