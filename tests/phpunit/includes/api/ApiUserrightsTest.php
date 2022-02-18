<?php

use MediaWiki\Block\DatabaseBlock;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUserrights
 */
class ApiUserrightsTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed = array_merge(
			$this->tablesUsed,
			[ 'change_tag', 'change_tag_def', 'logging' ]
		);
		$this->setMwGlobals( [
			'wgAddGroups' => [],
			'wgRemoveGroups' => [],
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
			$this->mergeMwGlobalArrayValue( 'wgAddGroups', [ 'bureaucrat' => $add ] );
		}
		if ( $remove ) {
			$this->mergeMwGlobalArrayValue( 'wgRemoveGroups', [ 'bureaucrat' => $remove ] );
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
		$expectedGroups = 'sysop', array $params = [], User $user = null
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
	 * @param string $expectedException Expected exception text
	 * @param array $params As for doSuccessfulRightsChange()
	 * @param User|null $user As for doSuccessfulRightsChange().  If there's no
	 *   user who will possibly be affected (such as if an invalid username is
	 *   provided in $params), pass null.
	 */
	protected function doFailedRightsChange(
		$expectedException, array $params = [], User $user = null
	) {
		$params['action'] = 'userrights';
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();

		$this->expectException( ApiUsageException::class );
		$this->expectExceptionMessage( $expectedException );

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

		$block = new DatabaseBlock( [ 'address' => $user, 'by' => $user, ] );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->doSuccessfulRightsChange();
		} finally {
			$blockStore->deleteBlock( $block );
			$user->clearInstanceCache();
		}
	}

	public function testBlockedWithoutUserrights() {
		$user = $this->getTestSysop()->getUser();

		$this->setPermissions( true, true );

		$block = new DatabaseBlock( [ 'address' => $user, 'by' => $user ] );
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $block );

		try {
			$this->doFailedRightsChange( 'You have been blocked from editing.' );
		} finally {
			$blockStore->deleteBlock( $block );
			$user->clearInstanceCache();
		}
	}

	public function testAddMultiple() {
		$this->doSuccessfulRightsChange(
			[ 'bureaucrat', 'sysop' ],
			[ 'add' => 'bureaucrat|sysop' ]
		);
	}

	public function testTooFewExpiries() {
		$this->doFailedRightsChange(
			'2 expiry timestamps were provided where 3 were needed.',
			[ 'add' => 'sysop|bureaucrat|bot', 'expiry' => 'infinity|tomorrow' ]
		);
	}

	public function testTooManyExpiries() {
		$this->doFailedRightsChange(
			'3 expiry timestamps were provided where 2 were needed.',
			[ 'add' => 'sysop|bureaucrat', 'expiry' => 'infinity|tomorrow|never' ]
		);
	}

	public function testInvalidExpiry() {
		$this->doFailedRightsChange( 'Invalid expiry time', [ 'expiry' => 'yummy lollipops!' ] );
	}

	public function testMultipleInvalidExpiries() {
		$this->doFailedRightsChange(
			'Invalid expiry time "foo".',
			[ 'add' => 'sysop|bureaucrat', 'expiry' => 'foo|bar' ]
		);
	}

	public function testWithTag() {
		ChangeTags::defineTag( 'custom tag' );

		$user = $this->getMutableTestUser()->getUser();

		$this->doSuccessfulRightsChange( 'sysop', [ 'tags' => 'custom tag' ], $user );

		$dbr = wfGetDB( DB_REPLICA );
		$this->assertSame(
			'custom tag',
			$dbr->selectField(
				[ 'change_tag', 'logging', 'change_tag_def' ],
				'ctd_name',
				[
					'ct_log_id = log_id',
					'log_namespace' => NS_USER,
					'log_title' => strtr( $user->getName(), ' ', '_' )
				],
				__METHOD__,
				[ 'change_tag_def' => [ 'JOIN', 'ctd_id = ct_tag_id' ] ]
			)
		);
	}

	public function testWithoutTagPermission() {
		ChangeTags::defineTag( 'custom tag' );

		$this->setGroupPermissions( 'user', 'applychangetags', false );

		$this->doFailedRightsChange(
			'You do not have permission to apply change tags along with your changes.',
			[ 'tags' => 'custom tag' ]
		);
	}

	public function testNonexistentUser() {
		$this->doFailedRightsChange(
			'There is no user by the name "Nonexistent user". Check your spelling.',
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

	public function addAndRemoveGroupsProvider() {
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
				// The userrights code does removals before adds, so it doesn't remove the sysop
				// group here and only adds it.
				[ 'bot', 'sysop' ],
			], 'Add and remove same existing group' => [
				null,
				[ [ 'bot' ], [ 'bot' ] ],
				// But here it first removes the existing group and then re-adds it.
				[ 'bot' ],
			],
		];
	}
}
