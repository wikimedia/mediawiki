<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserGroupAssignmentService;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserGroupMembership;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\UserGroupAssignmentService
 * @group Database
 */
class UserGroupAssignmentServiceTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	/** @dataProvider provideTargetCanHaveGroups */
	public function testTargetCanHaveGroups( UserIdentity $target, bool $expected ): void {
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();
		$this->assertEquals( $expected, $service->targetCanHaveUserGroups( $target ) );
	}

	public static function provideTargetCanHaveGroups(): array {
		return [
			'Registered user' => [ new UserIdentityValue( 1, 'Test user' ), true ],
			'Remote registered user' => [ new UserIdentityValue( 1, 'Test user', 'otherwiki' ), true ],
			'Anonymous user' => [ new UserIdentityValue( 0, '127.0.0.1' ), false ],
			'Remote anonymous user' => [ new UserIdentityValue( 0, '127.0.0.1', 'otherwiki' ), false ],
			'Temporary user' => [ new UserIdentityValue( 2, '~2025-1' ), false ],
			'Remote temporary user' => [ new UserIdentityValue( 2, '~2025-1', 'otherwiki' ), false ],
		];
	}

	/** @dataProvider provideTargetCanHaveGroups_tempAccountsNotKnown */
	public function testTargetCanHaveGroups_tempAccountsNotKnown( UserIdentity $target, bool $expected ): void {
		$this->disableAutoCreateTempUser();
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();
		$this->assertEquals( $expected, $service->targetCanHaveUserGroups( $target ) );
	}

	public static function provideTargetCanHaveGroups_tempAccountsNotKnown(): array {
		return [
			'Anonymous user' => [ new UserIdentityValue( 0, '127.0.0.1' ), false ],
			'Remote anonymous user' => [ new UserIdentityValue( 0, '127.0.0.1', 'otherwiki' ), false ],
			'Temporary (unknown) user' => [ new UserIdentityValue( 2, '~2025-1' ), true ],
			'Remote temporary user' => [ new UserIdentityValue( 2, '~2025-1', 'otherwiki' ), false ],
		];
	}

	/** @dataProvider provideUserCanChangeRights */
	public function testUserCanChangeRights( UserIdentity $target, bool $hasInterwikiRight, bool $expected ): void {
		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [
				'add' => [ 'add-group' ],
				'remove' => [],
				'add-self' => [],
				'remove-self' => [],
			] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$performer = $this->mockRegisteredAuthorityWithPermissions(
			$hasInterwikiRight ? [ 'userrights-interwiki' ] : []
		);
		$canChange = $service->userCanChangeRights( $performer, $target );
		$this->assertSame( $expected, $canChange );
	}

	public static function provideUserCanChangeRights(): array {
		return [
			'Registered target' => [
				'target' => new UserIdentityValue( 1, 'Test user' ),
				'hasInterwikiRight' => false,
				'expected' => true,
			],
			'Anonymous target' => [
				'target' => new UserIdentityValue( 0, '127.0.0.1' ),
				'hasInterwikiRight' => false,
				'expected' => false,
			],
			'Temporary user target' => [
				'target' => new UserIdentityValue( 2, '~2025-1' ),
				'hasInterwikiRight' => false,
				'expected' => false,
			],
			'Registered remote target, no interwiki right' => [
				'target' => new UserIdentityValue( 1, 'Test user', 'otherwiki' ),
				'hasInterwikiRight' => false,
				'expected' => false,
			],
			'Registered remote target, with interwiki right' => [
				'target' => new UserIdentityValue( 1, 'Test user', 'otherwiki' ),
				'hasInterwikiRight' => true,
				'expected' => true,
			],
		];
	}

	/** @dataProvider provideIsSelf */
	public function testUserCanChangeRightsOnlySelf( bool $isSelf ): void {
		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [
				'add' => [],
				'remove' => [],
				'add-self' => [ 'add-group' ],
				'remove-self' => [],
			] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$performer = $this->mockRegisteredNullAuthority();
		$target = $isSelf ? $performer->getUser() : new UserIdentityValue( 1, 'Test user' );
		$canChange = $service->userCanChangeRights( $performer, $target );
		$this->assertSame( $isSelf, $canChange );
	}

	/** @dataProvider provideIsSelf */
	public function testGetChangeableGroups( bool $isSelf ): void {
		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [
				'add' => [ 'add-group1' ],
				'remove' => [ 'remove-group1' ],
				'add-self' => [ 'add-group2' ],
				'remove-self' => [ 'remove-group2' ],
			] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$performer = $this->mockAnonNullAuthority();
		$target = $isSelf ? $performer->getUser() : new UserIdentityValue( 1, 'Test user' );

		$groups = $service->getChangeableGroups( $performer, $target );
		if ( $isSelf ) {
			$this->assertSame( [
				'add' => [ 'add-group1', 'add-group2' ],
				'remove' => [ 'remove-group1', 'remove-group2' ],
				'restricted' => [],
			], $groups );
		} else {
			$this->assertSame( [
				'add' => [ 'add-group1' ],
				'remove' => [ 'remove-group1' ],
				'restricted' => [],
			], $groups );
		}
	}

	public static function provideIsSelf(): array {
		return [
			'Not self' => [ false ],
			'Self' => [ true ],
		];
	}

	/** @dataProvider provideGetChangeableGroupsWithRestrictions */
	public function testGetChangeableGroupsWithRestrictions(
		array $restrictedGroups,
		bool $canIgnore,
		array $expected
	): void {
		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [
				'add' => array_keys( $restrictedGroups ),
				'remove' => array_keys( $restrictedGroups ),
				'add-self' => [],
				'remove-self' => [],
			] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );

		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, $restrictedGroups );

		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$performer = $canIgnore ?
			$this->mockRegisteredUltimateAuthority() :
			$this->mockAnonNullAuthority();
		$target = $performer->getUser();

		$groups = $service->getChangeableGroups( $performer, $target );
		$this->assertSame( $expected, $groups );
	}

	public static function provideGetChangeableGroupsWithRestrictions() {
		$passingConditions = [
			'|',
			[ APCOND_EDITCOUNT, 0 ],
			[ APCOND_AGE, 3 * 86400 * 30 ],
		];
		$failingConditions = [
			'&',
			[ APCOND_EDITCOUNT, 300 ],
			[ APCOND_AGE, 3 * 86400 * 30 ],
		];

		return [
			'Conditions not met, cannot be ignored' => [
				'restrictedGroups' => [
					'group1' => [
						'memberConditions' => $failingConditions,
						'canBeIgnored' => false,
					],
					'group2' => [
						'updaterConditions' => $failingConditions,
						'canBeIgnored' => false,
					],
				],
				'canIgnore' => true,
				'expected' => [
					'add' => [],
					'remove' => [ 'group1', 'group2' ],
					'restricted' => [
						'group1' => [
							'condition-met' => false,
							'ignore-condition' => false,
						],
						'group2' => [
							'condition-met' => false,
							'ignore-condition' => false,
						],
					],
				],
			],
			'Conditions not met, can be ignored' => [
				'restrictedGroups' => [
					'group1' => [
						'memberConditions' => $failingConditions,
						'updaterConditions' => $failingConditions,
						'canBeIgnored' => true,
					],
				],
				'canIgnore' => true,
				'expected' => [
					'add' => [ 'group1' ],
					'remove' => [ 'group1' ],
					'restricted' => [
						'group1' => [
							'condition-met' => false,
							'ignore-condition' => true,
						],
					],
				],
			],
			'Conditions met' => [
				'restrictedGroups' => [
					'group1' => [
						'memberConditions' => $passingConditions,
						'canBeIgnored' => false,
					],
					'group2' => [
						'updaterConditions' => $passingConditions,
						'canBeIgnored' => false,
					],
				],
				'canIgnore' => false,
				'expected' => [
					'add' => [ 'group1', 'group2' ],
					'remove' => [ 'group1', 'group2' ],
					'restricted' => [
						'group1' => [
							'condition-met' => true,
							'ignore-condition' => false,
						],
						'group2' => [
							'condition-met' => true,
							'ignore-condition' => false,
						]
					],
				],
			],
			'No restricted groups' => [
				'restrictedGroups' => [],
				'canIgnore' => false,
				'expected' => [
					'add' => [],
					'remove' => [],
					'restricted' => [],
				],
			],
		];
	}

	/** @dataProvider provideGetPageTitleForTargetUser */
	public function testGetPageTitleForTargetUser( UserIdentity $target, string $expected ): void {
		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$title = $service->getPageTitleForTargetUser( $target );
		$this->assertSame( $expected, $title );
	}

	public static function provideGetPageTitleForTargetUser(): array {
		return [
			'Local user' => [ new UserIdentityValue( 1, 'LocalUser' ), 'LocalUser' ],
			'Remote user' => [ new UserIdentityValue( 2, 'RemoteUser', 'otherwiki' ), 'RemoteUser@otherwiki' ],
		];
	}

	/** @dataProvider provideEnforcePermissions */
	public function testEnforcePermissions(
		array &$add,
		array &$remove,
		array &$newExpiries,
		array $existingUGMs,
		array $permittedChanges,
		array $expectedAdd,
		array $expectedRemove,
		array $expectedNewExpiries
	): void {
		foreach ( $existingUGMs as $group => $expiry ) {
			$existingUGMs[$group] = new UserGroupMembership( 1, $group, $expiry );
		}

		UserGroupAssignmentService::enforceChangeGroupPermissions(
			$add,
			$remove,
			$newExpiries,
			$existingUGMs,
			$permittedChanges
		);

		$this->assertSame( $expectedAdd, $add );
		$this->assertSame( $expectedRemove, $remove );
		$this->assertSame( $expectedNewExpiries, $newExpiries );
	}

	public static function provideEnforcePermissions(): array {
		return [
			'All changes are permitted' => [
				'add' => [ 'added-group1', 'added-group2', 'prolonged-group', 'shortened-group' ],
				'remove' => [ 'removed-group1', 'removed-group2' ],
				'newExpiries' => [
					'added-group1' => null,
					'added-group2' => '20290101000000',
					'prolonged-group' => '20300101000000',
					'shortened-group' => '20250101000000',
				],
				'existingUGMs' => [
					'prolonged-group' => '20280101000000',
					'shortened-group' => '20270101000000',
					'removed-group1' => null,
					'removed-group2' => '20260101000000',
				],
				'permittedChanges' => [
					'add' => [ 'added-group1', 'added-group2', 'prolonged-group' ],
					'remove' => [ 'removed-group1', 'removed-group2', 'shortened-group' ],
				],
				'expectedAdd' => [ 'added-group1', 'added-group2', 'prolonged-group', 'shortened-group' ],
				'expectedRemove' => [ 'removed-group1', 'removed-group2' ],
				'expectedNewExpiries' => [
					'added-group1' => null,
					'added-group2' => '20290101000000',
					'prolonged-group' => '20300101000000',
					'shortened-group' => '20250101000000',
				],
			],
			'No changes permitted' => [
				'add' => [ 'added-group1', 'added-group2', 'prolonged-group', 'shortened-group' ],
				'remove' => [ 'removed-group1', 'removed-group2' ],
				'newExpiries' => [
					'added-group1' => null,
					'added-group2' => '20290101000000',
					'prolonged-group' => '20300101000000',
					'shortened-group' => '20250101000000',
				],
				'existingUGMs' => [
					'prolonged-group' => '20280101000000',
					'shortened-group' => '20270101000000',
					'removed-group1' => null,
					'removed-group2' => '20260101000000',
				],
				'permittedChanges' => [
					'add' => [],
					'remove' => [],
				],
				'expectedAdd' => [],
				'expectedRemove' => [],
				'expectedNewExpiries' => [],
			],
			'Shortening fails without remove right' => [
				'add' => [ 'shortened-group1', 'shortened-group2' ],
				'remove' => [],
				'newExpiries' => [
					'shortened-group1' => '20250101000000',
					'shortened-group2' => '20260101000000',
				],
				'existingUGMs' => [
					'shortened-group1' => '20270101000000',
					'shortened-group2' => null,
				],
				'permittedChanges' => [
					'add' => [ 'shortened-group1', 'shortened-group2' ],
					'remove' => [],
				],
				'expectedAdd' => [],
				'expectedRemove' => [],
				'expectedNewExpiries' => [],
			],
			'Shortening succeeds with just remove right' => [
				'add' => [ 'shortened-group1', 'shortened-group2' ],
				'remove' => [],
				'newExpiries' => [
					'shortened-group1' => '20250101000000',
					'shortened-group2' => '20260101000000',
				],
				'existingUGMs' => [
					'shortened-group1' => '20270101000000',
					'shortened-group2' => null,
				],
				'permittedChanges' => [
					'add' => [],
					'remove' => [ 'shortened-group1', 'shortened-group2' ],
				],
				'expectedAdd' => [ 'shortened-group1', 'shortened-group2' ],
				'expectedRemove' => [],
				'expectedNewExpiries' => [
					'shortened-group1' => '20250101000000',
					'shortened-group2' => '20260101000000',
				],
			],
			'User wants to remove non-existing group' => [
				'add' => [],
				'remove' => [ 'non-existing-group' ],
				'newExpiries' => [],
				'existingUGMs' => [],
				'permittedChanges' => [
					'add' => [],
					'remove' => [ 'non-existing-group' ],
				],
				'expectedAdd' => [],
				'expectedRemove' => [],
				'expectedNewExpiries' => [],
			],
			'User wants to both add and remove the same group' => [
				'add' => [ 'group1', 'group2' ],
				'remove' => [ 'group1', 'group2' ],
				'newExpiries' => [
					'group1' => null,
					'group2' => '20290101000000',
				],
				'existingUGMs' => [
					'group2' => null,
				],
				'permittedChanges' => [
					'add' => [ 'group1', 'group2' ],
					'remove' => [ 'group1', 'group2' ],
				],
				'expectedAdd' => [],
				'expectedRemove' => [],
				'expectedNewExpiries' => [],
			],
		];
	}

	public function testCannotAddSelfToRestrictedGroup() {
		$ugmMock = $this->createMock( UserGroupManager::class );
		$ugmMock->method( 'getGroupsChangeableBy' )
			->willReturn( [
				'add' => [],
				'remove' => [],
				'add-self' => [ 'interface-admin' ],
				'remove-self' => [],
			] );

		$ugmFactoryMock = $this->createMock( UserGroupManagerFactory::class );
		$ugmFactoryMock->method( 'getUserGroupManager' )
			->willReturn( $ugmMock );

		$this->setService( 'UserGroupManagerFactory', $ugmFactoryMock );

		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'interface-admin' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 100 ],
			],
		] );

		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$performer = $this->mockAnonNullAuthority();
		$target = $performer->getUser();

		$groups = $service->getChangeableGroups( $performer, $target );
		$expected = [
			'add' => [],
			'remove' => [],
			'restricted' => [
				'interface-admin' => [
					'condition-met' => false,
					'ignore-condition' => false
				]
			],
		];
		$this->assertSame( $expected, $groups );
	}

	public function testSaveUserGroups(): void {
		$add = [ 'added-group1', 'added-group2' ];
		$remove = [ 'removed-group' ];
		$newExpiries = [
			'added-group1' => null,
			'added-group2' => '20290101000000',
		];

		// Using bureaucrat as a performer won't work because these groups aren't otherwise
		// known to software and won't be allowed to be assigned.
		$this->overrideConfigValue( MainConfigNames::AddGroups,
			[ 'sysop' => [ 'added-group1', 'added-group2', 'hook-added-group' ] ] );
		$this->overrideConfigValue( MainConfigNames::RemoveGroups,
			[ 'sysop' => [ 'removed-group', 'hook-removed-group' ] ] );

		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$target = $this->getTestUser( [ 'static-group', 'removed-group', 'hook-removed-group' ] )->getUser();

		$hookBeforeCalled = false;
		$hookAfterCalled = false;

		$this->setTemporaryHook(
			'ChangeUserGroups',
			function (
				$performer, $hookUser, array &$addGroups, array &$removeGroups
			) use ( &$hookBeforeCalled ) {
				$this->assertSame( [ 'added-group1', 'added-group2' ], $addGroups );
				$this->assertSame( [ 'removed-group' ], $removeGroups );
				$hookBeforeCalled = true;
				$addGroups[] = 'hook-added-group';
				$removeGroups[] = 'hook-removed-group';
			}
		);
		$this->setTemporaryHook(
			'UserGroupsChanged',
			function (
				$hookUser, array $addGroups, array $removeGroups, $performer, $reason, $oldUGMs, $newUGMs
			) use ( &$hookAfterCalled ) {
				$this->assertSame( [ 'added-group1', 'added-group2', 'hook-added-group' ], $addGroups );
				$this->assertSame( [ 'removed-group', 'hook-removed-group' ], $removeGroups );
				$this->assertSame(
					[ 'static-group', 'added-group1', 'added-group2', 'hook-added-group' ],
					array_keys( $newUGMs )
				);
				$hookAfterCalled = true;
			}
		);

		$service = $this->getServiceContainer()->getUserGroupAssignmentService();
		[ $added, $removed ] = $service->saveChangesToUserGroups( $performer, $target, $add, $remove, $newExpiries );

		$this->assertEquals( [ 'added-group1', 'added-group2', 'hook-added-group' ], $added );
		$this->assertEquals( [ 'removed-group', 'hook-removed-group' ], $removed );
		$this->assertTrue( $hookBeforeCalled, 'ChangeUserGroups hook was not called' );
		$this->assertTrue( $hookAfterCalled, 'UserGroupsChanged hook was not called' );
	}

	public function testPrivateRestrictedGroupConditions(): void {
		$this->overrideConfigValue( MainConfigNames::AddGroups, [ 'sysop' => [ 'test-group' ] ] );
		$this->overrideConfigValue( MainConfigNames::RemoveGroups, [] );

		// Only blocked users can be added to 'test-group'
		$this->overrideConfigValue( MainConfigNames::UserRequirementsPrivateConditions, [ APCOND_BLOCKED ] );
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'test-group' => [
				'memberConditions' => APCOND_BLOCKED
			]
		] );

		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$target = $this->getTestUser()->getUser();

		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		// The two subsequent assertions ensure that the cache is partitioned by evaluatePrivateConditions
		$changeableGroups = $service->getChangeableGroups( $performer, $target, false );
		$this->assertSame( [ 'test-group' ], $changeableGroups['add'] );

		$changeableGroups = $service->getChangeableGroups( $performer, $target );
		$this->assertSame( [], $changeableGroups['add'] );

		// Make sure that attempt to add the user to 'test-group' fails
		[ $added, $removed ] = $service->saveChangesToUserGroups(
			$performer, $target, [ 'test-group' ], [], []
		);
		$this->assertSame( [], $added );
		$this->assertSame( [], $removed );
	}

	public function testValidateUserGroups() {
		$this->overrideConfigValue( MainConfigNames::AddGroups,
			[ 'sysop' => [
				'addable-group', 'restricted-group', 'restricted-group-private1', 'restricted-group-private2'
			] ] );
		$this->overrideConfigValue( MainConfigNames::RemoveGroups,
			[ 'sysop' => [ 'removable-group' ] ] );

		// Groups are considered known only if they are given or revoked some permissions (empty array counts as well)
		$this->overrideConfigValue(
			MainConfigNames::RevokePermissions,
			[
				'addable-group' => [],
				'restricted-group' => [],
				'restricted-group-private1' => [],
				'restricted-group-private2' => [],
				'removable-group' => [],
				'insufficient-rights-group' => [],
			]
		);

		$this->overrideConfigValue( MainConfigNames::UserRequirementsPrivateConditions, [ APCOND_BLOCKED ] );
		$this->overrideConfigValue( MainConfigNames::RestrictedGroups, [
			'restricted-group' => [
				'memberConditions' => [ APCOND_EDITCOUNT, 100 ]
			],
			'restricted-group-private1' => [
				'memberConditions' => APCOND_BLOCKED
			],
			// Even though it uses a private condition, the target doesn't meet APCOND_EDITCOUNT, so it fails due to
			// 'restricted', as it can be publicly determined that the user is not eligible for the group
			'restricted-group-private2' => [
				'memberConditions' => [ '&', APCOND_BLOCKED, [ APCOND_EDITCOUNT, 100 ] ]
			]
		] );

		$service = $this->getServiceContainer()->getUserGroupAssignmentService();

		$currentTargetGroups = [ 'unknown-group1', 'removable-group' ];
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$target = $this->getTestUser()->getUser();

		$currentTargetUGMs = [];
		foreach ( $currentTargetGroups as $group ) {
			$currentTargetUGMs[$group] = new UserGroupMembership( $target->getId(), $group );
		}

		$removeGroups = [ 'unknown-group1', 'removable-group' ];
		$addGroups = [ 'unknown-group2', 'addable-group', 'restricted-group', 'restricted-group-private1',
			'restricted-group-private2', 'insufficient-rights-group' ];
		$expected = [
			'insufficient-rights-group' => 'rights',
			'restricted-group' => 'restricted',
			'restricted-group-private1' => 'private-condition',
			'restricted-group-private2' => 'restricted',
		];

		$result = $service->validateUserGroups(
			$performer, $target, $addGroups, $removeGroups, [], $currentTargetUGMs );

		$this->assertArrayEquals( $expected, $result, named: true );
	}
}
