<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialUserRights;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialUserRights
 */
class SpecialUserRightsTest extends SpecialPageTestBase {

	use TempUserTestTrait;

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialUserRights(
			$services->getUserGroupManagerFactory(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getUserFactory(),
			$services->getActorStoreFactory(),
			$services->getWatchlistManager(),
			$services->getTempUserConfig()
		);
	}

	/** @dataProvider provideUserCanChangeRights */
	public function testUserCanChangeRights( $targetUser, $checkIfSelf, $expectedReturnValue ) {
		$objectUnderTest = $this->newSpecialPage();
		$this->assertSame( $expectedReturnValue, $objectUnderTest->userCanChangeRights( $targetUser, $checkIfSelf ) );
	}

	public static function provideUserCanChangeRights() {
		return [
			'Target user not registered' => [ UserIdentityValue::newAnonymous( 'Test' ), true, false ],
		];
	}

	public function testUserCanChangeRightsForTemporaryAccount() {
		$temporaryAccount = $this->getServiceContainer()->getTempUserCreator()
			->create( null, new FauxRequest() )->getUser();
		$this->testUserCanChangeRights( $temporaryAccount, false, false );
	}

	private function performBasicFormAssertions( $html, $target ) {
		$targetName = $target->getName();
		$this->assertStringContainsString( "(userrights-editusergroup: $targetName)", $html );
		$this->assertStringContainsString( 'wpGroup-sysop', $html );
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

	private function setUnaddableSysopGroup() {
		$this->setTemporaryHook(
			'SpecialUserRightsChangeableGroups',
			static function ( $authority, $target, $addableGroups, &$unaddableGroups ) {
				$unaddableGroups['sysop'] = 'sysop-unaddable-reason';
			}
		);
	}

	public function testShowFormWithUnaddableGroup() {
		$this->setUnaddableSysopGroup();

		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestSysop()->getUser();

		[ $html ] = $this->executeSpecialPage(
			$target->getName(),
			null,
			'qqx',
			$performer
		);

		$this->performBasicFormAssertions( $html, $target );
		$this->assertStringContainsString( '(sysop-unaddable-reason)', $html );
	}

	public function testSaveUserGroups() {
		$target = $this->getTestUser()->getUser();
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

	public function testSaveUserGroupsWithUnaddableGroup() {
		$this->setUnaddableSysopGroup();

		$target = $this->getTestUser()->getUser();
		$performer = $this->getTestSysop()->getUser();
		$request = new FauxRequest(
			[
				'saveusergroups' => true,
				'conflictcheck-originalgroups' => '',
				'wpGroup-sysop' => true,
				'wpExpiry-sysop' => 'existing',
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
			[],
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
				'wpExpiry-sysop' => 'existing',
				'wpGroup-bot' => true,
				'wpExpiry-bot' => 'existing',
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
		$expiry = wfTimestamp( TS_MW, (int)wfTimestamp( TS_UNIX ) + 100 );
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
}
