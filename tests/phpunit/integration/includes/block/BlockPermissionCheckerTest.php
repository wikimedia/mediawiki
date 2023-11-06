<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass \MediaWiki\Block\BlockPermissionChecker
 */
class BlockPermissionCheckerTest extends MediaWikiIntegrationTestCase {

	/**
	 * Moved from tests for old SpecialBlock::checkUnblockSelf
	 *
	 * @covers ::checkBlockPermissions
	 * @dataProvider provideCheckUnblockSelf
	 */
	public function testCheckUnblockSelf(
		$blockedUser,
		$blockPerformer,
		$adjustPerformer,
		$adjustTarget,
		$sitewide,
		$expectedResult,
		$reason
	) {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );
		$this->setGroupPermissions( 'sysop', 'unblockself', true );
		$this->setGroupPermissions( 'user', 'block', true );
		// Getting errors about creating users in db in provider.
		// Need to use mutable to ensure different named testusers.
		$users = [
			'u1' => $this->getMutableTestUser( 'sysop' )->getUser(),
			'u2' => $this->getMutableTestUser( 'sysop' )->getUser(),
			'u3' => $this->getMutableTestUser( 'sysop' )->getUser(),
			'u4' => $this->getMutableTestUser( 'sysop' )->getUser(),
			'nonsysop' => $this->getTestUser()->getUser()
		];
		foreach ( [ 'blockedUser', 'blockPerformer', 'adjustPerformer', 'adjustTarget' ] as $var ) {
			$$var = $users[$$var];
		}

		$block = new DatabaseBlock( [
			'address' => $blockedUser,
			'by' => $blockPerformer,
			'expiry' => 'infinity',
			'sitewide' => $sitewide,
			'enableAutoblock' => true,
		] );

		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$this->assertSame(
			$expectedResult,
			$this->getServiceContainer()
				->getBlockPermissionCheckerFactory()
				->newBlockPermissionChecker( $adjustTarget, $adjustPerformer )
				->checkBlockPermissions(),
			$reason
		);
	}

	public static function provideCheckUnblockSelf() {
		// 'blockedUser', 'blockPerformer', 'adjustPerformer', 'adjustTarget'
		return [
			[ 'u1', 'u2', 'u3', 'u4', 1, true, 'Unrelated users' ],
			[ 'u1', 'u2', 'u1', 'u4', 1, 'ipbblocked', 'Block unrelated while blocked' ],
			[ 'u1', 'u2', 'u1', 'u4', 0, true, 'Block unrelated while partial blocked' ],
			[ 'u1', 'u2', 'u1', 'u1', 1, true, 'Has unblockself' ],
			[ 'nonsysop', 'u2', 'nonsysop', 'nonsysop', 1, 'ipbnounblockself', 'no unblockself' ],
			[
				'nonsysop', 'nonsysop', 'nonsysop', 'nonsysop', 1, true,
				'no unblockself but can de-selfblock'
			],
			[ 'u1', 'u2', 'u1', 'u2', 1, true, 'Can block user who blocked' ],
		];
	}
}
