<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockPermissionChecker;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\BlockPermissionChecker
 * @author DannyS712
 */
class BlockPermissionCheckerTest extends MediaWikiUnitTestCase {
	use MockAuthorityTrait;

	/**
	 * @param bool $enableUserEmail
	 * @param array $targetAndType
	 * @param UserIdentity|MockObject $user
	 * @param string[] $rights
	 * @return BlockPermissionChecker
	 */
	private function getBlockPermissionChecker(
		bool $enableUserEmail,
		array $targetAndType,
		$user,
		array $rights
	) {
		$options = new ServiceOptions(
			BlockPermissionChecker::CONSTRUCTOR_OPTIONS,
			[ 'EnableUserEmail' => $enableUserEmail ]
		);

		// Make things simple: in the test cases when the UserFactory is needed,
		// we expect to have the mock authority objects be based on mocks of full
		// User objects instead of just UserIdentity, so performer->getUser() is a
		// full user object, and have the factory just return that object
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromUserIdentity' )
			->with( $this->isInstanceOf( User::class ) )
			->will( $this->returnArgument( 0 ) );

		// We don't care about how BlockUtils::parseBlockTarget actually works, just
		// override with whatever. Only used for a single call in the constructor
		// for getting target and type
		$blockUtils = $this->createNoOpMock( BlockUtils::class, [ 'parseBlockTarget' ] );
		$blockUtils->expects( $this->once() )
			->method( 'parseBlockTarget' )
			->willReturn( $targetAndType );

		$performer = $this->mockUserAuthorityWithPermissions( $user, $rights );

		return new BlockPermissionChecker(
			$options,
			$blockUtils,
			$userFactory,
			'foo', // input to BlockUtils::parseBlockTarget, not used
			$performer
		);
	}

	/**
	 * @param bool $isSitewide
	 * @param string $byName
	 * @return DatabaseBlock|MockObject
	 */
	private function getBlock( bool $isSitewide, string $byName ) {
		// Mock DatabaseBlock instead of AbstractBlock because its easier
		$block = $this->createNoOpMock(
			DatabaseBlock::class,
			[ 'isSitewide', 'getByName' ]
		);
		$block->method( 'isSitewide' )->willReturn( $isSitewide );
		$block->method( 'getByName' )->willReturn( $byName );
		return $block;
	}

	public function provideCheckBasePermissions() {
		// $rights, $checkHideuser, $expect
		yield 'need block' => [ [], false, 'badaccess-group0' ];
		yield 'block enough for not hiding' => [ [ 'block' ], false, true ];
		yield 'need hideuser' => [ [ 'block' ], true, 'unblock-hideuser' ];
		yield 'can hideuser' => [ [ 'block', 'hideuser' ], true, true ];
	}

	/**
	 * @covers ::checkBasePermissions
	 * @dataProvider provideCheckBasePermissions
	 */
	public function testCheckBasePermissions( $rights, $checkHideuser, $expect ) {
		$user = new UserIdentityValue( 4, 'admin' );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$user,
			$rights
		);
		$this->assertSame(
			$expect,
			$blockPermissionChecker->checkBasePermissions( $checkHideuser )
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testNotBlockedPerformer() {
		// checkBlockPermissions has an early return true if the performer has no block
		$user = $this->createMock( User::class );
		$user->method( 'getBlock' )->willReturn( null );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$user,
			[] // $rights, irrelevant
		);
		$this->assertTrue(
			$blockPermissionChecker->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testPartialBlockedPerformer() {
		// checkBlockPermissions has an early return true if the performer is not sitewide blocked
		$user = $this->createMock( User::class );
		$user->method( 'getBlock' )->willReturn(
			$this->getBlock( false, '' )
		);

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$user,
			[] // $rights, irrelevant
		);
		$this->assertTrue(
			$blockPermissionChecker->checkBlockPermissions()
		);
	}

	public function provideCheckBlockPermissions() {
		// Blocked admin changing own block
		yield 'Self blocked' => [ 'blocker', 1, 'blocker', false, true ];
		yield 'unblockself' => [ 'another admin', 1, 'blocker', true, true ];
		yield 'ipbnounblockself' => [ 'another admin', 1, 'blocker', false, 'ipbnounblockself' ];

		// Blocked admins can always block the admin who blocked them
		yield 'Block my blocker' => [ 'another admin', 2, 'another admin', false, true ];

		// Blocked admin blocking a third party
		yield 'Block someone else' => [ 'another admin', 3, 'someone else', false, 'ipbblocked' ];
	}

	/**
	 * @covers ::checkBlockPermissions
	 * @dataProvider provideCheckBlockPermissions
	 */
	public function testCheckBlockPermissions(
		string $blockedBy,
		int $targetUserId,
		string $targetUserName,
		bool $unblockSelf,
		$expect
	) {
		// cases for the target being a UserIdentity
		// performing user has id 1 and the name 'blocker'
		$block = $this->getBlock(
			true, // sitewide
			$blockedBy
		);
		$user = $this->createMock( User::class );
		$user->method( 'getBlock' )->willReturn( $block );
		$user->method( 'getId' )->willReturn( 1 );
		$user->method( 'getName' )->willReturn( 'blocker' );

		$target = new UserIdentityValue( $targetUserId, $targetUserName );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ $target, AbstractBlock::TYPE_USER ], // $targetAndType, irrelevant
			$user,
			$unblockSelf ? [ 'unblockself' ] : []
		);
		$this->assertSame(
			$expect,
			$blockPermissionChecker->checkBlockPermissions()
		);
	}

	public function provideCheckEmailPermissions() {
		// $enableEmail, $rights, $expect
		yield 'Email not enabled, without permissions' => [ false, [], false ];
		yield 'Email not enabled, with permissions' => [ false, [ 'blockemail' ], false ];
		yield 'Email enabled, without permissions' => [ true, [], false ];
		yield 'Email enabled, with permissions' => [ true, [ 'blockemail' ], true ];
	}

	/**
	 * @covers ::checkEmailPermissions
	 * @dataProvider provideCheckEmailPermissions
	 */
	public function testCheckEmailPermissionOkay( $enableEmail, $rights, $expect ) {
		$user = new UserIdentityValue( 4, 'admin' );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			$enableEmail,
			[ null, null ], // $targetAndType, irrelevant
			$user,
			$rights
		);
		$this->assertSame( $expect, $blockPermissionChecker->checkEmailPermissions() );
	}
}
