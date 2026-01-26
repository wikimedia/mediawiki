<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\RestrictedUserGroupChecker;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserRequirementsConditionChecker;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\User\RestrictedUserGroupChecker
 */
class RestrictedUserGroupCheckerTest extends MediaWikiUnitTestCase {

	use MockAuthorityTrait;

	// A dummy condition for testing purposes. Its actual value is irrelevant.
	private const CONDITION = 999;

	private function createOptions( array $restrictedGroups ): ServiceOptions {
		return new ServiceOptions(
			RestrictedUserGroupChecker::CONSTRUCTOR_OPTIONS,
			[
				MainConfigNames::RestrictedGroups => $restrictedGroups,
			]
		);
	}

	/** @dataProvider provideIsGroupRestricted */
	public function testIsGroupRestricted( array $restrictedGroups, string $groupName, bool $expected ) {
		$options = $this->createOptions( $restrictedGroups );
		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );
		$this->assertSame( $expected, $groupChecker->isGroupRestricted( $groupName ) );
	}

	public static function provideIsGroupRestricted(): array {
		return [
			'Empty restricted groups' => [
				'restrictedGroups' => [],
				'groupName' => 'sysop',
				'expected' => false,
			],
			'Group with empty restrictions is considered restricted' => [
				'restrictedGroups' => [ 'sysop' => [] ],
				'groupName' => 'sysop',
				'expected' => true,
			],
			'Group is restricted' => [
				'restrictedGroups' => [ 'sysop' => [ 'memberConditions' => [ self::CONDITION ] ] ],
				'groupName' => 'sysop',
				'expected' => true,
			],
			'Group is not restricted, but others are' => [
				'restrictedGroups' => [ 'sysop' => [] ],
				'groupName' => 'bureaucrat',
				'expected' => false,
			],
		];
	}

	/** @dataProvider provideConditionCheckerIsCalledOnlyForNonEmptyArrays */
	public function testConditionCheckerIsCalledOnlyForNonEmptyArrays(
		array $updaterConditions,
		array $memberConditions,
		int $expectedCalls
	) {
		$restrictions = [
			'sysop' => [
				'updaterConditions' => $updaterConditions,
				'memberConditions' => $memberConditions,
				'canBeIgnored' => false,
			],
		];
		$options = $this->createOptions( $restrictions );

		$performer = $this->mockAnonNullAuthority();
		$target = UserIdentityValue::newAnonymous( '127.0.0.2' );

		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->expects( $this->exactly( $expectedCalls ) )
			->method( 'recursivelyCheckCondition' )
			->willReturnCallback( function ( $cond, $user ) use ( $target, $updaterConditions, $memberConditions ) {
				$this->assertNotSame( [], $cond );
				if ( $target->equals( $user ) ) {
					$this->assertSame( $memberConditions, $cond, 'Member conditions do not match' );
				} else {
					$this->assertSame( $updaterConditions, $cond, 'Updater conditions do not match' );
				}
				return true;
			} );

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );
		$result = $groupChecker->canPerformerAddTargetToGroup( $performer, $target, 'sysop' );
		$this->assertTrue( $result );
	}

	public static function provideConditionCheckerIsCalledOnlyForNonEmptyArrays(): array {
		return [
			'Both conditions empty' => [
				'updaterConditions' => [],
				'memberConditions' => [],
				'expectedCalls' => 0,
			],
			'Only updater conditions non-empty' => [
				'updaterConditions' => [ self::CONDITION ],
				'memberConditions' => [],
				'expectedCalls' => 1,
			],
			'Only member conditions non-empty' => [
				'updaterConditions' => [],
				'memberConditions' => [ self::CONDITION ],
				'expectedCalls' => 1,
			],
			'Both conditions non-empty' => [
				// Different arguments to distinguish calls
				'updaterConditions' => [ self::CONDITION, 'arg1' ],
				'memberConditions' => [ self::CONDITION, 'arg2' ],
				'expectedCalls' => 2,
			],
		];
	}

	/** @dataProvider providePerformerCanAddTargetToGroup */
	public function testPerformerCanAddTargetToGroup(
		bool $groupRestricted,
		bool $ignorable,
		bool $performerMeets,
		bool $targetMeets,
		array $performerRights,
		bool $expected
	) {
		$restrictions = [];
		if ( $groupRestricted ) {
			$restrictions = [
				'sysop' => [
					'updaterConditions' => [ self::CONDITION ],
					'memberConditions' => [ self::CONDITION ],
					'canBeIgnored' => $ignorable,
				],
			];
		}
		$options = $this->createOptions( $restrictions );

		$performer = $this->mockAnonAuthorityWithPermissions( $performerRights );
		$target = UserIdentityValue::newAnonymous( '127.0.0.2' );

		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->method( 'recursivelyCheckCondition' )
			->willReturnCallback(
				static fn ( $cond, $user ) => $target->equals( $user ) ? $targetMeets : $performerMeets
			);

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );

		$result = $groupChecker->canPerformerAddTargetToGroup( $performer, $target, 'sysop' );
		$this->assertSame( $expected, $result );
	}

	public static function providePerformerCanAddTargetToGroup(): array {
		return [
			'Unrestricted group' => [
				'groupRestricted' => false,
				'ignorable' => false,
				'performerMeets' => false,
				'targetMeets' => false,
				'performerRights' => [],
				'expected' => true,
			],
			'Restricted group: performer meets conditions, target meets conditions' => [
				'groupRestricted' => true,
				'ignorable' => false,
				'performerMeets' => true,
				'targetMeets' => true,
				'performerRights' => [],
				'expected' => true,
			],
			'Restricted group: performer meets conditions, target does not meet conditions' => [
				'groupRestricted' => true,
				'ignorable' => false,
				'performerMeets' => true,
				'targetMeets' => false,
				'performerRights' => [],
				'expected' => false,
			],
			'Restricted group: performer does not meet conditions, target meets conditions' => [
				'groupRestricted' => true,
				'ignorable' => false,
				'performerMeets' => false,
				'targetMeets' => true,
				'performerRights' => [],
				'expected' => false,
			],
			'Restricted group: performer does not meet conditions, target does not meet conditions' => [
				'groupRestricted' => true,
				'ignorable' => false,
				'performerMeets' => false,
				'targetMeets' => false,
				'performerRights' => [],
				'expected' => false,
			],
			'Restricted ignorable group: performer has ignore right' => [
				'groupRestricted' => true,
				'ignorable' => true,
				'performerMeets' => false,
				'targetMeets' => false,
				'performerRights' => [ 'ignore-restricted-groups' ],
				'expected' => true,
			],
			'Restricted ignorable group: performer lacks ignore right' => [
				'groupRestricted' => true,
				'ignorable' => true,
				'performerMeets' => false,
				'targetMeets' => false,
				'performerRights' => [],
				'expected' => false,
			],
		];
	}

	/** @dataProvider provideGetPrivateConditionsForGroup */
	public function testGetPrivateConditionsForGroup( string $group, array $expected ): void {
		$restrictions = [
			'interface-admin' => [
				'memberConditions' => [ 'cond1' ]
			],
			'suppress' => [
				'memberConditions' => [ 'cond1' ],
				'updaterConditions' => [ 'cond2' ],
			],
			'bureaucrat' => [
				'updaterConditions' => [ 'cond2' ],
			]
		];
		$options = $this->createOptions( $restrictions );

		// This mock assumes all conditions are private and returns them as provided in the input
		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->method( 'extractPrivateConditions' )
			->willReturnCallback( static fn ( $cond ) => $cond );

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );

		$result = $groupChecker->getPrivateConditionsForGroup( $group );
		$this->assertSame( $expected, $result );
	}

	public static function provideGetPrivateConditionsForGroup(): array {
		// Updater restrictions are not considered private, so they are absent from the expected state
		return [
			'Unrestricted group' => [
				'group' => 'sysop',
				'expected' => [],
			],
			'Group with only member restrictions' => [
				'group' => 'interface-admin',
				'expected' => [ 'cond1' ],
			],
			'Group with member and updater restrictions' => [
				'group' => 'suppress',
				'expected' => [ 'cond1' ],
			],
			'Group with only updater restrictions' => [
				'group' => 'bureaucrat',
				'expected' => [],
			],
		];
	}
}
