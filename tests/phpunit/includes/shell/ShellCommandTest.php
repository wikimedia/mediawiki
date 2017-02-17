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
	public function testExecute( $command, $expectedExitCode, $expectedOutput ) {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}

		$command = new ShellCommand( $command );
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
}
