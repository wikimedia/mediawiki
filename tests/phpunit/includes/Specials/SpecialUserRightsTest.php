<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialUserRights
 * @covers \MediaWiki\SpecialPage\UserGroupsSpecialPage
 */
class SpecialUserRightsTest extends SpecialPageTestBase {

	use TempUserTestTrait;
	use MockAuthorityTrait;

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return $services->getSpecialPageFactory()->getPage( 'UserRights' );
	}

	private function performBasicFormAssertions( $html, $target ) {
		$targetName = $target->getName();
		$this->assertStringContainsString( "(userrights-editusergroup: $targetName)", $html );
		$this->assertStringContainsString( 'wpGroup-sysop', $html );
		$this->assertStringContainsString( '(rightslog)', $html );
		$this->assertStringContainsString( '(logempty)', $html );
	}

	public function testShowForm() {
		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestSysop()->getUser();

		[ $html ] = $this->executeSpecialPage(
			$target->getName(),
			null,
			'qqx',
			$performer
		);

		$this->performBasicFormAssertions( $html, $target );
	}

	public function testShowFormViewMode() {
		$user = $this->getTestUser()->getUser();

		[ $html ] = $this->executeSpecialPage(
			$user->getName(),
			null,
			'qqx',
			$user
		);

		$this->assertStringContainsString( '(userrights-viewusergroup: ' . $user->getName() . ')', $html );

		// There should be no input for the groups, as we are in view mode
		$input = DOMCompat::querySelector(
			DOMUtils::parseHTML( $html ),
			'#mw-userrights-form2 input'
		);
		$this->assertNull( $input,
			'No input fields should be present in the view mode, apart from the user select form' );
	}

	/** @dataProvider provideShowFormWithRestrictedGroup */
	public function testShowFormWithRestrictedGroup( array $privateConditions, bool $expectPrivateCondsNotice ) {
		$this->overrideConfigValue(
			MainConfigNames::RestrictedGroups,
			[
				'sysop' => [
					'memberConditions' => [
						'&',
						[ APCOND_EDITCOUNT, 300 ],
						[ APCOND_AGE, 3 * 86400 * 30 ],
					],
					'canBeIgnored' => false,
				],
			]
		);
		$this->overrideConfigValue( MainConfigNames::UserRequirementsPrivateConditions, $privateConditions );

		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestSysop()->getUser();

		[ $html ] = $this->executeSpecialPage(
			$target->getName(),
			null,
			'qqx',
			$performer
		);

		$this->performBasicFormAssertions( $html, $target );

		// The custom message exists because we are in qqx
		$noticeMessage = '(userrights-restricted-group-sysop)';
		$noticeMessagePrivateConditions = '(userrights-restricted-group-sysop-private-conditions)';
		if ( $expectPrivateCondsNotice ) {
			$this->assertStringNotContainsString( $noticeMessage, $html );
			$this->assertStringContainsString( $noticeMessagePrivateConditions, $html );
		} else {
			$this->assertStringContainsString( $noticeMessage, $html );
			$this->assertStringNotContainsString( $noticeMessagePrivateConditions, $html );
		}
	}

	public static function provideShowFormWithRestrictedGroup() {
		return [
			'No private conditions' => [
				'privateConditions' => [],
				'expectPrivateCondsNotice' => false,
			],
			'One of two conditions is private; other is false' => [
				'privateConditions' => [ APCOND_AGE ],
				'expectPrivateCondsNotice' => false,
			],
			'All conditions are private' => [
				'privateConditions' => [ APCOND_AGE, APCOND_EDITCOUNT ],
				'expectPrivateCondsNotice' => true,
			]
		];
	}

	public function testShowFormWithRestrictedGroupForSelf() {
		$this->overrideConfigValue(
			MainConfigNames::RestrictedGroups,
			[
				'sysop' => [
					'memberConditions' => [ APCOND_EDITCOUNT, 300 ],
				],
			]
		);
		$this->overrideConfigValue( MainConfigNames::UserRequirementsPrivateConditions, [ APCOND_EDITCOUNT ] );

		$performer = $this->getTestSysop()->getUser();

		[ $html ] = $this->executeSpecialPage(
			$performer->getName(),
			null,
			'qqx',
			$performer
		);

		$this->performBasicFormAssertions( $html, $performer );

		// The custom message exists because we are in qqx
		$this->assertStringContainsString( '(userrights-restricted-group-sysop)', $html );
		$this->assertStringNotContainsString( '(userrights-restricted-group-sysop-private-conditions)', $html );
	}

	public function testSaveUserGroups() {
		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestSysop()->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => '',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => 'infinity',
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		$this->executeSpecialPage(
			$target->getName(),
			$request,
			'qqx',
			$performer
		);

		$this->assertSame( 1, $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$this->assertSame(
			[ 'bot' ],
			$this->getServiceContainer()->getUserGroupManager()->getUserGroups( $target )
		);
	}

	public function testSaveUserGroupsForTemporaryAccount() {
		$target = $this->getServiceContainer()->getTempUserCreator()->create( null, new FauxRequest() )->getUser();
		$performer = $this->getTestSysop()->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => '',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => 'existing',
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		[ $html ] = $this->executeSpecialPage( $target->getName(), $request, 'qqx', $performer );

		$this->assertNull( $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$this->assertCount( 0, $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $target ) );
		$this->assertStringContainsString( 'userrights-no-group', $html );
	}

	public function testSaveUserGroups_change() {
		$target = $this->getTestUser( [ 'sysop' ] )->getUser();
		$performer = $this->getTestSysop()->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => 'sysop',
				'wpGroup-sysop' => true,
				'wpExpiry-sysop' => 'infinity',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => 'infinity',
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		$this->executeSpecialPage(
			$target->getName(),
			$request,
			'qqx',
			$performer
		);

		$this->assertSame( 1, $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$result = $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $target );
		sort( $result );
		$this->assertSame(
			[ 'bot', 'sysop' ],
			$result
		);
	}

	public function testSaveUserGroups_change_expiry() {
		$expiry = wfTimestamp( TS::MW, (int)ConvertibleTimestamp::now( TS::UNIX ) + 100 );
		$target = $this->getTestUser( [ 'bot' ] )->getUser();
		$performer = $this->getTestSysop()->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => 'bot',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => $expiry,
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		$this->executeSpecialPage(
			$target->getName(),
			$request,
			'qqx',
			$performer
		);

		$this->assertSame( 1, $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$userGroups = $this->getServiceContainer()->getUserGroupManager()->getUserGroupMemberships( $target );
		$this->assertCount( 1, $userGroups );
		foreach ( $userGroups as $ugm ) {
			$this->assertSame( 'bot', $ugm->getGroup() );
			$this->assertSame( $expiry, $ugm->getExpiry() );
		}
	}

	public function testSaveUserGroup_keepsDisabled() {
		$this->overrideConfigValue( MainConfigNames::AddGroups, [ 'sysop' => [ 'suppress' ] ] );
		$this->overrideConfigValue( MainConfigNames::RemoveGroups, [ 'sysop' => [] ] );

		$target = $this->getTestUser( [ 'bot' ] )->getUser();
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => 'bot',
				'wpGroup-suppress' => true,
				'wpExpiry-suppress' => 'infinity',
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		$this->executeSpecialPage(
			$target->getName(),
			$request,
			'qqx',
			$performer
		);

		$this->assertSame( 1, $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$result = $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $target );
		sort( $result );
		$this->assertSame(
			[ 'bot', 'suppress' ],
			$result
		);
	}

	public function testSaveUserGroups_invalid() {
		$this->overrideConfigValue( MainConfigNames::AddGroups, [ 'sysop' => [ 'suppress', 'interface-admin' ] ] );

		$this->overrideConfigValue( MainConfigNames::UserRequirementsPrivateConditions, [ APCOND_BLOCKED ] );
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'suppress' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 100 ]
			],
			'interface-admin' => [
				'memberConditions' => APCOND_BLOCKED
			]
		] );

		$hookCalled = false;
		$this->setTemporaryHook(
			'ReadPrivateUserRequirementsCondition',
			function ( $performer, $target, $conditions ) use ( &$hookCalled ) {
				$this->assertSame( [ APCOND_BLOCKED ], $conditions );
				$hookCalled = true;
			}
		);

		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => '',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => 'infinity',
				'wpGroup-suppress' => true,
				'wpExpiry-suppress' => 'infinity',
				'wpGroup-interface-admin' => true,
				'wpExpiry-interface-admin' => 'infinity',
				'wpEditToken' => $performer->getEditToken( $target->getName() ),
			],
			true
		);

		[ $html ] = $this->executeSpecialPage( $target->getName(), $request, 'qqx', $performer );

		$this->assertNull( $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		$this->assertSame( [], $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $target ) );
		$this->assertTrue( $hookCalled );

		$errorBox = DOMCompat::querySelector(
			DOMUtils::parseHTML( $html ),
			'.cdx-message--error'
		);
		$this->assertNotNull( $errorBox );

		$errorHtml = DOMCompat::getInnerHTML( $errorBox );
		$this->assertStringContainsString( 'userrights-unable-to-change', $errorHtml );
		$this->assertStringContainsString( 'userrights-insufficient-rights', $errorHtml );
		$this->assertStringContainsString( 'userrights-restricted-group-suppress', $errorHtml );
		$this->assertStringContainsString( 'userrights-restricted-group-interface-admin', $errorHtml );
	}

	private function getExternalDBname(): ?string {
		$availableDatabases = array_diff(
			$this->getConfVar( MainConfigNames::LocalDatabases ),
			[ WikiMap::getCurrentWikiDbDomain()->getDatabase() ]
		);

		if ( $availableDatabases === [] ) {
			return null;
		}

		// sort to ensure results are deterministic
		sort( $availableDatabases );
		return $availableDatabases[0];
	}

	public function testInterwikiRightsChange() {
		$externalDBname = $this->getExternalDBname();
		if ( $externalDBname === null ) {
			$this->markTestSkipped( 'No external database is available' );
		}

		// FIXME: This should not depend on WikiAdmin user existence
		// NOTE: This is here, as in WMF's CI setup, WikiAdmin is the only user
		// guaranteed to exist on the other wiki.
		$localUser = $this->getServiceContainer()->getUserFactory()->newFromName( 'WikiAdmin' );

		$externalUsername = $localUser->getName() . '@' . $externalDBname;

		// ensure using SpecialUserRights with external usernames doesn't throw (T342747, T342322)
		$performer = $this->getTestUser( [ 'bureaucrat' ] );
		$request = new FauxRequest( [
			'saveusergroups' => true,
			'conflictcheck-originalgroups' => '',
			'wpGroup-sysop' => true,
			'wpExpiry-sysop' => 'existing',
			'wpEditToken' => $performer->getUser()->getEditToken( $externalUsername ),
		], true );
		[ $html, ] = $this->executeSpecialPage(
			$externalUsername,
			$request,
			null,
			$performer->getAuthority()
		);
		$this->assertSame( 1, $request->getSession()->get( 'specialUserrightsSaveSuccess' ) );
		// ensure logging is done with the right username (T344391)
		$this->assertSame(
			1,
			(int)$this->getDb()->newSelectQueryBuilder()
				->select( [ 'cnt' => 'COUNT(*)' ] )
				->from( 'logging' )
				->where( [
					'log_type' => 'rights',
					'log_action' => 'rights',
					'log_namespace' => NS_USER,
					'log_title' => $externalUsername,
				] )
				->caller( __METHOD__ )
				->fetchField()
		);
	}

	public function testDisplayCurrentGroups() {
		$testUser = $this->getTestUser();
		$userId = $testUser->getUserIdentity()->getId();
		$memberships = [
			new UserGroupMembership( $userId, 'sysop' ),
			new UserGroupMembership( $userId, 'bureaucrat' ),
			new UserGroupMembership( $userId, 'bot', '99990101000000' ),
		];

		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getUserGroupMemberships' )
			->willReturn( $memberships );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [ 'add' => [], 'remove' => [], 'add-self' => [], 'remove-self' => [] ] );
		$ugmMock->method( 'getUserAutopromoteGroups' )
			->willReturn( [ 'autoconfirmed' ] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );
		$specialPage = $this->newSpecialPage();
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setLanguage( 'qqx' );
		$specialPage->setContext( $context );

		$wrappedPage = TestingAccessWrapper::newFromObject( $specialPage );
		$wrappedPage->initialize( $testUser->getUser() );

		// This test is deliberately not using executeSpecialPage, as we want to ensure that these group names are
		// present in the correct places. The full output of this special page would contain them in many other places.
		$groupsLists = $wrappedPage->buildFormGroupsLists();

		// The lists are explicit groups and then implicit groups
		$permanentGroups = $groupsLists[0];
		$this->assertStringContainsString( '(userrights-groupsmember: 3,', $permanentGroups );
		$this->assertStringContainsString( '(group-bot)</a>, 00:00, 1 (january) 9999', $permanentGroups );
		$this->assertStringContainsString( '(group-sysop)', $permanentGroups );
		$this->assertStringContainsString( '(group-bureaucrat)', $permanentGroups );

		$implicitGroups = $groupsLists[1];
		$this->assertStringContainsString( '(userrights-groupsmember-auto: 1,', $implicitGroups );
		$this->assertStringContainsString( '(group-autoconfirmed)', $implicitGroups );
	}

	public function testSystemUserNotice() {
		$userName = $this->getTestUser()->getUser()->getName();

		$userMock = $this->createMock( User::class );
		$userMock->method( 'isSystemUser' )
			->willReturn( true );

		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromUserIdentity' )
			->willReturn( $userMock );

		$this->setService( 'UserFactory', $userFactoryMock );

		[ $html, ] = $this->executeSpecialPage( $userName );
		$this->assertStringContainsString( 'userrights-systemuser', $html );
	}

	/** @dataProvider provideSupportsWatchUser */
	public function testSupportsWatchUser( callable $target, bool $expected ) {
		$user = $this->getTestUser()->getUser();
		[ $html ] = $this->executeSpecialPage(
			$target( $user ),
			null,
			'qqx',
			$this->mockAnonUltimateAuthority()
		);

		if ( $expected ) {
			$this->assertStringContainsString( '(userrights-watchuser)', $html );
		} else {
			$this->assertStringNotContainsString( '(userrights-watchuser)', $html );
		}
	}

	public static function provideSupportsWatchUser() {
		return [
			'User on local wiki' => [
				'target' => static fn ( UserIdentity $user ) => $user->getName(),
				'expected' => true,
			],
			'User on remote wiki' => [
				'target' => static fn ( UserIdentity $user ) => $user->getName() . '@otherwiki',
				'expected' => false,
			],
		];
	}

	public function testReadGroupsForm() {
		$request = new FauxRequest( [
			'wpGroup-bot' => true,
			'wpExpiry-bot' => 'infinity',
			'wpGroup-sysop' => true,
			'wpExpiry-sysop' => '99990101000000',
			'wpExpiry-bureaucrat' => 'infinity',
			'wpGroup-interface-admin' => true,
			'wpExpiry-interface-admin' => 'existing',
		], true );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );

		$specialPage = $this->newSpecialPage();
		$specialPage->setContext( $context );
		$wrappedPage = TestingAccessWrapper::newFromObject( $specialPage );
		$wrappedPage->explicitGroups = [ 'bot', 'sysop', 'bureaucrat', 'interface-admin' ];

		$groupsStatus = $wrappedPage->readGroupsForm();
		$this->assertStatusGood( $groupsStatus );
		$groups = $groupsStatus->value;
		$this->assertEquals( [
			'bot' => null,
			'sysop' => '99990101000000',
			'interface-admin' => 'existing',
		], $groups );
	}

	/** @dataProvider provideSplitGroupsIntoAddRemove */
	public function testSplitGroupsIntoAddRemove( array $oldGroups, array $newGroups, array $expected ) {
		$specialPage = $this->newSpecialPage();
		$wrappedPage = TestingAccessWrapper::newFromObject( $specialPage );

		$oldUGMs = [];
		foreach ( $oldGroups as $group => $expiry ) {
			$oldUGMs[$group] = new UserGroupMembership( 1, $group, $expiry );
		}

		$actual = $wrappedPage->splitGroupsIntoAddRemove( $newGroups, $oldUGMs );
		$this->assertEquals( $expected, $actual );
	}

	public static function provideSplitGroupsIntoAddRemove() {
		return [
			'Add a permanent and temporary group' => [
				'oldGroups' => [ 'static' => null ],
				'newGroups' => [ 'static' => null, 'permanent' => null, 'temporary' => '20250101000000' ],
				'expected' => [
					[ 'permanent', 'temporary' ],
					[],
					[ 'permanent' => null, 'temporary' => '20250101000000' ],
				],
			],
			'Remove a group' => [
				'oldGroups' => [ 'static' => null, 'removed1' => null, 'removed2' => '20250101000000' ],
				'newGroups' => [ 'static' => null ],
				'expected' => [
					[],
					[ 'removed1', 'removed2' ],
					[],
				],
			],
			'Nothing changes' => [
				'oldGroups' => [ 'static-perm' => null, 'static-temp' => '20250101000000' ],
				'newGroups' => [ 'static-perm' => null, 'static-temp' => '20250101000000' ],
				'expected' => [
					[],
					[],
					[],
				],
			],
			'Add and remove at once' => [
				'oldGroups' => [ 'old-group' => null ],
				'newGroups' => [ 'new-group' => null ],
				'expected' => [
					[ 'new-group' ],
					[ 'old-group' ],
					[ 'new-group' => null ],
				],
			],
			'Change expiry' => [
				'oldGroups' => [ 'group' => '20250101000000' ],
				'newGroups' => [ 'group' => null ],
				'expected' => [
					[ 'group' ],
					[],
					[ 'group' => null ],
				],
			],
			'Ignore "existing" expiry' => [
				'oldGroups' => [ 'group' => '20250101000000' ],
				'newGroups' => [ 'group' => 'existing', 'new-group' => 'existing' ],
				'expected' => [
					[],
					[],
					[],
				],
			],
		];
	}
}
