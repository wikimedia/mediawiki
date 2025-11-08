<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\User;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserRequirementsConditionChecker;
use MediaWiki\User\UserRequirementsConditionCheckerFactory;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use TestLogger;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\User\UserGroupManager
 * @covers \MediaWiki\User\UserRequirementsConditionChecker
 * @group Database
 */
class UserGroupManagerTest extends MediaWikiIntegrationTestCase {

	private const GROUP = 'user_group_manager_test_group';

	private string $expiryTime;

	private function getManager(
		array $configOverrides = [],
		?UserEditTracker $userEditTrackerOverride = null,
		?callable $callback = null,
		?UserRegistrationLookup $userRegistrationLookupOverride = null,
	): UserGroupManager {
		$services = $this->getServiceContainer();

		$userRequirementsConditionCheckerFactory = $this->createNoOpMock(
			UserRequirementsConditionCheckerFactory::class,
			[ 'getUserRequirementsConditionChecker' ]
		);

		$ugm = new UserGroupManager(
			new ServiceOptions(
				UserGroupManager::CONSTRUCTOR_OPTIONS,
				$configOverrides,
				[
					MainConfigNames::AddGroups => [],
					MainConfigNames::Autopromote => [
						'autoconfirmed' => [ APCOND_EDITCOUNT, 0 ]
					],
					MainConfigNames::AutopromoteOnce => [],
					MainConfigNames::GroupPermissions => [
						self::GROUP => [
							'runtest' => true,
						]
					],
					MainConfigNames::GroupsAddToSelf => [],
					MainConfigNames::GroupsRemoveFromSelf => [],
					MainConfigNames::ImplicitGroups => [ '*', 'user', 'autoconfirmed' ],
					MainConfigNames::RemoveGroups => [],
					MainConfigNames::RevokePermissions => [],
				],
				$services->getMainConfig()
			),
			$services->getReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$services->getJobQueueGroup(),
			new RealTempUserConfig( [
				'enabled' => true,
				'expireAfterDays' => null,
				'actions' => [ 'edit' ],
				'serialProvider' => [ 'type' => 'local' ],
				'serialMapping' => [ 'type' => 'plain-numeric' ],
				'matchPattern' => '*Unregistered $1',
				'genPattern' => '*Unregistered $1'
			] ),
			$userRequirementsConditionCheckerFactory,
			$callback ? [ $callback ] : []
		);

		$userRequirementsConditionCheckerFactory->method( 'getUserRequirementsConditionChecker' )
			->willReturn( new UserRequirementsConditionChecker(
				new ServiceOptions(
					UserRequirementsConditionChecker::CONSTRUCTOR_OPTIONS,
					$configOverrides,
					[
						MainConfigNames::AutoConfirmAge => 0,
						MainConfigNames::AutoConfirmCount => 0,
					],
					$services->getMainConfig()
				),
				$services->getGroupPermissionsLookup(),
				$services->getHookContainer(),
				new TestLogger(),
				$userEditTrackerOverride ?? $services->getUserEditTracker(),
				$userRegistrationLookupOverride ?? $services->getUserRegistrationLookup(),
				$services->getUserFactory(),
				RequestContext::getMain(),
				$ugm,
			) );

		return $ugm;
	}

	protected function setUp(): void {
		parent::setUp();

		$this->expiryTime = wfTimestamp( TS_MW, time() + 100500 );
		$this->clearHooks();
	}

	/**
	 * Returns a callable that must be called exactly $invokedCount times.
	 * @return callable|MockObject
	 */
	private function countPromise( InvokedCount $invokedCount ) {
		$mockHandler = $this->getMockBuilder( \stdClass::class )
			->addMethods( [ '__invoke' ] )
			->getMock();
		$mockHandler->expects( $invokedCount )
			->method( '__invoke' );
		return $mockHandler;
	}

	private function assertMembership(
		UserGroupManager $manager,
		UserIdentity $user,
		string $group,
		?string $expiry = null
	) {
		$this->assertContains( $group, $manager->getUserGroups( $user ) );
		$memberships = $manager->getUserGroupMemberships( $user );
		$this->assertArrayHasKey( $group, $memberships );
		$membership = $memberships[$group];
		$this->assertSame( $group, $membership->getGroup() );
		$this->assertSame( $user->getId(), $membership->getUserId() );
		$this->assertSame( $expiry, $membership->getExpiry() );
	}

	public function testNewGroupMembershipFromRow() {
		$row = new \stdClass();
		$row->ug_user = '1';
		$row->ug_group = __METHOD__;
		$row->ug_expiry = null;
		$membership = $this->getManager()->newGroupMembershipFromRow( $row );
		$this->assertSame( 1, $membership->getUserId() );
		$this->assertSame( __METHOD__, $membership->getGroup() );
		$this->assertNull( $membership->getExpiry() );
	}

	public function testNewGroupMembershipFromRowExpiring() {
		$row = new \stdClass();
		$row->ug_user = '1';
		$row->ug_group = __METHOD__;
		$row->ug_expiry = $this->expiryTime;
		$membership = $this->getManager()->newGroupMembershipFromRow( $row );
		$this->assertSame( 1, $membership->getUserId() );
		$this->assertSame( __METHOD__, $membership->getGroup() );
		$this->assertSame( $this->expiryTime, $membership->getExpiry() );
	}

