<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiUserrights
 */
class ApiUserrightsTest extends ApiTestCase {
	/**
	 * Unsets $wgGroupPermissions['bureaucrat']['userrights'], and sets
	 * $wgAddGroups['bureaucrat'] and $wgRemoveGroups['bureaucrat'] to the
	 * specified values.
	 *
	 * @param array|bool $add Groups bureaucrats should be allowed to add, true for all
	 * @param array|bool $remove Groups bureaucrats should be allowed to remove, true for all
	 */
	protected function setPermissions( $add = [], $remove = [] ) {
		global $wgAddGroups, $wgRemoveGroups;

		$this->setGroupPermissions( 'bureaucrat', 'userrights', false );

		if ( $add ) {
			$this->stashMwGlobals( 'wgAddGroups' );
			$wgAddGroups['bureaucrat'] = $add;
		}
		if ( $remove ) {
			$this->stashMwGlobals( 'wgRemoveGroups' );
			$wgRemoveGroups['bureaucrat'] = $remove;
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
		$this->assertSame( $expectedGroups, $user->getGroups() );

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

		$this->setExpectedException( ApiUsageException::class, $expectedException );

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
		$expectedGroups = $user->getGroups();

		try {
			$this->doApiRequestWithToken( $params );
		} finally {
			$user->clearInstanceCache();
			$this->assertSame( $expectedGroups, $user->getGroups() );
		}
	}

	public function testAdd() {
		$this->doSuccessfulRightsChange();
	}

	public function testBlockedWithUserrights() {
		global $wgUser;

		$block = new Block( [ 'address' => $wgUser, 'by' => $wgUser->getId(), ] );
		$block->insert();

		try {
			$this->doSuccessfulRightsChange();
		} finally {
			$block->delete();
			$wgUser->clearInstanceCache();
		}
	}

	public function testBlockedWithoutUserrights() {
		$user = $this->getTestSysop()->getUser();

		$this->setPermissions( true, true );

		$block = new Block( [ 'address' => $user, 'by' => $user->getId() ] );
		$block->insert();

		try {
			$this->doFailedRightsChange( 'You have been blocked from editing.' );
		} finally {
			$block->delete();
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
				[ 'change_tag', 'logging' ],
				'ct_tag',
				[
					'ct_log_id = log_id',
					'log_namespace' => NS_USER,
					'log_title' => strtr( $user->getName(), ' ', '_' )
				],
				__METHOD__
			)
		);
	}

	public function testWithoutTagPermission() {
		global $wgGroupPermissions;

		ChangeTags::defineTag( 'custom tag' );

		$this->stashMwGlobals( 'wgGroupPermissions' );
		$wgGroupPermissions['user']['applychangetags'] = false;

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
		$this->assertSame( [ 'sysop' ], $user->getGroups() );

		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	/**
	 * Helper for testCanProcessExpiries that returns a mock ApiUserrights that either can or cannot
	 * process expiries.  Although the regular page can process expiries, we use a mock here to
	 * ensure that it's the result of canProcessExpiries() that makes a difference, and not some
	 * error in the way we construct the mock.
	 *
	 * @param bool $canProcessExpiries
	 */
	private function getMockForProcessingExpiries( $canProcessExpiries ) {
		$sysop = $this->getTestSysop()->getUser();
		$user = $this->getMutableTestUser()->getUser();

		$token = $sysop->getEditToken( 'userrights' );

		$main = new ApiMain( new FauxRequest( [
			'action' => 'userrights',
			'user' => $user->getName(),
			'add' => 'sysop',
			'token' => $token,
		] ) );

		$mockUserRightsPage = $this->getMockBuilder( UserrightsPage::class )
			->setMethods( [ 'canProcessExpiries' ] )
			->getMock();
		$mockUserRightsPage->method( 'canProcessExpiries' )->willReturn( $canProcessExpiries );

		$mockApi = $this->getMockBuilder( ApiUserrights::class )
			->setConstructorArgs( [ $main, 'userrights' ] )
			->setMethods( [ 'getUserRightsPage' ] )
			->getMock();
		$mockApi->method( 'getUserRightsPage' )->willReturn( $mockUserRightsPage );

		return $mockApi;
	}

	public function testCanProcessExpiries() {
		$mock1 = $this->getMockForProcessingExpiries( true );
		$this->assertArrayHasKey( 'expiry', $mock1->getAllowedParams() );

		$mock2 = $this->getMockForProcessingExpiries( false );
		$this->assertArrayNotHasKey( 'expiry', $mock2->getAllowedParams() );
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
		array $permissions = null, array $groupsToChange, array $expectedGroups
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
