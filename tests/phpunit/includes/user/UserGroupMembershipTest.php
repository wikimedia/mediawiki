<?php

/**
 * @group Database
 */
class UserGroupMembershipTest extends MediaWikiTestCase {

	protected $tablesUsed = [ 'user', 'user_groups' ];

	/**
	 * @var User Belongs to no groups
	 */
	protected $userNoGroups;
	/**
	 * @var User Belongs to the 'unittesters' group indefinitely, and the
	 * 'testwriters' group with expiry
	 */
	protected $userTester;
	/**
	 * @var string The timestamp, in TS_MW format, of the expiry of $userTester's
	 * membership in the 'testwriters' group
	 */
	protected $expiryTime;

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgGroupPermissions' => [
				'unittesters' => [
					'runtest' => true,
				],
				'testwriters' => [
					'writetest' => true,
				]
			]
		] );

		$this->userNoGroups = new User;
		$this->userNoGroups->setName( 'NoGroups' );
		$this->userNoGroups->addToDatabase();

		$this->userTester = new User;
		$this->userTester->setName( 'Tester' );
		$this->userTester->addToDatabase();
		$this->userTester->addGroup( 'unittesters' );
		$this->expiryTime = wfTimestamp( TS_MW, time() + 100500 );
		$this->userTester->addGroup( 'testwriters', $this->expiryTime );
	}

	/**
	 * @covers UserGroupMembership::insert
	 * @covers UserGroupMembership::delete
	 */
	public function testAddAndRemoveGroups() {
		$user = $this->getMutableTestUser()->getUser();

		// basic tests
		$ugm = new UserGroupMembership( $user->getId(), 'unittesters' );
		$this->assertTrue( $ugm->insert() );
		$user->clearInstanceCache();
		$this->assertContains( 'unittesters', $user->getGroups() );
		$this->assertArrayHasKey( 'unittesters', $user->getGroupMemberships() );
		$this->assertTrue( $user->isAllowed( 'runtest' ) );

		// try updating without allowUpdate. Should fail
		$ugm = new UserGroupMembership( $user->getId(), 'unittesters', $this->expiryTime );
		$this->assertFalse( $ugm->insert() );

		// now try updating with allowUpdate
		$this->assertTrue( $ugm->insert( 2 ) );
		$user->clearInstanceCache();
		$this->assertContains( 'unittesters', $user->getGroups() );
		$this->assertArrayHasKey( 'unittesters', $user->getGroupMemberships() );
		$this->assertTrue( $user->isAllowed( 'runtest' ) );

		// try removing the group
		$ugm->delete();
		$user->clearInstanceCache();
		$this->assertThat( $user->getGroups(),
			$this->logicalNot( $this->contains( 'unittesters' ) ) );
		$this->assertThat( $user->getGroupMemberships(),
			$this->logicalNot( $this->arrayHasKey( 'unittesters' ) ) );
		$this->assertFalse( $user->isAllowed( 'runtest' ) );

		// check that the user group is now in user_former_groups
		$this->assertContains( 'unittesters', $user->getFormerGroups() );
	}

	private function addUserTesterToExpiredGroup() {
		// put $userTester in a group with expiry in the past
		$ugm = new UserGroupMembership( $this->userTester->getId(), 'sysop', '20010102030405' );
		$ugm->insert();
	}

	/**
	 * @covers UserGroupMembership::getMembershipsForUser
	 */
	public function testGetMembershipsForUser() {
		$this->addUserTesterToExpiredGroup();

		// check that the user in no groups has no group memberships
		$ugms = UserGroupMembership::getMembershipsForUser( $this->userNoGroups->getId() );
		$this->assertEmpty( $ugms );

		// check that the user in 2 groups has 2 group memberships
		$testerUserId = $this->userTester->getId();
		$ugms = UserGroupMembership::getMembershipsForUser( $testerUserId );
		$this->assertCount( 2, $ugms );

		// check that the required group memberships are present on $userTester,
		// with the correct user IDs and expiries
		$expectedGroups = [ 'unittesters', 'testwriters' ];

		foreach ( $expectedGroups as $group ) {
			$this->assertArrayHasKey( $group, $ugms );
			$this->assertEquals( $ugms[$group]->getUserId(), $testerUserId );
			$this->assertEquals( $ugms[$group]->getGroup(), $group );

			if ( $group === 'unittesters' ) {
				$this->assertNull( $ugms[$group]->getExpiry() );
			} elseif ( $group === 'testwriters' ) {
				$this->assertEquals( $ugms[$group]->getExpiry(), $this->expiryTime );
			}
		}
	}

	/**
	 * @covers UserGroupMembership::getMembership
	 */
	public function testGetMembership() {
		$this->addUserTesterToExpiredGroup();

		// groups that the user doesn't belong to shouldn't be returned
		$ugm = UserGroupMembership::getMembership( $this->userNoGroups->getId(), 'sysop' );
		$this->assertFalse( $ugm );

		// implicit groups shouldn't be returned
		$ugm = UserGroupMembership::getMembership( $this->userNoGroups->getId(), 'user' );
		$this->assertFalse( $ugm );

		// expired groups shouldn't be returned
		$ugm = UserGroupMembership::getMembership( $this->userTester->getId(), 'sysop' );
		$this->assertFalse( $ugm );

		// groups that the user does belong to should be returned with correct properties
		$ugm = UserGroupMembership::getMembership( $this->userTester->getId(), 'unittesters' );
		$this->assertInstanceOf( UserGroupMembership::class, $ugm );
		$this->assertEquals( $ugm->getUserId(), $this->userTester->getId() );
		$this->assertEquals( $ugm->getGroup(), 'unittesters' );
		$this->assertNull( $ugm->getExpiry() );
	}
}
