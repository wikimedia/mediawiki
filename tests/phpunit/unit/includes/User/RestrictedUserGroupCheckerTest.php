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

		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->expects( $this->exactly( $expectedCalls ) )
			->method( 'recursivelyCheckCondition' )
			->willReturnCallback( function ( $cond, $_, $isPerforming ) use ( $updaterConditions, $memberConditions ) {
				$this->assertNotSame( [], $cond );
				if ( $isPerforming ) {
					$this->assertSame( $updaterConditions, $cond, 'Updater conditions do not match' );
				} else {
					$this->assertSame( $memberConditions, $cond, 'Member conditions do not match' );
				}
				return true;
			} );

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );

		$performer = $this->mockAnonNullAuthority();
		$target = UserIdentityValue::newAnonymous( '127.0.0.1' );

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
		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->method( 'recursivelyCheckCondition' )
			->willReturnCallback(
				static fn ( $cond, $user, $isPerformer ) => $isPerformer ? $performerMeets : $targetMeets
			);

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );

		$performer = $this->mockAnonAuthorityWithPermissions( $performerRights );
		$target = UserIdentityValue::newAnonymous( '127.0.0.1' );

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

	public function testPerformerCanRemoveTargetFromGroup() {
		// Test in a most restrictive scenario to ensure nothing is really checked for removing the group.
		$restrictions = [
			'sysop' => [
				'performerConditions' => [ self::CONDITION ],
			]
		];
		$options = $this->createOptions( $restrictions );

		$conditionCheckerMock = $this->createMock( UserRequirementsConditionChecker::class );
		$conditionCheckerMock->method( 'recursivelyCheckCondition' )
			->willReturn( false );

		$groupChecker = new RestrictedUserGroupChecker( $options, $conditionCheckerMock );

		$performer = $this->mockAnonAuthorityWithPermissions( [ 'ignore-restricted-groups' ] );
		$target = UserIdentityValue::newAnonymous( '127.0.0.1' );
		$result = $groupChecker->canPerformerRemoveTargetFromGroup( $performer, $target, 'sysop' );

		$this->assertTrue( $result );
	}
}
