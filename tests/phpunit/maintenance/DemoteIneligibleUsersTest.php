<?php

namespace MediaWiki\Tests\Maintenance;

use DemoteIneligibleUsers;
use MediaWiki\MainConfigNames;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserIdentity;

/**
 * @covers \DemoteIneligibleUsers
 * @group Database
 */
class DemoteIneligibleUsersTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return DemoteIneligibleUsers::class;
	}

	/**
	 * Helper: create a test user and add them to the given groups.
	 *
	 * @param string[] $groups
	 * @return UserIdentity
	 */
	private function createUserInGroups( array $groups ): UserIdentity {
		return $this->getMutableTestUser( $groups )->getUserIdentity();
	}

	public function testExecuteNoDemotableGroupsConfigured(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
			],
		] );
		$this->maintenance->execute();
		$this->expectOutputString( "No groups are configured for automatic demotion, exiting.\n" );
	}

	public function testExecuteDemotesIneligibleUser(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$user = $this->createUserInGroups( [ 'sysop' ] );
		$this->maintenance->execute();

		// Verify the user is actually no longer in the group
		$groups = $this->getServiceContainer()
			->getUserGroupManager()
			->getUserGroups( $user );
		$this->assertNotContains( 'sysop', $groups );
	}

	public function testExecuteDryRunDoesNotActuallyDemote(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$user = $this->createUserInGroups( [ 'sysop' ] );
		$this->maintenance->setOption( 'dry-run', true );
		$this->maintenance->execute();

		// The user should still be in the group (no actual change)
		$groups = $this->getServiceContainer()
			->getUserGroupManager()
			->getUserGroups( $user );
		$this->assertContains( 'sysop', $groups, 'Dry-run must not actually remove groups' );
	}

	public function testExecuteDemotesUserFromMultipleGroups(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
			'bureaucrat' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$user = $this->createUserInGroups( [ 'sysop', 'bureaucrat' ] );
		$this->maintenance->execute();

		$groups = $this->getServiceContainer()
			->getUserGroupManager()
			->getUserGroups( $user );
		$this->assertNotContains( 'sysop', $groups );
		$this->assertNotContains( 'bureaucrat', $groups );
	}

	public function testExecuteDemotesMultipleUsers(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$user1 = $this->createUserInGroups( [ 'sysop' ] );
		$user2 = $this->createUserInGroups( [ 'sysop' ] );
		$this->maintenance->execute();

		$ugm = $this->getServiceContainer()->getUserGroupManager();
		$this->assertNotContains( 'sysop', $ugm->getUserGroups( $user1 ) );
		$this->assertNotContains( 'sysop', $ugm->getUserGroups( $user2 ) );
	}

	public function testExecuteSkipsEligibleUsersInDemotableGroup(): void {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 0 ],
				'demote' => true,
			],
		] );

		$eligibleUser = $this->createUserInGroups( [ 'sysop' ] );
		$this->maintenance->execute();

		// The eligible user should keep their group
		$groups = $this->getServiceContainer()
			->getUserGroupManager()
			->getUserGroups( $eligibleUser );
		$this->assertContains( 'sysop', $groups );
	}

	public function testExecuteOnlyDemotesGroupsWithDemoteFlag(): void {
		// 'sysop' has demote=true, 'bureaucrat' does not — only sysop should be removed
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
			'bureaucrat' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
			],
		] );

		$user = $this->createUserInGroups( [ 'sysop', 'bureaucrat' ] );
		$this->maintenance->execute();

		$ugm = $this->getServiceContainer()->getUserGroupManager();
		$this->assertNotContains( 'sysop', $ugm->getUserGroups( $user ) );
		$this->assertContains( 'bureaucrat', $ugm->getUserGroups( $user ) );
	}

	public function testSystemUserIsNotDemoted() {
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$user = $this->createUserInGroups( [ 'sysop' ] );

		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromUserIdentity' )
			->willReturnCallback( function ( UserIdentity $userIdentity ) {
				$userMock = $this->createMock( User::class );
				$userMock->method( 'isSystemUser' )->willReturn( true );
				$userMock->method( 'isRegistered' )->willReturn( true );
				$userMock->method( 'getId' )->willReturn( $userIdentity->getId() );
				return $userMock;
			} );
		$this->setService( 'UserFactory', $userFactoryMock );

		$this->maintenance->execute();

		// Verify the user is actually no longer in the group
		$groups = $this->getServiceContainer()
			->getUserGroupManager()
			->getUserGroups( $user );
		$this->assertContains( 'sysop', $groups );
	}

	/** @dataProvider provideLogRelaying */
	public function testLogRelaying( array $userGroups, array $relayLog, array $expectedWikis ): void {
		$groupAssignmentServiceMock = $this->createMock( UserGroupAssignmentService::class );
		$groupAssignmentServiceMock->expects( $this->once() )
			->method( 'saveChangesToUserGroups' )
			->willReturnCallback(
				function ( $perf, $target, $add, $remove, $expiries, $reason, $tags, $wikis ) use ( $expectedWikis ) {
					$this->assertArrayEquals( $expectedWikis, $wikis );
					return [];
				} );
		$this->setService( 'UserGroupAssignmentService', $groupAssignmentServiceMock );

		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'sysop' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
			'bureaucrat' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 9999 ],
				'demote' => true,
			],
		] );

		$this->createUserInGroups( $userGroups );
		if ( $relayLog ) {
			$this->maintenance->setOption( 'relay-log', $relayLog );
		}
		$this->maintenance->execute();
	}

	public static function provideLogRelaying(): array {
		return [
			'No log relaying' => [
				'userGroups' => [ 'sysop' ],
				'relayLog' => [],
				'expectedWikis' => [],
			],
			'Sysop is relayed to otherwiki' => [
				'userGroups' => [ 'sysop' ],
				'relayLog' => [ 'sysop=otherwiki' ],
				'expectedWikis' => [ 'otherwiki' ],
			],
			'Bureaucrat is relayed to otherwiki, but user is only a sysop' => [
				'userGroups' => [ 'sysop' ],
				'relayLog' => [ 'bureaucrat=otherwiki' ],
				'expectedWikis' => [],
			],
			'Sysop is relayed to otherwiki and anotherwiki' => [
				'userGroups' => [ 'sysop' ],
				'relayLog' => [ 'sysop=otherwiki', 'sysop=anotherwiki' ],
				'expectedWikis' => [ 'otherwiki', 'anotherwiki' ],
			],
			'Sysop is relayed to otherwiki and bureaucrat to anotherwiki' => [
				'userGroups' => [ 'sysop', 'bureaucrat' ],
				'relayLog' => [ 'sysop=otherwiki', 'bureaucrat=anotherwiki' ],
				'expectedWikis' => [ 'otherwiki', 'anotherwiki' ],
			],
			'Sysop is relayed to otherwiki and bureaucrat to anotherwiki, but user is not a \'crat' => [
				'userGroups' => [ 'sysop' ],
				'relayLog' => [ 'sysop=otherwiki', 'bureaucrat=anotherwiki' ],
				'expectedWikis' => [ 'otherwiki' ],
			],
			'Bad format of options has no impact on result' => [
				'userGroups' => [ 'sysop', 'bureaucrat' ],
				'relayLog' => [ 'sysop=otherwiki=test', 'bureaucrat' ],
				'expectedWikis' => [],
			],
		];
	}
}
