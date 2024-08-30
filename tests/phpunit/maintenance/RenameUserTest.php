<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use MediaWiki\User\CentralId\CentralIdLookup;
use RenameUser;

/**
 * @covers \RenameUser
 * @group Database
 * @author Dreamy Jazz
 */
class RenameUserTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return RenameUser::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $oldName, $newName, $options, $expectedOutputRegex ) {
		$this->maintenance->setArg( 'old-name', $oldName );
		$this->maintenance->setArg( 'new-name', $newName );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Invalid old name' => [ 'Template:Testing#test', 'abc', [], '/The specified old username is invalid/' ],
			'Non-existing old name' => [ 'Non-existing-test-user123', 'abc', [], '/The user does not exist/' ],
		];
	}

	/** @dataProvider provideExecuteForFatalErrorWithValidOldName */
	public function testExecuteForFatalErrorWithValidOldName( $newName, $options, $expectedOutputRegex ) {
		$oldName = $this->getMutableTestUser()->getUserIdentity()->getName();
		$this->testExecuteForFatalError( $oldName, $newName, $options, $expectedOutputRegex );
		// Check that the old username has not been renamed, as the script should have failed to perform the rename.
		$this->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'actor' )
			->where( [ 'actor_name' => $oldName ] )
			->assertFieldValue( 1 );
	}

	public static function provideExecuteForFatalErrorWithValidOldName() {
		return [
			'Invalid new name' => [ 'Template:Test#testing', [], '/The specified new username is invalid/' ],
			'Performer does not exist' => [
				'Abc', [ 'performer' => 'Non-existing-test-user' ], '/Performer does not exist/',
			],
		];
	}

	public function testExecuteWhenNewNameIsAlreadyTaken() {
		$newName = $this->getMutableTestUser()->getUserIdentity();
		$this->testExecuteForFatalErrorWithValidOldName( $newName, [], '/New username must be free/' );
	}

	public function testExecuteWhenUserIsAttachedToNonLocalProvider() {
		// Set the CentralIdLookup as a mock CentralIdLookup which always returns true from ::isAttached.
		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProviders, [
			'renameUserTestProvider' => [
				'factory' => function () {
					$mockCentralIdLookup = $this->createMock( CentralIdLookup::class );
					$mockCentralIdLookup->method( 'isAttached' )
						->willReturn( true );
					return $mockCentralIdLookup;
				},
			],
		] );
		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProvider, 'renameUserTestProvider' );
		// Run the maintenance script and expect an error.
		$this->testExecuteForFatalErrorWithValidOldName(
			$this->getTestUser()->getUserIdentity()->getName(), [], '/The user is globally attached/'
		);
	}
}
