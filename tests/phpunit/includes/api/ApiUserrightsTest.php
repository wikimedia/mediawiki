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
		global $wgGroupPermissions, $wgAddGroups, $wgRemoveGroups;

		$this->stashMwGlobals( 'wgGroupPermissions' );
		$wgGroupPermissions['bureaucrat']['userrights'] = false;

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
	 * @param User|null $user As for doSuccessfulRightsChange(), except the
	 *   user may be immutable.  If there's no user who will possibly be
	 *   affected (such as if an invalid username is provided in $params), pass
	 *   null.
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
			$user = $this->getTestUser()->getUser();
		}
		if ( !isset( $params['user'] ) && !isset( $params['userid'] ) ) {
			$params['user'] = $user->getName();
		}
		if ( !isset( $params['add'] ) && !isset( $params['remove'] ) ) {
			$params['add'] = 'sysop';
		}

		try {
			$this->doApiRequestWithToken( $params );
		} finally {
			$user->clearInstanceCache();
			$this->assertSame( [], $user->getGroups() );
		}
	}

	public function testAdd() {
		$this->doSuccessfulRightsChange();
	}

	public function testBlockedWithUserrights() {
		global $wgUser;

		$block = new Block( [ 'address' => $wgUser, 'by' => $wgUser->getId(), ] );
		$block->insert();

		$this->doSuccessfulRightsChange();

		$block->delete();
		$wgUser->clearInstanceCache();
	}

	public function testBlockedWithoutUserrights() {
		global $wgUser;

		$this->setPermissions( true, true );

		$block = new Block( [ 'address' => $wgUser, 'by' => $wgUser->getId() ] );
		$block->insert();

		try {
			$this->doFailedRightsChange( 'You have been blocked from editing.' );
		} finally {
			$block->delete();
			$wgUser->clearInstanceCache();
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
}