	public function testGetImplicitGroups() {
		$manager = $this->getManager();
		$user = $this->getTestUser( 'unittesters' )->getUser();
		$this->assertArrayEquals(
			[ '*', 'user', 'autoconfirmed' ],
			$manager->getUserImplicitGroups( $user )
		);

		$user = $this->getTestUser( [ 'bureaucrat', 'test' ] )->getUser();
		$this->assertArrayEquals(
			[ '*', 'user', 'autoconfirmed' ],
			$manager->getUserImplicitGroups( $user )
		);

		$this->assertTrue(
			$manager->addUserToGroup( $user, self::GROUP ),
			'added user to group'
		);
		$this->assertArrayEquals(
			[ '*', 'user', 'autoconfirmed' ],
			$manager->getUserImplicitGroups( $user )
		);

		$user = User::newFromName( 'UTUser1' );
		$this->assertSame( [ '*' ], $manager->getUserImplicitGroups( $user ) );

		$manager = $this->getManager( [ MainConfigNames::Autopromote => [
			'dummy' => APCOND_EMAILCONFIRMED
		] ] );
		$user = $this->getTestUser()->getUser();
		$this->assertArrayEquals(
			[ '*', 'user' ],
			$manager->getUserImplicitGroups( $user )
		);
		$this->assertArrayEquals(
			[ '*', 'user' ],
			$manager->getUserEffectiveGroups( $user )
		);
		$user->confirmEmail();
		$this->assertArrayEquals(
			[ '*', 'user', 'dummy' ],
			$manager->getUserImplicitGroups( $user, IDBAccessObject::READ_NORMAL, true )
		);
		$this->assertArrayEquals(
			[ '*', 'user', 'dummy' ],
			$manager->getUserEffectiveGroups( $user )
		);

		$user = $this->getTestUser( [ 'dummy' ] )->getUser();
		$user->confirmEmail();
		$this->assertArrayEquals(
			[ '*', 'user', 'dummy' ],
			$manager->getUserImplicitGroups( $user )
		);

		$user = new User;
		$user->setName( '*Unregistered 1234' );
		$this->assertArrayEquals(
			[ '*', 'temp' ],
			$manager->getUserImplicitGroups( $user )
		);
	}

	public static function provideGetEffectiveGroups() {
		yield [ [], [ '*', 'user', 'autoconfirmed' ] ];
		yield [ [ 'bureaucrat', 'test' ], [ '*', 'user', 'autoconfirmed', 'bureaucrat', 'test' ] ];
		yield [ [ 'autoconfirmed', 'test' ], [ '*', 'user', 'autoconfirmed', 'test' ] ];
	}

	/**
	 * @dataProvider provideGetEffectiveGroups
	 */
	public function testGetEffectiveGroups( $userGroups, $effectiveGroups ) {
		$manager = $this->getManager();
		$user = $this->getTestUser( $userGroups )->getUser();
		$this->assertArrayEquals( $effectiveGroups, $manager->getUserEffectiveGroups( $user ) );
	}

