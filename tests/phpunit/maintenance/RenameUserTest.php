<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
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

	public function testExecuteWithoutPageMoves() {
		// Get one testing user, and create a user talk and user page for that user
		$testUser = $this->getMutableTestUser( [], 'Abc' );
		$testUser->getUser()->addToDatabase();
		$userPageBeforeRename = $testUser->getUser()->getUserPage();
		$this->editPage( $userPageBeforeRename, 'user testing1234' );
		$userTalkPageBeforeRename = $userPageBeforeRename->getTalkPageIfDefined();
		$this->editPage( $userTalkPageBeforeRename, 'usertalk testing1234' );
		$testUserIdentity = $testUser->getUserIdentity();
		// Run the maintenance script
		$this->maintenance->setArg( 'old-name', $testUserIdentity->getName() );
		$this->maintenance->setArg( 'new-name', 'Abcdef-user' );
		$this->maintenance->setOption( 'skip-page-moves', true );
		$this->maintenance->execute();
		// Check that the output of the script is as expected
		$this->expectOutputRegex(
			'/' . preg_quote( $testUserIdentity->getName(), '/' ) . ' was successfully renamed to Abcdef-user/'
		);
		// Check that the rename actually occurred
		$this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserIdentity->getId() ] )
			->assertFieldValue( 'Abcdef-user' );
		// Check that the user page and user talk page were not moved.
		$userPageContent = $this->getServiceContainer()->getRevisionLookup()
			->getRevisionByTitle( $userPageBeforeRename )
			->getContent( SlotRecord::MAIN )->getWikitextForTransclusion();
		$this->assertSame( 'user testing1234', $userPageContent );
		$userTalkPageContent = $this->getServiceContainer()->getRevisionLookup()
			->getRevisionByTitle( $userTalkPageBeforeRename )
			->getContent( SlotRecord::MAIN )->getWikitextForTransclusion();
		$this->assertSame( 'usertalk testing1234', $userTalkPageContent );
		$this->assertFalse( Title::newFromText( 'abcdef-user', NS_USER )->exists() );
		$this->assertFalse( Title::newFromText( 'abcdef-user', NS_USER_TALK )->exists() );
	}

	public function testExecuteWithPageMoves() {
		// Get one testing user, and create a user talk and user page for that user
		$testUser = $this->getMutableTestUser( [], 'Abc' );
		$testUser->getUser()->addToDatabase();
		$this->editPage( $testUser->getUser()->getUserPage(), 'user testing1234' );
		$userTalkPageBeforeRename = $testUser->getUser()->getUserPage()->getTalkPageIfDefined();
		$this->editPage( $userTalkPageBeforeRename, 'usertalk testing1234' );
		$this->editPage( Title::newFromText( $userTalkPageBeforeRename->getPrefixedText() . '/test' ), 'usertalk subpage' );
		$testUserIdentity = $testUser->getUserIdentity();
		// Run the maintenance script
		$this->maintenance->setArg( 'old-name', $testUserIdentity->getName() );
		$this->maintenance->setArg( 'new-name', 'Abcdef-user' );
		$this->maintenance->execute();
		// Check that the output of the script is as expected
		$this->expectOutputRegex(
			'/' . preg_quote( $testUserIdentity->getName(), '/' ) . ' was successfully renamed to Abcdef-user/'
		);
		// Check that the rename actually occurred
		$this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserIdentity->getId() ] )
			->assertFieldValue( 'Abcdef-user' );
		// Check that the user page, user talk page, and user talk subpage were actually moved.
		$expectedPageContent = [
			'user testing1234' => Title::newFromText( 'abcdef-user', NS_USER ),
			'usertalk testing1234' => Title::newFromText( 'abcdef-user', NS_USER_TALK ),
			'usertalk subpage' => Title::newFromText( 'abcdef-user/test', NS_USER_TALK ),
		];
		foreach ( $expectedPageContent as $expectedContent => $title ) {
			$userPageContent = $this->getServiceContainer()->getRevisionLookup()
				->getRevisionByTitle( $title )
				->getContent( SlotRecord::MAIN )->getWikitextForTransclusion();
			$this->assertSame( $expectedContent, $userPageContent );
		}
	}
}
