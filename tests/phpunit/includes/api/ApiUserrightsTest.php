<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\MainConfigNames;
use MediaWiki\MainConfigSchema;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use TestUserRegistry;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiUserrights
 */
class ApiUserrightsTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::AddGroups => [],
			MainConfigNames::RemoveGroups => [],
		] );
	}

	/**
	 * Unsets $wgGroupPermissions['bureaucrat']['userrights'], and sets
	 * $wgAddGroups['bureaucrat'] and $wgRemoveGroups['bureaucrat'] to the
	 * specified values.
	 *
	 * @param array|bool $add Groups bureaucrats should be allowed to add, true for all
	 * @param array|bool $remove Groups bureaucrats should be allowed to remove, true for all
	 */
	protected function setPermissions( $add = [], $remove = [] ) {
		$this->setGroupPermissions( 'bureaucrat', 'userrights', false );

		if ( $add ) {
			$this->overrideConfigValue(
				MainConfigNames::AddGroups,
				[ 'bureaucrat' => $add ] + MainConfigSchema::getDefaultValue( MainConfigNames::AddGroups )
			);
		}
		if ( $remove ) {
			$this->overrideConfigValue(
				MainConfigNames::RemoveGroups,
				[ 'bureaucrat' => $remove ] + MainConfigSchema::getDefaultValue( MainConfigNames::RemoveGroups )
			);
		}
	}

	/**
	 * Perform an API userrights request that's expected to be successful.
	 *
	 * @param array|string $expectedGroups Group(s) that the user is expected
	 *   to have after the API request
	 * @param array $params Array to pass to doApiRequestWithToken().  'action'
	 *   => 'userrights' is implicit.  If no 'user' or 'userid' is specified,
	 *   we add a 'user' parameter.  If no 'add' or 'remove' is specified, we
	 *   add 'add' => 'sysop'.
	 * @param User|null $user The user that we're modifying.  The user must be
	 *   mutable, because we're going to change its groups!  null means that
	 *   we'll make up our own user to modify, and doesn't make sense if 'user'
	 *   or 'userid' is specified in $params.
	 */
	protected function doSuccessfulRightsChange(
		$expectedGroups = 'sysop', array $params = [], ?User $user = null
	) {
		$expectedGroups = (array)$expectedGroups;
		$params['action'] = 'userrights';

		if ( !$user ) {
			$user = $this->getMutableTestUser()->getUser();
		}

		$this->assertTrue( TestUserRegistry::isMutable( $user ),
			'Immutable user passed to doSuccessfulRightsChange!' );

		if ( !isset( $params['user'] ) && !isset( $params['userid'] ) ) {
			$params['user'] = $user->getName();
		}
		if ( !isset( $params['add'] ) && !isset( $params['remove'] ) ) {
			$params['add'] = 'sysop';
		}

		$res = $this->doApiRequestWithToken( $params );

		$user->clearInstanceCache();
		$this->getServiceContainer()->getPermissionManager()->invalidateUsersRightsCache();
		$this->assertSame(
			$expectedGroups, $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $user )
		);

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	/**
	 * Perform an API userrights request that's expected to fail.
	 *
	 * @param string $expectedCode Expected API error code
	 * @param array $params As for doSuccessfulRightsChange()
	 * @param User|null $user As for doSuccessfulRightsChange().  If there's no
	 *   user who will possibly be affected (such as if an invalid username is
	 *   provided in $params), pass null.
	 */
	private function doFailedRightsChange(
		$expectedCode, array $params = [], ?User $user = null
	) {
		$params['action'] = 'userrights';
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();

		$this->expectApiErrorCode( $expectedCode );

		if ( !$user ) {
			// If 'user' or 'userid' is specified and $user was not specified,
			// the user we're creating now will have nothing to do with the API
			// request, but that's okay, since we're just testing that it has
			// no groups.
			$user = $this->getMutableTestUser()->getUser();
		}

		$this->assertTrue( TestUserRegistry::isMutable( $user ),
			'Immutable user passed to doFailedRightsChange!' );

		if ( !isset( $params['user'] ) && !isset( $params['userid'] ) ) {
			$params['user'] = $user->getName();
		}
		if ( !isset( $params['add'] ) && !isset( $params['remove'] ) ) {
			$params['add'] = 'sysop';
		}
		$expectedGroups = $userGroupManager->getUserGroups( $user );

		try {
			$this->doApiRequestWithToken( $params );
		} finally {
			$user->clearInstanceCache();
			$this->assertSame( $expectedGroups, $userGroupManager->getUserGroups( $user ) );
		}
	}

	public function testAdd() {
		$this->doSuccessfulRightsChange();
	}

	public function testBlockedWithUserrights() {
		$user = $this->getTestSysop()->getUser();

		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [ 'targetUser' => $user, 'by' => $user ] );

		$this->doSuccessfulRightsChange();
	}

	public function testBlockedWithoutUserrights() {
		$user = $this->getTestSysop()->getUser();

		$this->setPermissions( true, true );

		$this->getServiceContainer()->getDatabaseBlockStore()
			->insertBlockWithParams( [ 'targetUser' => $user, 'by' => $user ] );

		$this->doFailedRightsChange( 'blocked' );
	}

	public function testAddMultiple() {
		$this->doSuccessfulRightsChange(
			[ 'bureaucrat', 'sysop' ],
			[ 'add' => 'bureaucrat|sysop' ]
		);
	}

	public function testTooFewExpiries() {
		$this->doFailedRightsChange(
			'toofewexpiries',
			[ 'add' => 'sysop|bureaucrat|bot', 'expiry' => 'infinity|tomorrow' ]
		);
	}

	public function testTooManyExpiries() {
		$this->doFailedRightsChange(
			'toofewexpiries',
			[ 'add' => 'sysop|bureaucrat', 'expiry' => 'infinity|tomorrow|never' ]
		);
	}

	public function testInvalidExpiry() {
		$this->doFailedRightsChange( 'invalidexpiry', [ 'expiry' => 'yummy lollipops!' ] );
	}

	public function testMultipleInvalidExpiries() {
		$this->doFailedRightsChange(
			'invalidexpiry',
			[ 'add' => 'sysop|bureaucrat', 'expiry' => 'foo|bar' ]
		);
	}

	public function testWithTag() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$user = $this->getMutableTestUser()->getUser();

		$this->doSuccessfulRightsChange( 'sysop', [ 'tags' => 'custom tag' ], $user );

		$this->assertSame(
			'custom tag',
			$this->getDb()->newSelectQueryBuilder()
				->select( 'ctd_name' )
				->from( 'logging' )
				->join( 'change_tag', null, 'ct_log_id = log_id' )
				->join( 'change_tag_def', null, 'ctd_id = ct_tag_id' )
				->where( [ 'log_namespace' => NS_USER, 'log_title' => strtr( $user->getName(), ' ', '_' ) ] )
				->caller( __METHOD__ )->fetchField() );
	}

	public function testWithoutTagPermission() {
		$this->getServiceContainer()->getChangeTagsStore()->defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$this->doFailedRightsChange(
			'tags-apply-no-permission',
			[ 'tags' => 'custom tag' ]
		);
	}

	public function testNonexistentUser() {
		$this->doFailedRightsChange(
			'nosuchuser',
			[ 'user' => 'Nonexistent user' ]
		);
	}

	public function testWebToken() {
		$sysop = $this->getTestSysop()->getUser();
		$user = $this->getMutableTestUser()->getUser();

		$token = $sysop->getEditToken( $user->getName() );

		$res = $this->doApiRequest( [
			'action' => 'userrights',
			'user' => $user->getName(),
			'add' => 'sysop',
			'token' => $token,
		] );

		$user->clearInstanceCache();
		$this->assertSame( [ 'sysop' ], $this->getServiceContainer()->getUserGroupManager()->getUserGroups( $user ) );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	/**
	 * Tests adding and removing various groups with various permissions.
	 *
	 * @dataProvider addAndRemoveGroupsProvider
	 * @param array|null $permissions [ [ $wgAddGroups, $wgRemoveGroups ] ] or null for 'userrights'
	 *   to be set in $wgGroupPermissions
	 * @param array $groupsToChange [ [ groups to add ], [ groups to remove ] ]
	 * @param array $expectedGroups Array of expected groups
	 */
	public function testAddAndRemoveGroups(
		?array $permissions, array $groupsToChange, array $expectedGroups
	) {
		if ( $permissions !== null ) {
			$this->setPermissions( $permissions[0], $permissions[1] );
		}

		$params = [
			'add' => implode( '|', $groupsToChange[0] ),
			'remove' => implode( '|', $groupsToChange[1] ),
		];

		// We'll take a bot so we have a group to remove
		$user = $this->getMutableTestUser( [ 'bot' ] )->getUser();

		$this->doSuccessfulRightsChange( $expectedGroups, $params, $user );
	}

	public static function addAndRemoveGroupsProvider() {
		return [
			'Simple add' => [
				[ [ 'sysop' ], [] ],
				[ [ 'sysop' ], [] ],
				[ 'bot', 'sysop' ]
			], 'Add with only remove permission' => [
				[ [], [ 'sysop' ] ],
				[ [ 'sysop' ], [] ],
				[ 'bot' ],
			], 'Add with global remove permission' => [
				[ [], true ],
				[ [ 'sysop' ], [] ],
				[ 'bot' ],
			], 'Simple remove' => [
				[ [], [ 'bot' ] ],
				[ [], [ 'bot' ] ],
				[],
			], 'Remove with only add permission' => [
				[ [ 'bot' ], [] ],
				[ [], [ 'bot' ] ],
				[ 'bot' ],
			], 'Remove with global add permission' => [
				[ true, [] ],
				[ [], [ 'bot' ] ],
				[ 'bot' ],
			], 'Add and remove same new group' => [
				null,
				[ [ 'sysop' ], [ 'sysop' ] ],
				// The userrights code ignores groups that are both added and removed.
				[ 'bot' ],
			], 'Add and remove same existing group' => [
				null,
				[ [ 'bot' ], [ 'bot' ] ],
				// Here the change to bot is also ignored.
				[ 'bot' ],
			],
		];
	}

	public function testWatched() {
		$user = $this->getMutableTestUser()->getUser();
		$userPage = Title::makeTitle( NS_USER, $user->getName() );
		$this->doSuccessfulRightsChange( 'sysop', [ 'watchuser' => true ], $user );
		$this->assertTrue( $this->getServiceContainer()->getWatchlistManager()
			->isWatched( $this->getTestSysop()->getUser(), $userPage ) );
	}
}