	public function testGetEffectiveGroupsHook() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserEffectiveGroups',
			function ( UserIdentity $hookUser, array &$groups ) use ( $user ) {
				$this->assertTrue( $hookUser->equals( $user ) );
				$groups[] = 'from_hook';
			}
		);
		$this->assertContains( 'from_hook', $manager->getUserEffectiveGroups( $user ) );
	}

	public function testAddUserToGroup() {
		$manager = $this->getManager();
		$user = $this->getMutableTestUser()->getUser();

		$result = $manager->addUserToGroup( $user, self::GROUP );
		$this->assertTrue( $result );
		$this->assertMembership( $manager, $user, self::GROUP );
		$manager->clearCache( $user );
		$this->assertMembership( $manager, $user, self::GROUP );

		// try updating without allowUpdate. Should fail
		$result = $manager->addUserToGroup( $user, self::GROUP, $this->expiryTime );
		$this->assertFalse( $result );

		// now try updating with allowUpdate
		$result = $manager->addUserToGroup( $user, self::GROUP, $this->expiryTime, true );
		$this->assertTrue( $result );
		$this->assertMembership( $manager, $user, self::GROUP, $this->expiryTime );
		$manager->clearCache( $user );
		$this->assertMembership( $manager, $user, self::GROUP, $this->expiryTime );
	}

	public function testAddUserToGroupReadonly() {
		$user = $this->getTestUser()->getUser();
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->addUserToGroup( $user, 'test' ) );
		$this->assertNotContains( 'test', $manager->getUserGroups( $user ) );
	}

	public function testAddUserToGroupAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon' );
		$this->expectException( InvalidArgumentException::class );
		$manager->addUserToGroup( $anon, 'test' );
	}

	public function testAddUserToGroupHookAbort() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$originalGroups = $manager->getUserGroups( $user );
		$this->setTemporaryHook(
			'UserAddGroup',
			function ( UserIdentity $hookUser ) use ( $user ) {
				$this->assertTrue( $hookUser->equals( $user ) );
				return false;
			}
		);
		$this->assertFalse( $manager->addUserToGroup( $user, 'test_group' ) );
		$this->assertArrayEquals( $originalGroups, $manager->getUserGroups( $user ) );
	}

	public function testAddUserToGroupHookModify() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'UserAddGroup',
			function ( UserIdentity $hookUser, &$group, &$hookExp ) use ( $user ) {
				$this->assertTrue( $hookUser->equals( $user ) );
				$this->assertSame( self::GROUP, $group );
				$this->assertSame( $this->expiryTime, $hookExp );
				$group = 'from_hook';
				$hookExp = null;
				return true;
			}
		);
		$this->assertTrue( $manager->addUserToGroup( $user, self::GROUP, $this->expiryTime ) );
		$this->assertContains( 'from_hook', $manager->getUserGroups( $user ) );
		$this->assertNotContains( self::GROUP, $manager->getUserGroups( $user ) );
		$this->assertNull( $manager->getUserGroupMemberships( $user )['from_hook']->getExpiry() );
	}

	public function testAddUserToMultipleGroups() {
		$manager = $this->getManager();
		$user = $this->getMutableTestUser()->getUser();

		$manager->addUserToMultipleGroups( $user, [ self::GROUP, self::GROUP . '1' ] );
		$this->assertMembership( $manager, $user, self::GROUP );
		$this->assertMembership( $manager, $user, self::GROUP . '1' );

		$anon = new UserIdentityValue( 0, 'Anon' );
		$this->expectException( InvalidArgumentException::class );
		$manager->addUserToMultipleGroups( $anon, [ self::GROUP, self::GROUP . '1' ] );
	}

	public function testGetUserGroupMembershipsForAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon' );

		$this->assertSame( [], $manager->getUserGroupMemberships( $anon ) );
	}

	public function testGetUserFormerGroupsForAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon' );

		$this->assertSame( [], $manager->getUserFormerGroups( $anon ) );
	}

	public function testRemoveUserFromGroup() {
		$manager = $this->getManager();
		$user = $this->getMutableTestUser( [ self::GROUP ] )->getUser();
		$this->assertMembership( $manager, $user, self::GROUP );

		$result = $manager->removeUserFromGroup( $user, self::GROUP );
		$this->assertTrue( $result );
		$this->assertNotContains( self::GROUP,
			$manager->getUserGroups( $user ) );
		$this->assertArrayNotHasKey( self::GROUP,
			$manager->getUserGroupMemberships( $user ) );
		$this->assertContains( self::GROUP,
			$manager->getUserFormerGroups( $user ) );
		$manager->clearCache( $user );
		$this->assertNotContains( self::GROUP,
			$manager->getUserGroups( $user ) );
		$this->assertArrayNotHasKey( self::GROUP,
			$manager->getUserGroupMemberships( $user ) );
		$this->assertContains( self::GROUP,
			$manager->getUserFormerGroups( $user ) );
		// From cache
		$this->assertContains( self::GROUP,
			$manager->getUserFormerGroups( $user ) );
	}

	public function testRemoveUserToGroupHookAbort() {
		$manager = $this->getManager();
		$user = $this->getTestUser( [ self::GROUP ] )->getUser();
		$originalGroups = $manager->getUserGroups( $user );
		$this->setTemporaryHook(
			'UserRemoveGroup',
			function ( UserIdentity $hookUser ) use ( $user ) {
				$this->assertTrue( $hookUser->equals( $user ) );
				return false;
			}
		);
		$this->assertFalse( $manager->removeUserFromGroup( $user, self::GROUP ) );
		$this->assertArrayEquals( $originalGroups, $manager->getUserGroups( $user ) );
	}

	public function testRemoveUserFromGroupHookModify() {
		$manager = $this->getManager();
		$user = $this->getTestUser( [ self::GROUP, 'from_hook' ] )->getUser();
		$this->setTemporaryHook(
			'UserRemoveGroup',
			function ( UserIdentity $hookUser, &$group ) use ( $user ) {
				$this->assertTrue( $hookUser->equals( $user ) );
				$this->assertSame( self::GROUP, $group );
				$group = 'from_hook';
				return true;
			}
		);
		$this->assertTrue( $manager->removeUserFromGroup( $user, self::GROUP ) );
		$this->assertNotContains( 'from_hook', $manager->getUserGroups( $user ) );
		$this->assertContains( self::GROUP, $manager->getUserGroups( $user ) );
	}

	public function testRemoveUserFromGroupReadOnly() {
		$user = $this->getTestUser( [ 'test' ] )->getUser();
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertContains( 'test', $manager->getUserGroups( $user ) );
	}

	public function testRemoveUserFromGroupAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon' );
		$this->expectException( InvalidArgumentException::class );
		$manager->removeUserFromGroup( $anon, 'test' );
	}

	public function testRemoveUserFromGroupCallback() {
		$user = $this->getTestUser( [ 'test' ] )->getUser();
		$calledCount = 0;
		$callback = function ( UserIdentity $callbackUser ) use ( $user, &$calledCount ) {
			$this->assertTrue( $callbackUser->equals( $user ) );
			$calledCount++;
		};
		$manager = $this->getManager( [], null, $callback );
		$this->assertTrue( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertNotContains( 'test', $manager->getUserGroups( $user ) );
		$this->assertSame( 1, $calledCount );
		$this->assertFalse( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertSame( 1, $calledCount );
	}

	public function testPurgeExpired() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$expiryInPast = wfTimestamp( TS_MW, time() - 100500 );
		$this->assertTrue(
			$manager->addUserToGroup( $user, 'expired', $expiryInPast ),
			'can add expired group'
		);
		$manager->purgeExpired();
		$this->assertNotContains( 'expired', $manager->getUserGroups( $user ) );
		$this->assertArrayNotHasKey( 'expired', $manager->getUserGroupMemberships( $user ) );
		$this->assertContains( 'expired', $manager->getUserFormerGroups( $user ) );
	}

	public function testPurgeExpiredReadOnly() {
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->purgeExpired() );
	}

	public function testGetAllGroups() {
		$manager = $this->getManager( [
			MainConfigNames::GroupPermissions => [
				__METHOD__ => [ 'test' => true ],
				'implicit' => [ 'test' => true ]
			],
			MainConfigNames::RevokePermissions => [
				'revoked' => [ 'test' => true ]
			],
			MainConfigNames::ImplicitGroups => [ 'implicit' ]
		] );
		$this->assertArrayEquals( [ __METHOD__, 'revoked' ], $manager->listAllGroups() );
	}

	public function testGetAllImplicitGroups() {
		$manager = $this->getManager( [ MainConfigNames::ImplicitGroups => [ __METHOD__ ] ] );
		$this->assertArrayEquals( [ __METHOD__ ], $manager->listAllImplicitGroups() );
	}

	public function testLoadGroupMembershipsFromArray() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$row = new \stdClass();
		$row->ug_user = $user->getId();
		$row->ug_group = 'test';
		$row->ug_expiry = null;
		$manager->loadGroupMembershipsFromArray( $user, [ $row ], IDBAccessObject::READ_NORMAL );
		$memberships = $manager->getUserGroupMemberships( $user );
		$this->assertCount( 1, $memberships );
		$this->assertArrayHasKey( 'test', $memberships );
		$this->assertSame( $user->getId(), $memberships['test']->getUserId() );
		$this->assertSame( 'test', $memberships['test']->getGroup() );
	}

	public static function provideGetUserAutopromoteEmailConfirmed() {
		yield 'Successful autopromote' => [
			true, [ 'email' => 'test@test.com', 'timestamp' => wfTimestampNow() ], [ 'test_autoconfirmed' ]
		];
		yield 'wgEmailAuthentication is false' => [
			false, [ 'email' => 'test@test.com' ], [ 'test_autoconfirmed' ]
		];
		yield 'Invalid email' => [
			true, [ 'email' => 'INVALID!' ], []
		];
		yield 'Invalid email auth timestamp' => [
			true, [ 'email' => 'test@test.com', 'timestamp' => null ], []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteEmailConfirmed
	 */
	public function testGetUserAutopromoteEmailConfirmed(
		bool $emailAuthentication,
		array $userSpec,
		array $expected
	) {
		$user = $this->createNoOpMock(
			User::class, array_merge( [ 'getEmail', 'isTemp', 'assertWiki', 'getWikiId' ],
				( array_key_exists( 'timestamp', $userSpec ) ? [ 'getEmailAuthenticationTimestamp' ] : [] ) )
		);
		$user->method( 'getWikiId' )->willReturn( UserIdentity::LOCAL );
		$user->method( 'assertWiki' )->willReturn( true );
		$user->expects( $this->once() )
			->method( 'getEmail' )
			->willReturn( $userSpec['email'] );
		if ( array_key_exists( 'timestamp', $userSpec ) ) {
			$user->expects( $this->once() )
				->method( 'getEmailAuthenticationTimestamp' )
				->willReturn( $userSpec['timestamp'] );
		}
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ APCOND_EMAILCONFIRMED ] ],
			MainConfigNames::EmailAuthentication => $emailAuthentication
		] );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteEditCount() {
		yield 'Successful promote' => [
			[ APCOND_EDITCOUNT, 5 ], true, 10, [ 'test_autoconfirmed' ]
		];
		yield 'Required edit count negative' => [
			[ APCOND_EDITCOUNT, -1 ], true, 10, [ 'test_autoconfirmed' ]
		];
		yield 'No edit count, use AutoConfirmCount = 11' => [
			[ APCOND_EDITCOUNT ], true, 10, []
		];
		yield 'Null edit count, use AutoConfirmCount = 11' => [
			[ APCOND_EDITCOUNT, null ], true, 13, [ 'test_autoconfirmed' ]
		];
		yield 'Anon' => [
			[ APCOND_EDITCOUNT, 5 ], false, 100, []
		];
		yield 'Not enough edits' => [
			[ APCOND_EDITCOUNT, 100 ], true, 10, []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteEditCount
	 */
	public function testGetUserAutopromoteEditCount(
		array $requiredCond,
		bool $userRegistered,
		int $userEditCount,
		array $expected
	) {
		$userEditTrackerMock = $this->createNoOpMock(
			UserEditTracker::class,
			[ 'getUserEditCount' ]
		);
		if ( $userRegistered ) {
			$user = $this->getTestUser()->getUser();
			$userEditTrackerMock->method( 'getUserEditCount' )
				->with( $user )
				->willReturn( $userEditCount );
		} else {
			$user = User::newFromName( 'UTUser1' );
		}
		$manager = $this->getManager(
			[
				MainConfigNames::AutoConfirmCount => 11,
				MainConfigNames::Autopromote => [ 'test_autoconfirmed' => $requiredCond ]
			],
			$userEditTrackerMock
		);
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteAge() {
		yield 'Successful promote' => [
			[ APCOND_AGE, 1000 ],
			MWTimestamp::convert( TS_MW, time() - 1000000 ),
			[ 'test_autoconfirmed' ]
		];
		yield 'Not old enough' => [
			[ APCOND_AGE, 10000000 ], MWTimestamp::now(), []
		];
		yield 'Not old enough, using AutoConfirmAge via unset' => [
			[ APCOND_AGE ], MWTimestamp::now(), []
		];
		yield 'Not old enough, using AutoConfirmAge via null' => [
			[ APCOND_AGE, null ], MWTimestamp::now(), []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteAge
	 */
	public function testGetUserAutopromoteAge(
		array $requiredCondition,
		string $registrationTs,
		array $expected
	) {
		$registrationLookupMock = $this->createNoOpMock( UserRegistrationLookup::class, [ 'getRegistration' ] );
		$registrationLookupMock->method( 'getRegistration' )
			->willReturn( $registrationTs );

		$manager = $this->getManager( [
			MainConfigNames::AutoConfirmAge => 10000000,
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => $requiredCondition ]
		], null, null, $registrationLookupMock );
		$user = $this->createNoOpMock( User::class, [ 'isTemp', 'assertWiki', 'getWikiId' ] );
		$user->method( 'getWikiId' )->willReturn( UserIdentity::LOCAL );
		$user->method( 'assertWiki' )->willReturn( true );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteEditAge() {
		yield 'Successful promote' => [
			[ APCOND_AGE_FROM_EDIT, 1000 ],
			MWTimestamp::convert( TS_MW, time() - 1000000 ),
			[ 'test_autoconfirmed' ]
		];
		yield 'Not old enough' => [
			[ APCOND_AGE_FROM_EDIT, 10000000 ], MWTimestamp::now(), []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteEditAge
	 */
	public function testGetUserAutopromoteEditAge(
		array $requiredCondition,
		string $firstEditTs,
		array $expected
	) {
		$user = $this->getTestUser()->getUser();
		$mockUserEditTracker = $this->createNoOpMock( UserEditTracker::class, [ 'getFirstEditTimestamp' ] );
		$mockUserEditTracker->expects( $this->once() )
			->method( 'getFirstEditTimestamp' )
			->with( $user )
			->willReturn( $firstEditTs );
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => $requiredCondition ]
		], $mockUserEditTracker );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteGroups() {
		yield 'Successful promote' => [
			[ 'group1', 'group2' ], [ 'group1', 'group2' ], [ 'test_autoconfirmed' ]
		];
		yield 'Not enough groups to promote' => [
			[ 'group1', 'group2' ], [ 'group1' ], []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteGroups
	 */
	public function testGetUserAutopromoteGroups(
		array $requiredGroups,
		array $userGroups,
		array $expected
	) {
		$user = $this->getTestUser( $userGroups )->getUser();
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [
				'test_autoconfirmed' => array_merge( [ APCOND_INGROUPS ], $requiredGroups )
			]
		] );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteIP() {
		yield 'Individual ip, success' => [
			[ APCOND_ISIP, '123.123.123.123' ], '123.123.123.123', [ 'test_autoconfirmed' ]
		];
		yield 'Individual ip, failed' => [
			[ APCOND_ISIP, '123.123.123.123' ], '124.124.124.124', []
		];
		yield 'Range ip, success' => [
			[ APCOND_IPINRANGE, '123.123.123.1/24' ], '123.123.123.123', [ 'test_autoconfirmed' ]
		];
		yield 'Range ip, failed' => [
			[ APCOND_IPINRANGE, '123.123.123.1/24' ], '124.124.124.124', []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteIP
	 */
	public function testGetUserAutopromoteIP(
		array $condition,
		string $userIp,
		array $expected
	) {
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => $condition ]
		] );
		$request = new FauxRequest();
		$request->setIP( $userIp );
		$this->setRequest( $request );
		$user = $this->createNoOpMock( User::class, [ 'getRequest', 'isTemp', 'assertWiki', 'getWikiId' ] );
		$user->method( 'getWikiId' )->willReturn( UserIdentity::LOCAL );
		$user->method( 'assertWiki' )->willReturn( true );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public function testGetUserAutopromoteGroupsHook() {
		$manager = $this->getManager( [ MainConfigNames::Autopromote => [] ] );
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'GetAutoPromoteGroups',
			function ( User $hookUser, array &$promote ) use ( $user ){
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertSame( [], $promote );
				$promote[] = 'from_hook';
			}
		);
		$this->assertArrayEquals( [ 'from_hook' ], $manager->getUserAutopromoteGroups( $user ) );
	}

	public function testGetUserAutopromoteComplexCondition() {
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [
				'test_autoconfirmed' => [ '&',
					[ APCOND_INGROUPS, 'group1' ],
					[ '!', [ APCOND_INGROUPS, 'group2' ] ],
					[ '^', [ APCOND_INGROUPS, 'group3' ], [ APCOND_INGROUPS, 'group4' ] ],
					[ '|', [ APCOND_INGROUPS, 'group5' ], [ APCOND_INGROUPS, 'group6' ] ]
				]
			]
		] );
		$this->assertSame( [], $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1' ] )->getUser() )
		);
		$this->assertSame( [], $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group2' ] )->getUser() )
		);
		$this->assertSame( [], $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group3', 'group4' ] )->getUser() )
		);
		$this->assertSame( [], $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group3' ] )->getUser() )
		);
		$this->assertArrayEquals(
			[ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $this->getTestUser( [ 'group1', 'group3', 'group5' ] )->getUser() )
		);
	}

	public function testGetUserAutopromoteBot() {
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ APCOND_ISBOT ] ]
		] );
		$notBot = $this->getTestUser()->getUser();
		$this->assertSame( [], $manager->getUserAutopromoteGroups( $notBot ) );
		$bot = $this->getTestUser( [ 'bot' ] )->getUser();
		$this->assertArrayEquals( [ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $bot ) );
	}

	public function testGetUserAutopromoteBlocked() {
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ APCOND_BLOCKED ] ]
		] );
		$nonBlockedUser = $this->getTestUser()->getUser();
		$this->assertSame( [], $manager->getUserAutopromoteGroups( $nonBlockedUser ) );
		$blockedUser = $this->getTestUser( [ 'blocked' ] )->getUser();
		$block = new DatabaseBlock();
		$block->setTarget( $this->getServiceContainer()->getBlockTargetFactory()
			->newUserBlockTarget( $blockedUser ) );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->isSitewide( true );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );
		$this->assertArrayEquals( [ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $blockedUser ) );
	}

	public function testGetUserAutopromoteBlockedDoesNotRecurse() {
		// Make sure session handling is started
		if ( !PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install(
				SessionManager::singleton()
			);
		}
		$oldSessionId = session_id();

		$context = RequestContext::getMain();
		// Variables are unused but needed to reproduce the failure
		$oInfo = $context->exportSession();

		$user = User::newFromName( 'UnitTestContextUser' );
		$user->addToDatabase();

		$sessionInfo = [
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '192.0.2.0',
			'headers' => [
				'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0'
			]
		];
		$this->overrideConfigValue(
			MainConfigNames::Autopromote,
			[ 'test_autoconfirmed' => [ '&', APCOND_BLOCKED ] ]
		);
		// Variables are unused but needed to reproduce the failure
		// load new context
		$sc = RequestContext::importScopedSession( $sessionInfo );
		$info = $context->exportSession();

		$this->assertNull( $user->getBlock() );
	}

	public function testGetUserAutopromoteBlockedDoesNotRecurseWithHook() {
		$this->overrideConfigValue(
			MainConfigNames::Autopromote,
			[ 'test_autoconfirmed' => [ '&', APCOND_BLOCKED ] ]
		);

		// Make sure session handling is started
		if ( !PHPSessionHandler::isInstalled() ) {
			PHPSessionHandler::install(
				SessionManager::singleton()
			);
		}
		$permissionManager = $this->getServiceContainer()->getPermissionManager();
		$permissionManager->invalidateUsersRightsCache();

		$oldSessionId = session_id();

		$context = RequestContext::getMain();
		// Variables are unused but needed to reproduce the failure
		$oInfo = $context->exportSession();

		$user = User::newFromName( 'UnitTestContextUser' );
		$user->addToDatabase();

		$sinfo = [
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '192.0.2.0',
			'headers' => [
				'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0'
			]
		];

		$onGetUserBlockCalled = false;
		$this->setTemporaryHook(
			'GetUserBlock',
			static function ( $user, $ip, &$block ) use ( $permissionManager, &$onGetUserBlockCalled ) {
				$onGetUserBlockCalled = true;

				try {
					if ( $permissionManager->userHasAnyRight( $user, 'ipblock-exempt', 'globalblock-exempt' ) ) {
						return true;
					}
				} catch ( LogicException $e ) {
					// We expect an uncaught LogicException from UserGroupManager::checkCondition here
					// otherwise there's something else wrong!
					if ( !str_starts_with( $e->getMessage(), "Unexpected recursion!" ) ) {
						throw $e;
					}
				}

				return true;
			}
		);

		// Variables are unused but needed to reproduce the failure
		// load new context
		$sc = RequestContext::importScopedSession( $sinfo );
		$info = $context->exportSession();

		$this->assertNull( $user->getBlock() );

		$this->assertTrue(
			$onGetUserBlockCalled,
			'Check that HookRunner::onGetUserBlock was called'
		);
	}

	public function testGetUserAutopromoteInvalid() {
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ 999 ] ]
		] );
		$user = $this->getTestUser()->getUser();
		$this->expectException( InvalidArgumentException::class );
		$manager->getUserAutopromoteGroups( $user );
	}

	public function testGetUserAutopromoteConditionHook() {
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'AutopromoteCondition',
			function ( $type, array $arg, User $hookUser, &$result ) use ( $user ){
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertSame( 999, $type );
				$this->assertSame( 'ARGUMENT', $arg[0] );
				$result = true;
			}
		);
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ 999, 'ARGUMENT' ] ]
		] );
		$this->assertArrayEquals( [ 'test_autoconfirmed' ], $manager->getUserAutopromoteGroups( $user ) );
	}

	public function testGetUserAutopromoteUserRequirementsConditionHook() {
		$user = new UserIdentityValue( 1, 'TestUser' );
		$this->setTemporaryHook(
			'UserRequirementsCondition',
			function ( $type, array $arg, UserIdentity $hookUser, $isPerformer, &$result ) use ( $user ){
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertSame( 999, $type );
				$this->assertSame( 'ARGUMENT', $arg[0] );
				$result = true;
			}
		);
		$manager = $this->getManager( [
			MainConfigNames::Autopromote => [ 'test_autoconfirmed' => [ 999, 'ARGUMENT' ] ]
		] );
		$this->assertArrayEquals( [ 'test_autoconfirmed' ], $manager->getUserAutopromoteGroups( $user ) );
	}

	public static function provideGetUserAutopromoteOnce() {
		yield 'Events are not matching' => [
			[ 'NOT_EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ], [], [], []
		];
		yield 'Empty config' => [
			[ 'EVENT' => [] ], [], [], []
		];
		yield 'Simple case, not user groups, not former groups' => [
			[ 'EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ], [], [], [ 'autopromoteonce' ]
		];
		yield 'User already in the group' => [
			[ 'EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ], [], [ 'autopromoteonce' ], []
		];
		yield 'User used to be in the group' => [
			[ 'EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ], [ 'autopromoteonce' ], [], []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteOnce
	 */
	public function testGetUserAutopromoteOnce(
		array $config,
		array $formerGroups,
		array $userGroups,
		array $expected
	) {
		$manager = $this->getManager( [ MainConfigNames::AutopromoteOnce => $config ] );
		$user = $this->getTestUser()->getUser();
		$manager->addUserToMultipleGroups( $user, $userGroups );
		foreach ( $formerGroups as $formerGroup ) {
			$manager->addUserToGroup( $user, $formerGroup );
			$manager->removeUserFromGroup( $user, $formerGroup );
		}
		$this->assertArrayEquals( $userGroups, $manager->getUserGroups( $user ),
			false, 'user groups are correct ' );
		$this->assertArrayEquals( $formerGroups, $manager->getUserFormerGroups( $user ),
			false, 'user former groups are correct ' );
		$this->assertArrayEquals(
			$expected,
			$manager->getUserAutopromoteOnceGroups( $user, 'EVENT' )
		);
	}

	public function testAddUserToAutopromoteOnceGroupsForeignDomain() {
		$siteConfig = new SiteConfiguration();
		$siteConfig->wikis = [ 'TEST_DOMAIN' ];
		$this->setMwGlobals( 'wgConf', $siteConfig );

		$this->overrideConfigValue( MainConfigNames::LocalDatabases, [ 'TEST_DOMAIN' ] );

		$manager = $this->getServiceContainer()
			->getUserGroupManagerFactory()
			->getUserGroupManager( 'TEST_DOMAIN' );
		$user = $this->getTestUser()->getUser();
		$this->expectException( PreconditionException::class );
		$this->assertSame( [], $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	public function testAddUserToAutopromoteOnceGroupsAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'TEST' );
		$this->assertSame( [], $manager->addUserToAutopromoteOnceGroups( $anon, 'TEST' ) );
	}

	public function testAddUserToAutopromoteOnceGroupsReadOnly() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'TEST' );
		$this->assertSame( [], $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	public function testAddUserToAutopromoteOnceGroupsNoGroups() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$this->assertSame( [], $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	public function testAddUserToAutopromoteOnceGroupsSuccess() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager( [
			MainConfigNames::AutopromoteOnce => [ 'EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ]
		] );
		$this->assertNotContains( 'autopromoteonce', $manager->getUserGroups( $user ) );
		$hookCalled = false;
		$this->setTemporaryHook(
			'UserGroupsChanged',
			function ( User $hookUser, array $added, array $removed ) use ( $user, &$hookCalled ) {
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertArrayEquals( [ 'autopromoteonce' ], $added );
				$this->assertSame( [], $removed );
				$hookCalled = true;
			}
		);
		$manager->addUserToAutopromoteOnceGroups( $user, 'EVENT' );
		$this->assertContains( 'autopromoteonce', $manager->getUserGroups( $user ) );
		$this->assertTrue( $hookCalled );
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action', 'log_params' ] )
			->from( 'logging' )
			->where( [ 'log_type' => 'rights' ] )
			->assertResultSet( [ [ 'rights',
				'autopromote',
				LogEntryBase::makeParamBlob( [
					'4::oldgroups' => [],
					'5::newgroups' => [ 'autopromoteonce' ],
				] )
			] ] );
	}

	/**
	 * @dataProvider provideAutopromoteOnceGroupsRecentChanges
	 */
	public function testAddUserToAutopromoteOnceGroupsRecentChanges( array $autoPromoteOnceGroups ) {
		$user = $this->getTestUser()->getUser();

		// Setup one-shot autopromote conditions for the groups we would like to trigger autopromotion into
		$autoPromoteOnce = [];
		foreach ( $autoPromoteOnceGroups as $groupName ) {
			$autoPromoteOnce[$groupName] = [ APCOND_EDITCOUNT, 0 ];
		}

		$rcExcludedGroups = [ 'autopromoteonce-excluded' ];

		$manager = $this->getManager( [
			MainConfigNames::AutopromoteOnce => [
				'EVENT' => $autoPromoteOnce
			],
			MainConfigNames::AutopromoteOnceLogInRC => true,
			MainConfigNames::AutopromoteOnceRCExcludedGroups => $rcExcludedGroups
		] );

		// Add the test user to an unrelated group to verify autopromote RC exclusion ignores these.
		$manager->addUserToGroup( $user, 'sysop' );
		$preAutopromoteGroups = $manager->getUserGroups( $user );

		foreach ( $autoPromoteOnceGroups as $groupName ) {
			$this->assertNotContains( $groupName, $manager->getUserGroups( $user ) );
		}

		$manager->addUserToAutopromoteOnceGroups( $user, 'EVENT' );

		foreach ( $autoPromoteOnceGroups as $groupName ) {
			$this->assertContains( $groupName, $manager->getUserGroups( $user ) );
		}

		$logQueryBuilder = $this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action', 'log_params' ] )
			->from( 'logging' )
			->where( [ 'log_type' => 'rights' ] );

		$logQueryBuilder->assertRowValue( [ 'rights',
			'autopromote',
			LogEntryBase::makeParamBlob( [
				'4::oldgroups' => $preAutopromoteGroups,
				'5::newgroups' => $manager->getUserGroups( $user ),
			] )
		] );

		$logId = $logQueryBuilder
			->clearFields()
			->field( 'log_id' )
			->fetchField();

		if ( !array_diff( $autoPromoteOnceGroups, $rcExcludedGroups ) ) {
			$this->newSelectQueryBuilder()
				->select( [ 'rc_logid' ] )
				->from( 'recentchanges' )
				->where( [ 'rc_source' => RecentChange::SRC_LOG ] )
				->assertEmptyResult();
			return;
		}

		$this->newSelectQueryBuilder()
			->select( [ 'rc_logid' ] )
			->from( 'recentchanges' )
			->where( [ 'rc_source' => RecentChange::SRC_LOG ] )
			->assertFieldValue( $logId );
	}

	public static function provideAutopromoteOnceGroupsRecentChanges(): iterable {
		yield 'autopromotion into excluded group' => [ [ 'autopromoteonce-excluded' ] ];
		yield 'autopromotion into excluded and non-excluded group' => [
			[ 'autopromoteonce', 'autopromoteonce-excluded' ]
		];
		yield 'autopromotion into non-excluded group' => [ [ 'autopromoteonce' ] ];
	}

	private const CHANGEABLE_GROUPS_TEST_CONFIG = [
		MainConfigNames::GroupPermissions => [],
		MainConfigNames::AddGroups => [
			'sysop' => [ 'rollback' ],
			'bureaucrat' => [ 'sysop', 'bureaucrat' ],
		],
		MainConfigNames::RemoveGroups => [
			'sysop' => [ 'rollback' ],
			'bureaucrat' => [ 'sysop' ],
		],
		MainConfigNames::GroupsAddToSelf => [
			'sysop' => [ 'flood' ],
		],
		MainConfigNames::GroupsRemoveFromSelf => [
			'flood' => [ 'flood' ],
		],
	];

	private function assertGroupsEquals( array $expected, array $actual ) {
		// assertArrayEquals can compare without requiring the same order,
		// but the elements of an array are still required to be in the same order,
		// so just compare each element
		$this->assertArrayEquals( $expected['add'], $actual['add'], 'Add must match' );
		$this->assertArrayEquals( $expected['remove'], $actual['remove'], 'Remove must match' );
		$this->assertArrayEquals( $expected['add-self'], $actual['add-self'], 'Add-self must match' );
		$this->assertArrayEquals( $expected['remove-self'], $actual['remove-self'], 'Remove-self must match' );
	}

	public function testChangeableGroups() {
		$manager = $this->getManager( self::CHANGEABLE_GROUPS_TEST_CONFIG );
		$allGroups = $manager->listAllGroups();

		$user = $this->getTestUser()->getUser();
		$changeableGroups = $manager->getGroupsChangeableBy( new SimpleAuthority( $user, [ 'userrights' ] ) );
		$this->assertGroupsEquals(
			[
				'add' => $allGroups,
				'remove' => $allGroups,
				'add-self' => [],
				'remove-self' => [],
			],
			$changeableGroups
		);

		$user = $this->getTestUser( [ 'bureaucrat', 'sysop' ] )->getUser();
		$changeableGroups = $manager->getGroupsChangeableBy( new SimpleAuthority( $user, [] ) );
		$this->assertGroupsEquals(
			[
				'add' => [ 'sysop', 'bureaucrat', 'rollback' ],
				'remove' => [ 'sysop', 'rollback' ],
				'add-self' => [ 'flood' ],
				'remove-self' => [],
			],
			$changeableGroups
		);

		$user = $this->getTestUser( [ 'flood' ] )->getUser();
		$changeableGroups = $manager->getGroupsChangeableBy( new SimpleAuthority( $user, [] ) );
		$this->assertGroupsEquals(
			[
				'add' => [],
				'remove' => [],
				'add-self' => [],
				'remove-self' => [ 'flood' ],
			],
			$changeableGroups
		);
	}

	public static function provideChangeableByGroup() {
		yield 'sysop' => [ 'sysop', [
			'add' => [ 'rollback' ],
			'remove' => [ 'rollback' ],
			'add-self' => [ 'flood' ],
			'remove-self' => [],
		] ];
		yield 'flood' => [ 'flood', [
			'add' => [],
			'remove' => [],
			'add-self' => [],
			'remove-self' => [ 'flood' ],
		] ];
	}

	/**
	 * @dataProvider provideChangeableByGroup
	 */
	public function testChangeableByGroup( string $group, array $expected ) {
		$manager = $this->getManager( self::CHANGEABLE_GROUPS_TEST_CONFIG );
		$this->assertGroupsEquals( $expected, $manager->getGroupsChangeableByGroup( $group ) );
	}

	public function testGetUserPrivilegedGroups() {
		$this->overrideConfigValue( MainConfigNames::PrivilegedGroups, [ 'sysop', 'interface-admin', 'bar', 'baz' ] );
		$makeHook = function ( $invocationCount, User $userToMatch, array $groupsToAdd ) {
			return function ( $u, &$groups ) use ( $userToMatch, $invocationCount, $groupsToAdd ) {
				$invocationCount();
				$this->assertTrue( $userToMatch->equals( $u ) );
				$groups = array_merge( $groups, $groupsToAdd );
			};
		};

		$manager = $this->getManager();

		$user = new User;
		$user->setName( '*Unregistered 1234' );

		$this->assertArrayEquals(
			[],
			$manager->getUserPrivilegedGroups( $user )
		);

		$user = $this->getTestUser( [ 'sysop', 'bot', 'interface-admin' ] )->getUser();

		$this->setTemporaryHook( 'UserPrivilegedGroups',
			$makeHook( $this->countPromise( $this->once() ), $user, [ 'foo' ] ) );
		$this->setTemporaryHook( 'UserEffectiveGroups',
			$makeHook( $this->countPromise( $this->once() ), $user, [ 'bar', 'boom' ] ) );
		$this->assertArrayEquals(
			[ 'sysop', 'interface-admin', 'foo', 'bar' ],
			$manager->getUserPrivilegedGroups( $user )
		);
		$this->assertArrayEquals(
			[ 'sysop', 'interface-admin', 'foo', 'bar' ],
			$manager->getUserPrivilegedGroups( $user )
		);

		$this->setTemporaryHook( 'UserPrivilegedGroups',
			$makeHook( $this->countPromise( $this->once() ), $user, [ 'baz' ] ) );
		$this->setTemporaryHook( 'UserEffectiveGroups',
			$makeHook( $this->countPromise( $this->once() ), $user, [ 'baz' ] ) );
		$this->assertArrayEquals(
			[ 'sysop', 'interface-admin', 'foo', 'bar' ],
			$manager->getUserPrivilegedGroups( $user )
		);
		$this->assertArrayEquals(
			[ 'sysop', 'interface-admin', 'baz' ],
			$manager->getUserPrivilegedGroups( $user, IDBAccessObject::READ_NORMAL, true )
		);
		$this->assertArrayEquals(
			[ 'sysop', 'interface-admin', 'baz' ],
			$manager->getUserPrivilegedGroups( $user )
		);

		$this->setTemporaryHook( 'UserPrivilegedGroups', static function () {
		} );
		$this->setTemporaryHook( 'UserEffectiveGroups', static function () {
		} );
		$user = $this->getTestUser( [] )->getUser();
		$this->assertArrayEquals(
			[],
			$manager->getUserPrivilegedGroups( $user, IDBAccessObject::READ_NORMAL, true )
		);
	}
}
