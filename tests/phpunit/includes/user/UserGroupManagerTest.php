<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Tests\User;

use InvalidArgumentException;
use LogEntryBase;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use MWTimestamp;
use RequestContext;
use TestLogger;
use User;
use WebRequest;
use Wikimedia\Assert\PreconditionException;

/**
 * @covers \MediaWiki\User\UserGroupManager
 * @group Database
 */
class UserGroupManagerTest extends MediaWikiIntegrationTestCase {

	private const GROUP = 'user_group_manager_test_group';

	/** @var string */
	private $expiryTime;

	/**
	 * @param array $configOverrides
	 * @param UserEditTracker|null $userEditTrackerOverride
	 * @return UserGroupManager
	 */
	private function getManager(
		array $configOverrides = [],
		UserEditTracker $userEditTrackerOverride = null,
		callable $callback = null
	) : UserGroupManager {
		$services = MediaWikiServices::getInstance();
		return new UserGroupManager(
			new ServiceOptions(
				UserGroupManager::CONSTRUCTOR_OPTIONS,
				$configOverrides,
				[
					'Autopromote' => [
						'autoconfirmed' => [ APCOND_EDITCOUNT, 0 ]
					],
					'AutopromoteOnce' => [],
					'GroupPermissions' => [
						self::GROUP => [
							'runtest' => true,
						]
					],
					'ImplicitGroups' => [ '*', 'user', 'autoconfirmed' ],
					'RevokePermissions' => [],
				],
				$services->getMainConfig()
			),
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$userEditTrackerOverride ?? $services->getUserEditTracker(),
			new TestLogger(),
			$callback ? [ $callback ] : []
		);
	}

	protected function setUp() : void {
		parent::setUp();
		$this->tablesUsed[] = 'user';
		$this->tablesUsed[] = 'user_groups';
		$this->tablesUsed[] = 'user_former_groups';
		$this->tablesUsed[] = 'logging';
		$this->expiryTime = wfTimestamp( TS_MW, time() + 100500 );
	}

	/**
	 * @param UserGroupManager $manager
	 * @param UserIdentity $user
	 * @param string $group
	 * @param string|null $expiry
	 */
	private function assertMembership(
		UserGroupManager $manager,
		UserIdentity $user,
		string $group,
		string $expiry = null
	) {
		$this->assertContains( $group, $manager->getUserGroups( $user ) );
		$memberships = $manager->getUserGroupMemberships( $user );
		$this->assertArrayHasKey( $group, $memberships );
		$membership = $memberships[$group];
		$this->assertSame( $group, $membership->getGroup() );
		$this->assertSame( $user->getId(), $membership->getUserId() );
		$this->assertSame( $expiry, $membership->getExpiry() );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::newGroupMembershipFromRow
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::newGroupMembershipFromRow
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserImplicitGroups
	 */
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
			'Sanity: added user to group'
		);
		$this->assertArrayEquals(
			[ '*', 'user', 'autoconfirmed' ],
			$manager->getUserImplicitGroups( $user )
		);

		$user = User::newFromName( 'UTUser1' );
		$this->assertSame( [ '*' ], $manager->getUserImplicitGroups( $user ) );

