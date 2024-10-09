<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use RenameUsersMatchingPattern;
use TestUser;

/**
 * @covers RenameUsersMatchingPattern
 * @group Database
 * @author Dreamy Jazz
 */
class RenameUsersMatchingPatternTest extends MaintenanceBaseTestCase {

	protected function setUp(): void {
		parent::setUp();
		// The tests only work if the local central ID provider is used. As such, force it to be the local provider in
		// case we are running this test while an extension is loaded that provides another provider.
		$this->overrideConfigValue( MainConfigNames::CentralIdLookupProvider, 'local' );
	}

	public function getMaintenanceClass() {
		return RenameUsersMatchingPattern::class;
	}

	public function testExecuteWhenNonLocalCentralIdAvailible() {
		$mockCentralIdLookupFactory = $this->createMock( CentralIdLookupFactory::class );
		$mockCentralIdLookupFactory->method( 'getNonLocalLookup' )
			->willReturn( $this->createMock( CentralIdLookup::class ) );
		$this->setService( 'CentralIdLookupFactory', $mockCentralIdLookupFactory );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/This script cannot be run when CentralAuth is enabled/' );
		$this->maintenance->execute();
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->execute();
	}

	public static function provideExecuteForFatalError() {
		return [
			'Invalid performer username' => [
				[ 'performer' => 'Template:Testing#test', 'from' => '*$1', 'to' => '~$1' ],
				'/Unable to get performer account/',
			]
		];
	}

	public function testExecuteForInvalidNewName() {
		// Get a test user and add it to the database (so that we can query the user table).
		$testUser = $this->getMutableTestUser( [], 'Abc' )->getUser();
		$testUser->addToDatabase();
		// Run the maintenance script to attempt to rename to an invalid username, and expect that this fails.
		$this->maintenance->setOption( 'from', 'Abc$1' );
		$this->maintenance->setOption( 'to', 'Template:Test#testing_$1' );
		$this->expectOutputRegex(
			'/Cannot rename "' . preg_quote( $testUser, '/' ) . '" because.*not a valid title[\s\S]*' .
			"Renamed 0 user\(s\)/"
		);
		$this->maintenance->execute();
	}

	public function testExecuteNewUsernameAlreadyInUse() {
		// Get two users, with a different prefix but the same after the prefix.
		$testUser = new TestUser( "Abc MediaWikiIntegrationTestCase-already-in-use-username-test" );
		$testUser->getUser()->addToDatabase();
		$conflictingUser = new TestUser( "Def MediaWikiIntegrationTestCase-already-in-use-username-test" );
		$conflictingUser->getUser()->addToDatabase();
		// Run the maintenance script to attempt to rename a user to use a name already in use, and expect this fails.
		$this->maintenance->setOption( 'from', 'Abc$1' );
		$this->maintenance->setOption( 'to', 'Def$1' );
		$this->expectOutputRegex(
			'/Cannot rename "' . preg_quote( $testUser->getUser(), '/' ) . '" because "' .
			preg_quote( $conflictingUser->getUser(), '/' ) . '" already exists[\s\S]*' .
			"Renamed 0 user\(s\)/"
		);
		$this->maintenance->execute();
	}

	public function testExecuteWithoutPageMoves() {
		// Get some testing users which have a common prefix
		$usersToBeRenamed = [];
		$userIdsUsedForTest = [];
		for ( $i = 0; $i < 3; $i++ ) {
			$testUser = $this->getMutableTestUser( [], 'Abc' );
			$testUser->getUser()->addToDatabase();
			$usersToBeRenamed[] = $testUser->getUserIdentity();
			$userIdsUsedForTest[] = $testUser->getUserIdentity()->getId();
		}
		// Get a user which does not use the common prefix, and therefore should not be renamed
		$testUserToNotBeRenamed = $this->getMutableTestUser( [], 'Def' )->getUser();
		$testUserToNotBeRenamed->addToDatabase();
		$userIdsUsedForTest[] = $testUserToNotBeRenamed->getId();
		// Run the maintenance script
		$this->maintenance->setOption( 'from', 'Abc$1' );
		$this->maintenance->setOption( 'to', 'Def$1' );
		$this->maintenance->setOption( 'skip-page-moves', true );
		$this->maintenance->execute();
		// Check that the output of the script is as expected, and generate a list of expected new usernames to check
		// against the DB.
		$expectedUsernamesAfterCall = [];
		$expectedOutputRegex = '/';
		foreach ( $usersToBeRenamed as $userIdentity ) {
			$expectedUsernameAfterCall = ucfirst( 'Def' . substr( $userIdentity->getName(), strlen( 'Abc' ) ) );
			$expectedOutputRegex .= preg_quote( $userIdentity->getName(), '/' ) .
				' was successfully renamed to ' . preg_quote( $expectedUsernameAfterCall, '/' ) . '[\s\S]*';
			$expectedUsernamesAfterCall[] = $expectedUsernameAfterCall;
		}
		$expectedOutputRegex .= 'Renamed 3 user\(s\)/';
		$this->expectOutputRegex( $expectedOutputRegex );
		// Check that the renames actually occurred
		$this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_id' => $userIdsUsedForTest ] )
			->assertFieldValues( array_merge( $expectedUsernamesAfterCall, [ $testUserToNotBeRenamed->getName() ] ) );
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
		$this->maintenance->setOption( 'from', 'Abc$1' );
		$this->maintenance->setOption( 'to', 'Xyz$1' );
		$this->maintenance->execute();
		// Check that the output of the script is as expected
		$expectedUsernameAfterCall = ucfirst( 'Xyz' . substr( $testUserIdentity->getName(), strlen( 'Abc' ) ) );
		$this->expectOutputRegex(
			'/' . preg_quote( $testUserIdentity->getName(), '/' ) .
			' was successfully renamed to ' . preg_quote( $expectedUsernameAfterCall, '/' ) . '[\s\S]*'
			. 'Renamed 1 user\(s\)/'
		);
		// Check that the rename actually occurred
		$this->newSelectQueryBuilder()
			->select( 'user_name' )
			->from( 'user' )
			->where( [ 'user_id' => $testUserIdentity->getId() ] )
			->assertFieldValue( $expectedUsernameAfterCall );
		// Check that the user page, user talk page, and user talk subpage were actually moved.
		$expectedPageContent = [
			'user testing1234' => Title::newFromText( $expectedUsernameAfterCall, NS_USER ),
			'usertalk testing1234' => Title::newFromText( $expectedUsernameAfterCall, NS_USER_TALK ),
			'usertalk subpage' => Title::newFromText( "$expectedUsernameAfterCall/test", NS_USER_TALK ),
		];
		foreach ( $expectedPageContent as $expectedContent => $title ) {
			$userPageContent = $this->getServiceContainer()->getRevisionLookup()
				->getRevisionByTitle( $title )
				->getContent( SlotRecord::MAIN )->getWikitextForTransclusion();
			$this->assertSame( $expectedContent, $userPageContent );
		}
	}
}
