<?php

namespace MediaWiki\Tests\Maintenance;

use InvalidateBotPasswords;
use MediaWiki\User\BotPasswordStore;

/**
 * @covers \InvalidateBotPasswords
 * @group Database
 * @author Dreamy Jazz
 */
class InvalidateBotPasswordsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return InvalidateBotPasswords::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex = null ) {
		$this->expectCallToFatalError();
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		if ( $expectedOutputRegex ) {
			$this->expectOutputRegex( $expectedOutputRegex );
		}
	}

	public static function provideExecuteForFatalError() {
		return [
			'No options provided' => [ [], '/A "user" or "userid" must be set/' ],
			'User ID option for ID which does not exist' => [ [ 'userid' => 0 ] ],
			'User option for user which does not exist' => [ [ 'user' => 'Non-existent-test-user' ] ],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecute( $mockBotPasswordsWereInvalidated, $expectedOutputRegex ) {
		$name = $this->getTestUser()->getUserIdentity()->getName();
		// Mock the BotPasswordStore to expect a call to ::invalidateUserPasswords
		// This is done to avoid indirectly testing the BotPasswordStore as that should
		// be tested separately.
		$this->setService( 'BotPasswordStore', function () use ( $name, $mockBotPasswordsWereInvalidated ) {
			$mockBotPasswordStore = $this->createMock( BotPasswordStore::class );
			$mockBotPasswordStore->expects( $this->once() )
				->method( 'invalidateUserPasswords' )
				->with( $name )
				->willReturn( $mockBotPasswordsWereInvalidated );
			return $mockBotPasswordStore;
		} );
		// Run the maintenance script
		$this->maintenance->setOption( 'user', $name );
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecute() {
		return [
			'No bot passwords were invalidated' => [ false, '/No bot passwords invalidated for/' ],
			'Bot passwords were invalidated' => [ true, '/Bot passwords invalidated/' ],
		];
	}
}