		$manager = $this->getManager( [ 'Autopromote' => [
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
			$manager->getUserImplicitGroups( $user, UserGroupManager::READ_NORMAL, true )
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
	}

	public function provideGetEffectiveGroups() {
		yield [ [], [ '*', 'user', 'autoconfirmed' ] ];
		yield [ [ 'bureaucrat', 'test' ], [ '*', 'user', 'autoconfirmed', 'bureaucrat', 'test' ] ];
		yield [ [ 'autoconfirmed', 'test' ], [ '*', 'user', 'autoconfirmed', 'test' ] ];
	}

	/**
	 * @dataProvider provideGetEffectiveGroups
	 * @covers \MediaWiki\User\UserGroupManager::getUserEffectiveGroups
	 */
	public function testGetEffectiveGroups( $userGroups, $effectiveGroups ) {
		$manager = $this->getManager();
		$user = $this->getTestUser( $userGroups )->getUser();
		$this->assertArrayEquals( $effectiveGroups, $manager->getUserEffectiveGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserEffectiveGroups
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToGroup
	 * @covers \MediaWiki\User\UserGroupManager::getUserGroups
	 * @covers \MediaWiki\User\UserGroupManager::getUserGroupMemberships
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToGroup
	 */
	public function testAddUserToGroupReadonly() {
		$user = $this->getTestUser()->getUser();
		MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->addUserToGroup( $user, 'test' ) );
		$this->assertNotContains( 'test', $manager->getUserGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToGroup
	 */
	public function testAddUserToGroupAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon', 0 );
		$this->expectException( InvalidArgumentException::class );
		$manager->addUserToGroup( $anon, 'test' );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToGroup
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToGroup
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserGroupMemberships
	 */
	public function testGetUserGroupMembershipsForAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon', 0 );

		$this->assertEmpty( $manager->getUserGroupMemberships( $anon ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserFormerGroups
	 */
	public function testGetUserFormerGroupsForAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon', 0 );

		$this->assertEmpty( $manager->getUserFormerGroups( $anon ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 * @covers \MediaWiki\User\UserGroupManager::getUserFormerGroups
	 * @covers \MediaWiki\User\UserGroupManager::getUserGroups
	 * @covers \MediaWiki\User\UserGroupManager::getUserGroupMemberships
	 */
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
		$this->assertContains( self::GROUP,
			$manager->getUserFormerGroups( $user ) ); // From cache
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 */
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

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 */
	public function testRemoveUserFromGroupReadOnly() {
		$user = $this->getTestUser( [ 'test' ] )->getUser();
		MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertContains( 'test', $manager->getUserGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 */
	public function testRemoveUserFromGroupAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'Anon', 0 );
		$this->expectException( InvalidArgumentException::class );
		$manager->removeUserFromGroup( $anon, 'test' );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::removeUserFromGroup
	 */
	public function testRemoveUserFromGroupCallback() {
		$user = $this->getTestUser( [ 'test' ] )->getUser();
		$calledCount = 0;
		$callback = function ( UserIdentity $callbackUser ) use ( $user, &$calledCount ) {
			$this->assertTrue( $callbackUser->equals( $user ) );
			$calledCount += 1;
		};
		$manager = $this->getManager( [], null, $callback );
		$this->assertTrue( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertNotContains( 'test', $manager->getUserGroups( $user ) );
		$this->assertSame( 1, $calledCount );
		$this->assertFalse( $manager->removeUserFromGroup( $user, 'test' ) );
		$this->assertSame( 1, $calledCount );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::purgeExpired
	 */
	public function testPurgeExpired() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$expiryInPast = wfTimestamp( TS_MW, time() - 100500 );
		$this->assertTrue(
			$manager->addUserToGroup( $user, 'expired', $expiryInPast ),
			'Sanity: can add expired group'
		);
		$manager->purgeExpired();
		$this->assertNotContains( 'expired', $manager->getUserGroups( $user ) );
		$this->assertArrayNotHasKey( 'expired', $manager->getUserGroupMemberships( $user ) );
		$this->assertContains( 'expired', $manager->getUserFormerGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::purgeExpired
	 */
	public function testPurgeExpiredReadOnly() {
		MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()->setReason( 'TEST' );
		$manager = $this->getManager();
		$this->assertFalse( $manager->purgeExpired() );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::listAllGroups
	 */
	public function testGetAllGroups() {
		$manager = $this->getManager( [
			'GroupPermissions' => [
				__METHOD__ => [ 'test' => true ],
				'implicit' => [ 'test' => true ]
			],
			'RevokePermissions' => [
				'revoked' => [ 'test' => true ]
			],
			'ImplicitGroups' => [ 'implicit' ]
		] );
		$this->assertArrayEquals( [ __METHOD__, 'revoked' ], $manager->listAllGroups() );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::listAllImplicitGroups
	 */
	public function testGetAllImplicitGroups() {
		$manager = $this->getManager( [
			'ImplicitGroups' => [ __METHOD__ ]
		] );
		$this->assertArrayEquals( [ __METHOD__ ], $manager->listAllImplicitGroups() );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::loadGroupMembershipsFromArray
	 */
	public function testLoadGroupMembershipsFromArray() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$row = new \stdClass();
		$row->ug_user = $user->getId();
		$row->ug_group = 'test';
		$row->ug_expiry = null;
		$manager->loadGroupMembershipsFromArray( $user, [ $row ], UserGroupManager::READ_NORMAL );
		$memberships = $manager->getUserGroupMemberships( $user );
		$this->assertCount( 1, $memberships );
		$this->assertArrayHasKey( 'test', $memberships );
		$this->assertSame( $user->getId(), $memberships['test']->getUserId() );
		$this->assertSame( 'test', $memberships['test']->getGroup() );
	}

	public function provideGetUserAutopromoteEmailConfirmed() {
		$successUserMock = $this->createNoOpMock( User::class, [ 'getEmail', 'getEmailAuthenticationTimestamp' ] );
		$successUserMock->expects( $this->once() )
			->method( 'getEmail' )
			->willReturn( 'test@test.com' );
		$successUserMock->expects( $this->once() )
			->method( 'getEmailAuthenticationTimestamp' )
			->willReturn( wfTimestampNow() );
		yield 'Successfull autopromote' => [
			true, $successUserMock, [ 'test_autoconfirmed' ]
		];
		$emailAuthMock = $this->createNoOpMock( User::class, [ 'getEmail' ] );
		$emailAuthMock->expects( $this->once() )
			->method( 'getEmail' )
			->willReturn( 'test@test.com' );
		yield 'wgEmailAuthentication is false' => [
			false, $emailAuthMock, [ 'test_autoconfirmed' ]
		];
		$invalidEmailMock = $this->createNoOpMock( User::class, [ 'getEmail' ] );
		$invalidEmailMock
			->expects( $this->once() )
			->method( 'getEmail' )
			->willReturn( 'INVALID!' );
		yield 'Invalid email' => [
			true, $invalidEmailMock, []
		];
		$nullTimestampMock = $this->createNoOpMock( User::class, [ 'getEmail', 'getEmailAuthenticationTimestamp' ] );
		$nullTimestampMock->expects( $this->once() )
			->method( 'getEmail' )
			->willReturn( 'test@test.com' );
		$nullTimestampMock->expects( $this->once() )
			->method( 'getEmailAuthenticationTimestamp' )
			->willReturn( null );
		yield 'Invalid email auth timestamp' => [
			true, $nullTimestampMock, []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteEmailConfirmed
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param bool $emailAuthentication
	 * @param User $user
	 * @param array $expected
	 */
	public function testGetUserAutopromoteEmailConfirmed(
		bool $emailAuthentication,
		User $user,
		array $expected
	) {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_EMAILCONFIRMED ] ],
			'EmailAuthentication' => $emailAuthentication
		] );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public function provideGetUserAutopromoteEditCount() {
		yield 'Successfull promote'	=> [
			5, true, 10, [ 'test_autoconfirmed' ]
		];
		yield 'Required edit count negative' => [
			-1, false, 10, [ 'test_autoconfirmed' ]
		];
		yield 'Anon' => [
			5, false, 100, []
		];
		yield 'Not enough edits' => [
			100, true, 10, []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteEditCount
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param int $requiredCount
	 * @param bool $userRegistered
	 * @param int $userEditCount
	 */
	public function testGetUserAutopromoteEditCount(
		int $requiredCount,
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
			$userEditTrackerMock->expects( $this->once() )
				->method( 'getUserEditCount' )
				->with( $user )
				->willReturn( $userEditCount );
		} else {
			$user = User::newFromName( 'UTUser1' );
			$userEditTrackerMock->expects( $this->never() )
				->method( 'getUserEditCount' );
		}
		$manager = $this->getManager(
			[
				'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_EDITCOUNT, $requiredCount ] ]
			],
			$userEditTrackerMock
		);
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public function provideGetUserAutopromoteAge() {
		yield 'Successfull promote' => [
			1000, MWTimestamp::convert( TS_MW, time() - 1000000 ), [ 'test_autoconfirmed' ]
		];
		yield 'Not old enough' => [
			10000000, MWTimestamp::now(), []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteAge
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param int $requiredAge
	 * @param string $registrationTs
	 * @param array $expected
	 */
	public function testGetUserAutopromoteAge(
		int $requiredAge,
		string $registrationTs,
		array $expected
	) {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_AGE, $requiredAge ] ]
		] );
		$user = $this->createNoOpMock( User::class, [ 'getRegistration' ] );
		$user->expects( $this->once() )
			->method( 'getRegistration' )
			->willReturn( $registrationTs );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	/**
	 * @dataProvider provideGetUserAutopromoteAge
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param int $requiredAge
	 * @param string $firstEditTs
	 * @param array $expected
	 */
	public function testGetUserAutopromoteEditAge(
		int $requiredAge,
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
			'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_AGE_FROM_EDIT, $requiredAge ] ]
		], $mockUserEditTracker );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public function provideGetUserAutopromoteGroups() {
		yield 'Successfull promote' => [
			[ 'group1', 'group2' ], [ 'group1', 'group2' ], [ 'test_autoconfirmed' ]
		];
		yield 'Not enough groups to promote' => [
			[ 'group1', 'group2' ], [ 'group1' ], []
		];
	}

	/**
	 * @dataProvider provideGetUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param int $requiredAge
	 * @param string $firstEditTs
	 * @param array $expected
	 */
	public function testGetUserAutopromoteGroups(
		array $requiredGroups,
		array $userGroups,
		array $expected
	) {
		$user = $this->getTestUser( $userGroups )->getUser();
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => array_merge( [ APCOND_INGROUPS ], $requiredGroups ) ]
		] );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	public function provideGetUserAutopromoteIP() {
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
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @param array $condition
	 * @param string $userIp
	 * @param array $expected
	 */
	public function testGetUserAutopromoteIP(
		array $condition,
		string $userIp,
		array $expected
	) {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => $condition ]
		] );
		$requestMock = $this->createNoOpMock( WebRequest::class, [ 'getIP' ] );
		$requestMock->expects( $this->once() )
			->method( 'getIP' )
			->willReturn( $userIp );
		$user = $this->createNoOpMock( User::class, [ 'getRequest' ] );
		$user->expects( $this->once() )
			->method( 'getRequest' )
			->willReturn( $requestMock );
		$this->assertArrayEquals( $expected, $manager->getUserAutopromoteGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 */
	public function testGetUserAutopromoteGroupsHook() {
		$manager = $this->getManager( [
			'Autopromote' => []
		] );
		$user = $this->getTestUser()->getUser();
		$this->setTemporaryHook(
			'GetAutoPromoteGroups',
			function ( User $hookUser, array &$promote ) use ( $user ){
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertEmpty( $promote );
				$promote[] = 'from_hook';
			}
		);
		$this->assertArrayEquals( [ 'from_hook' ], $manager->getUserAutopromoteGroups( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 * @covers \MediaWiki\User\UserGroupManager::recCheckCondition
	 */
	public function testGetUserAutopromoteComplexCondition() {
		$manager = $this->getManager( [
			'Autopromote' => [
				'test_autoconfirmed' => [ '&',
					[ APCOND_INGROUPS, 'group1' ],
					[ '!', [ APCOND_INGROUPS, 'group2' ] ],
					[ '^', [ APCOND_INGROUPS, 'group3' ], [ APCOND_INGROUPS, 'group4' ] ],
					[ '|', [ APCOND_INGROUPS, 'group5' ], [ APCOND_INGROUPS, 'group6' ] ]
				]
			]
		] );
		$this->assertEmpty( $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1' ] )->getUser() )
		);
		$this->assertEmpty( $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group2' ] )->getUser() )
		);
		$this->assertEmpty( $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group3', 'group4' ] )->getUser() )
		);
		$this->assertEmpty( $manager->getUserAutopromoteGroups(
			$this->getTestUser( [ 'group1', 'group3' ] )->getUser() )
		);
		$this->assertArrayEquals(
			[ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $this->getTestUser( [ 'group1', 'group3', 'group5' ] )->getUser() )
		);
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 */
	public function testGetUserAutopromoteBot() {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_ISBOT ] ]
		] );
		$notBot = $this->getTestUser()->getUser();
		$this->assertEmpty( $manager->getUserAutopromoteGroups( $notBot ) );
		$bot = $this->getTestUser( [ 'bot' ] )->getUser();
		$this->assertArrayEquals( [ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $bot ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 */
	public function testGetUserAutopromoteBlocked() {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => [ APCOND_BLOCKED ] ]
		] );
		$nonBlockedUser = $this->getTestUser()->getUser();
		$this->assertEmpty( $manager->getUserAutopromoteGroups( $nonBlockedUser ) );
		$blockedUser = $this->getTestUser( [ 'blocked' ] )->getUser();
		$block = new DatabaseBlock();
		$block->setTarget( $blockedUser );
		$block->setBlocker( $this->getTestSysop()->getUser() );
		$block->isSitewide( true );
		$block->insert();
		$this->assertArrayEquals( [ 'test_autoconfirmed' ],
			$manager->getUserAutopromoteGroups( $blockedUser ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 */
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

		$sinfo = [
			'sessionId' => 'd612ee607c87e749ef14da4983a702cd',
			'userId' => $user->getId(),
			'ip' => '192.0.2.0',
			'headers' => [
				'USER-AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0'
			]
		];
		$this->setMwGlobals( [
			'wgAutopromote' => [ 'test_autoconfirmed' => [ '&', APCOND_BLOCKED ] ],
		] );
		// Variables are unused but needed to reproduce the failure
		$sc = RequestContext::importScopedSession( $sinfo ); // load new context
		$info = $context->exportSession();

		$this->assertEmpty( $user->getBlock() );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 */
	public function testGetUserAutopromoteInvalid() {
		$manager = $this->getManager( [
			'Autopromote' => [ 'test_autoconfirmed' => [ 999 ] ]
		] );
		$user = $this->getTestUser()->getUser();
		$this->expectException( InvalidArgumentException::class );
		$manager->getUserAutopromoteGroups( $user );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteGroups
	 * @covers \MediaWiki\User\UserGroupManager::checkCondition
	 */
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
			'Autopromote' => [ 'test_autoconfirmed' => [ 999, 'ARGUMENT' ] ]
		] );
		$this->assertArrayEquals( [ 'test_autoconfirmed' ], $manager->getUserAutopromoteGroups( $user ) );
	}

	public function provideGetUserAutopromoteOnce() {
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
	 * @covers \MediaWiki\User\UserGroupManager::getUserAutopromoteOnceGroups
	 * @param array $config
	 * @param array $formerGroups
	 * @param array $userGroups
	 * @param array $expected
	 */
	public function testGetUserAutopromoteOnce(
		array $config,
		array $formerGroups,
		array $userGroups,
		array $expected
	) {
		$manager = $this->getManager( [
			'AutopromoteOnce' => $config
		] );
		$user = $this->getTestUser()->getUser();
		foreach ( $userGroups as $group ) {
			$manager->addUserToGroup( $user, $group );
		}
		foreach ( $formerGroups as $formerGroup ) {
			$manager->addUserToGroup( $user, $formerGroup );
			$manager->removeUserFromGroup( $user, $formerGroup );
		}
		$this->assertArrayEquals( $userGroups, $manager->getUserGroups( $user ),
			false, 'Sanity: user groups are correct ' );
		$this->assertArrayEquals( $formerGroups, $manager->getUserFormerGroups( $user ),
			false, 'Sanity: user former groups are correct ' );
		$this->assertArrayEquals(
			$expected,
			$manager->getUserAutopromoteOnceGroups( $user, 'EVENT' )
		);
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToAutopromoteOnceGroups
	 */
	public function testAddUserToAutopromoteOnceGroupsForeignDomain() {
		$manager = MediaWikiServices::getInstance()
			->getUserGroupManagerFactory()
			->getUserGroupManager( 'TEST_DOMAIN' );
		$user = $this->getTestUser()->getUser();
		$this->expectException( PreconditionException::class );
		$this->assertEmpty( $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToAutopromoteOnceGroups
	 */
	public function testAddUserToAutopromoteOnceGroupsAnon() {
		$manager = $this->getManager();
		$anon = new UserIdentityValue( 0, 'TEST', 0 );
		$this->assertEmpty( $manager->addUserToAutopromoteOnceGroups( $anon, 'TEST' ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToAutopromoteOnceGroups
	 */
	public function testAddUserToAutopromoteOnceGroupsReadOnly() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()->setReason( 'TEST' );
		$this->assertEmpty( $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToAutopromoteOnceGroups
	 */
	public function testAddUserToAutopromoteOnceGroupsNoGroups() {
		$manager = $this->getManager();
		$user = $this->getTestUser()->getUser();
		$this->assertEmpty( $manager->addUserToAutopromoteOnceGroups( $user, 'TEST' ) );
	}

	/**
	 * @covers \MediaWiki\User\UserGroupManager::addUserToAutopromoteOnceGroups
	 */
	public function testAddUserToAutopromoteOnceGroupsSuccess() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager( [
			'AutopromoteOnce' => [ 'EVENT' => [ 'autopromoteonce' => [ APCOND_EDITCOUNT, 0 ] ] ]
		] );
		$this->assertNotContains( 'autopromoteonce', $manager->getUserGroups( $user ) );
		$hookCalled = false;
		$this->setTemporaryHook(
			'UserGroupsChanged',
			function ( User $hookUser, array $added, array $removed ) use ( $user, &$hookCalled ) {
				$this->assertTrue( $user->equals( $hookUser ) );
				$this->assertArrayEquals( [ 'autopromoteonce' ], $added );
				$this->assertEmpty( $removed );
				$hookCalled = true;
			}
		);
		$manager->addUserToAutopromoteOnceGroups( $user, 'EVENT' );
		$this->assertContains( 'autopromoteonce', $manager->getUserGroups( $user ) );
		$this->assertTrue( $hookCalled );
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action', 'log_params' ],
			[ 'log_type' => 'rights' ],
			[ [ 'rights',
				'autopromote',
				LogEntryBase::makeParamBlob( [
					'4::oldgroups' => [],
					'5::newgroups' => [ 'autopromoteonce' ],
				] )
			] ]
		);
	}
}
