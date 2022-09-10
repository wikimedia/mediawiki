<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockPermissionChecker;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
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
	 * @param Authority $performer
	 * @return BlockPermissionChecker
	 */
	private function getBlockPermissionChecker(
		bool $enableUserEmail,
		array $targetAndType,
		Authority $performer
	) {
		$options = new ServiceOptions(
			BlockPermissionChecker::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::EnableUserEmail => $enableUserEmail ]
		);

		// We don't care about how BlockUtils::parseBlockTarget actually works, just
		// override with whatever. Only used for a single call in the constructor
		// for getting target and type
		$blockUtils = $this->createNoOpMock( BlockUtils::class, [ 'parseBlockTarget' ] );
		$blockUtils->expects( $this->once() )
			->method( 'parseBlockTarget' )
			->willReturn( $targetAndType );

		return new BlockPermissionChecker(
			$options,
			$blockUtils,
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
			[ 'isSitewide', 'getBlocker' ]
		);
		$block->method( 'isSitewide' )->willReturn( $isSitewide );
		$block->method( 'getBlocker' )->willReturn( new UserIdentityValue( 7, $byName ) );
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
		$performer = $this->mockRegisteredAuthorityWithPermissions( $rights );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$performer
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
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$this->mockRegisteredAuthorityWithoutPermissions( [] )
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
		$blocker = new UserIdentityValue( 1, 'blocker', UserIdentity::LOCAL );
		$performer = $this->mockUserAuthorityWithBlock( $blocker, $this->getBlock( false, '' ) );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ null, null ], // $targetAndType, irrelevant
			$performer
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
		$rights = $unblockSelf ? [ 'unblockself' ] : [];

		$blocker = new UserIdentityValue( 1, 'blocker', UserIdentity::LOCAL );
		$performer = $this->mockUserAuthorityWithBlock( $blocker, $block, $rights );

		$target = new UserIdentityValue( $targetUserId, $targetUserName );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			[ $target, AbstractBlock::TYPE_USER ], // $targetAndType, irrelevant
			$performer
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
		$performer = $this->mockRegisteredAuthorityWithPermissions( $rights );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			$enableEmail,
			[ null, null ], // $targetAndType, irrelevant
			$performer
		);
		$this->assertSame( $expect, $blockPermissionChecker->checkEmailPermissions() );
	}
}
