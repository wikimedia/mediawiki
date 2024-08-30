<?php

namespace MediaWiki\Tests\Maintenance;

use CreateBotPassword;
use MediaWiki\MainConfigNames;
use MediaWiki\User\BotPassword;

/**
 * @covers \CreateBotPassword
 * @group Database
 * @author Dreamy Jazz
 */
class CreateBotPasswordTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return CreateBotPassword::class;
	}

	public function testExecuteForShowGrants() {
		$this->overrideConfigValue( MainConfigNames::GrantPermissions, [
			'import' => [
				'import' => true,
				'importupload' => true,
			],
		] );
		$this->maintenance->setOption( 'showgrants', 1 );
		$this->maintenance->execute();
		$this->expectOutputString(
			str_pad( 'GRANT', 20 ) . " DESCRIPTION\n" .
			str_pad( 'import', 20 ) . " Import pages from other wikis\n"
		);
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $args, $options, $expectedOutputRegex ) {
		foreach ( $args as $name => $value ) {
			$this->maintenance->setArg( $name, $value );
		}
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Invalid grants provided' => [
				[ 'user' => 'Test' ],
				[ 'grants' => 'invalidgrant1234', 'appid' => 'abcdef' ],
				'/These grants are invalid: invalidgrant1234/',
			],
			'Non-existing user provided' => [
				[ 'user' => 'Test' ],
				[ 'grants' => 'import', 'appid' => 'abcdef' ],
				'/Cannot create bot password for non-existent user/',
			],
			'No arguments or options provided' => [
				[], [], "/Argument <user> required!\nParam appid required!\nParam grants required!/",
			],
		];
	}

	public function testExecuteWhenBotPasswordToShort() {
		$this->testExecuteForFatalError(
			[ 'user' => $this->getTestUser()->getUserIdentity()->getName(), 'password' => 'abc' ],
			[ 'grants' => 'import', 'appid' => 'abc' ],
			'/Bot passwords must have at least ' . BotPassword::PASSWORD_MINLENGTH .
			' characters. Given password is 3 characters/'
		);
	}

	public function testExecuteWhenAppIdTooLong() {
		$this->testExecuteForFatalError(
			[ 'user' => $this->getTestUser()->getUserIdentity()->getName() ],
			[ 'grants' => 'import', 'appid' => str_repeat( 'abc', 100 ) ],
			'/Bot password creation failed/'
		);
	}

	public function testExecuteWhenAppIdAlreadyExists() {
		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProvider, 'local' );
		$username = $this->getMutableTestUser()->getUserIdentity()->getName();
		// Get an existing bot password
		$bp = BotPassword::newUnsaved( [
			'username' => $username,
			'appId' => 'abc',
			'grants' => [ 'import', 'editpage' ]
		] );
		$bp->save( 'insert' );
		// Call the maintenance script with the same appId used to create the bot password above.
		$this->testExecuteForFatalError(
			[ 'user' => $username ],
			[ 'grants' => 'import', 'appid' => 'abc' ],
			'/Bot password creation failed. Does this appid already exist for the user perhaps?/'
		);
	}

	public function testExecuteForSuccess() {
		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProvider, 'local' );
		$username = $this->getMutableTestUser()->getUserIdentity()->getName();
		// Set the valid arguments and options
		$this->maintenance->setArg( 'user', $username );
		$this->maintenance->setOption( 'grants', 'import,editpage' );
		$this->maintenance->setOption( 'appid', 'abcdef' );
		// Run the maintenance script
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Success[\s\S]*Log in using username.*$username@abcdef/" );
	}
}
