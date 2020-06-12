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

use HashConfig;
use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use User;

/**
 * @covers \MediaWiki\User\UserGroupManager
 * @group Database
 */
class UserGroupManagerTest extends MediaWikiIntegrationTestCase {

	private const GROUP = 'user_group_manager_test_group';

	/** @var string */
	private $expiryTime;

	private function getManager(
		array $configOverrides = [],
		callable $callback = null
	) : UserGroupManager {
		$services = MediaWikiServices::getInstance();
		return new UserGroupManager(
			new ServiceOptions(
				UserGroupManager::CONSTRUCTOR_OPTIONS,
				new HashConfig(
					array_merge( [
						'GroupPermissions' => [
							self::GROUP => [
								'runtest' => true,
							]
						],
						'ImplicitGroups' => [ '*', 'user', 'autoconfirmed' ],
						'RevokePermissions' => []
					], $configOverrides )
				)
			),
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$callback ? [ $callback ] : []
		);
	}

	protected function setUp() : void {
		parent::setUp();
		$this->tablesUsed[] = 'user';
		$this->tablesUsed[] = 'user_groups';
		$this->tablesUsed[] = 'user_former_groups';
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

		$this->setMwGlobals( [
			'wgAutopromote' => [
				'dummy' => APCOND_EMAILCONFIRMED
			]
		] );
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
		$manager = $this->getManager( [], $callback );
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
}
