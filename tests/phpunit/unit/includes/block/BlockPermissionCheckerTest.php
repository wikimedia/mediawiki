<?php

use MediaWiki\Block\BlockPermissionChecker;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Blocking
 * @covers \MediaWiki\Block\BlockPermissionChecker
 * @author DannyS712
 */
class BlockPermissionCheckerTest extends MediaWikiUnitTestCase {
	use MockAuthorityTrait;

	/**
	 * @param bool $enableUserEmail
	 * @param Authority $performer
	 * @return BlockPermissionChecker
	 */
	private function getBlockPermissionChecker(
		bool $enableUserEmail,
		Authority $performer
	) {
		$options = new ServiceOptions(
			BlockPermissionChecker::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::EnableUserEmail => $enableUserEmail ]
		);

		$blockTargetFactory = $this->createNoOpMock(
			BlockTargetFactory::class,
			[ 'newFromLegacyUnion' ]
		);
		$blockTargetFactory->expects( $this->any() )
			->method( 'newFromLegacyUnion' )
			->willReturnCallback( static function ( $target ) {
				return new UserBlockTarget( $target );
			} );
		return new BlockPermissionChecker(
			$options,
			$blockTargetFactory,
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

	/**
	 * @param int $id
	 * @param string $name
	 * @return UserIdentityValue
	 */
	private function getTarget( $id = 1, $name = 'Blocked' ) {
		return new UserIdentityValue( $id, $name );
	}

	public static function provideCheckBasePermissions() {
		// $rights, $checkHideuser, $expect
		yield 'need block' => [ [], false, 'badaccess-group0' ];
		yield 'block enough for not hiding' => [ [ 'block' ], false, true ];
		yield 'need hideuser' => [ [ 'block' ], true, 'unblock-hideuser' ];
		yield 'can hideuser' => [ [ 'block', 'hideuser' ], true, true ];
	}

	/**
	 * @dataProvider provideCheckBasePermissions
	 */
	public function testCheckBasePermissions( $rights, $checkHideuser, $expect ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $rights );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			$performer
		);
		$this->assertSame(
			$expect,
			$blockPermissionChecker->checkBasePermissions( $checkHideuser )
		);
	}

	public function testNotBlockedPerformer() {
		// checkBlockPermissions has an early return true if the performer has no block
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			$this->mockRegisteredAuthorityWithoutPermissions( [] )
		);
		$this->assertTrue(
			$blockPermissionChecker->checkBlockPermissions( $this->getTarget() )
		);
	}

	public function testPartialBlockedPerformer() {
		// checkBlockPermissions has an early return true if the performer is not sitewide blocked
		$blocker = new UserIdentityValue( 1, 'blocker', UserIdentity::LOCAL );
		$performer = $this->mockUserAuthorityWithBlock( $blocker, $this->getBlock( false, '' ) );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			$performer
		);
		$this->assertTrue(
			$blockPermissionChecker->checkBlockPermissions( $this->getTarget() )
		);
	}

	public static function provideCheckBlockPermissions() {
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

		$target = $this->getTarget( $targetUserId, $targetUserName );

		$blockPermissionChecker = $this->getBlockPermissionChecker(
			true, // $enableUserEmail, irrelevant
			$performer
		);
		$this->assertSame(
			$expect,
			$blockPermissionChecker->checkBlockPermissions( $target )
		);
	}

	public static function provideCheckEmailPermissions() {
		// $enableEmail, $rights, $expect
		yield 'Email not enabled, without permissions' => [ false, [], false ];
		yield 'Email not enabled, with permissions' => [ false, [ 'blockemail' ], false ];
		yield 'Email enabled, without permissions' => [ true, [], false ];
		yield 'Email enabled, with permissions' => [ true, [ 'blockemail' ], true ];
	}

	/**
	 * @dataProvider provideCheckEmailPermissions
	 */
	public function testCheckEmailPermissionOkay( $enableEmail, $rights, $expect ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $rights );
		$blockPermissionChecker = $this->getBlockPermissionChecker(
			$enableEmail,
			$performer
		);
		$this->assertSame( $expect, $blockPermissionChecker->checkEmailPermissions() );
	}
}
