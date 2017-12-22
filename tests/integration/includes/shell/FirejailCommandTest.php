<?php

use MediaWiki\Shell\FirejailCommand;
use MediaWiki\Shell\Shell;

/**
* Integration tests to ensure that firejail actually prevents execution.
* Meant to run on vagrant, although will probably work on other setups
* as long as firejail and sudo has similar config.
*/
class FirejailCommandIntegrationTest extends PHPUnit_Framework_TestCase {

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
			[ 'sudo ls /', Shell::NO_ROOT ],
			[ 'sudo ls /', Shell::SECCOMP ], // not a great test ut seems to work
			[ 'ls /dev/cpu', Shell::PRIVATE_DEV ],
			[ 'curl -fsSo /dev/null https://wikipedia.org/', Shell::NO_NETWORK ],
			[ 'exec ls /', Shell::NO_EXECVE ],
			[ "cat $IP/LocalSettings.php", Shell::NO_LOCALSETTINGS ],
		];
	}

}
