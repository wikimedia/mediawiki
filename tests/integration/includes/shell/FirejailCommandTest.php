<?php

use MediaWiki\Shell\FirejailCommand;
use MediaWiki\Shell\Shell;

/**
 * Integration tests to ensure that firejail actually prevents execution.
 * Meant to run on vagrant, although will probably work on other setups
 * as long as firejail and sudo has similar config.
 *
 * @group large
 * @group Shell
 * @covers FirejailCommand
 */
class FirejailCommandIntegrationTest extends PHPUnit\Framework\TestCase {

	public function setUp() {
		parent::setUp();
		if ( Shell::command( 'which', 'firejail' )->execute()->getExitCode() ) {
			$this->markTestSkipped( 'firejail not installed' );
		} elseif ( wfIsWindows() ) {
			$this->markTestSkipped( 'test supports POSIX environments only' );
		}
	}

	public function testSanity() {
		// Make sure that firejail works at all.
		$command = new FirejailCommand( 'firejail' );
		$command
			->unsafeParams( 'ls .' )
			->restrict( Shell::RESTRICT_DEFAULT );
		$result = $command->execute();
		$this->assertSame( 0, $result->getExitCode() );
	}

	/**
	 * @coversNothing
	 * @dataProvider provideExecute
	 */
	public function testExecute( $testCommand, $flag ) {
		if ( preg_match( '/^sudo /', $testCommand ) ) {
			if ( Shell::command( 'sudo', '-n', 'ls', '/' )->execute()->getExitCode() ) {
				$this->markTestSkipped( 'need passwordless sudo' );
			}
		}

		$command = new FirejailCommand( 'firejail' );
		$command
			->unsafeParams( $testCommand )
			// If we don't restrict at all, firejail won't be invoked,
			// so the test will give a false positive if firejail breaks
			// the command for some non-flag-related reason. Instead,
			// set some flag that won't get in the way.
			->restrict( $flag === Shell::NO_NETWORK ? Shell::PRIVATE_DEV : Shell::NO_NETWORK );
		$result = $command->execute();
		$this->assertSame( 0, $result->getExitCode(), 'sanity check' );

		$command = new FirejailCommand( 'firejail' );
		$command
			->unsafeParams( $testCommand )
			->restrict( $flag );
		$result = $command->execute();
		$this->assertNotSame( 0, $result->getExitCode(), 'real check' );
	}

	public function provideExecute() {
		global $IP;
		return [
			[ 'sudo -n ls /', Shell::NO_ROOT ],
			[ 'sudo -n ls /', Shell::SECCOMP ], // not a great test but seems to work
			[ 'ls /dev/cpu', Shell::PRIVATE_DEV ],
			[ 'curl -fsSo /dev/null https://wikipedia.org/', Shell::NO_NETWORK ],
			[ 'exec ls /', Shell::NO_EXECVE ],
			[ "cat $IP/LocalSettings.php", Shell::NO_LOCALSETTINGS ],
		];
	}

}
