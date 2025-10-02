<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Integration\Permissions;

use MediaWiki\Block\Block;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Language\RawMessage;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Permissions\PermissionStatus
 */
class PermissionStatusIntegrationTest extends MediaWikiIntegrationTestCase {

	private function makeBlockedStatus() {
		$user = new UserIdentityValue( 1, 'Primus' );
		$blocker = new UserIdentityValue( 2, 'Secundus' );

		$block = $this->createNoOpMock(
			Block::class,
			[
				'getTargetUserIdentity',
				'getBlocker',
				'getIdentifier',
				'getTargetName',
				'getReasonComment',
				'getExpiry',
				'getTimestamp',
			]
		);
		$block->method( 'getTargetUserIdentity' )->willReturn( $user );
		$block->method( 'getTargetName' )->willReturn( $user->getName() );
		$block->method( 'getBlocker' )->willReturn( $blocker );
		$block->method( 'getIdentifier' )->willReturn( 'TEST' );
		$block->method( 'getReasonComment' )->willReturn(
			CommentStoreComment::newUnsavedComment( 'testing' )
		);
		$block->method( 'getTimestamp' )->willReturn( '20220102334455' );
		$block->method( 'getExpiry' )->willReturn( '20250102334455' );

		$status = PermissionStatus::newEmpty();
		$status->fatal( 'blockednoreason' );
		$status->setBlock( $block );
		return $status;
	}

	private function assertThrowErrorPageError( PermissionStatus $status, $expected ) {
		$this->expectExceptionMessage( $expected->getMessage() );

		$status->throwErrorPageError();
	}

	public function testThrowErrorPageError_unknown() {
		$status = PermissionStatus::newEmpty();
		$status->fatal( 'permissionserrorstext-withaction', 'move' );
		$expected = new PermissionsError(
			null,
			[ [ 'permissionserrorstext-withaction', 'move' ] ]
		);

		$this->assertThrowErrorPageError( $status, $expected );
	}

	public function testThrowErrorPageError_known() {
		$status = PermissionStatus::newEmpty();
		$status->setPermission( 'move' );
		$status->fatal( 'permissionserrorstext-withaction', 'move' );
		$expected = new PermissionsError(
			'move',
			[ [ 'permissionserrorstext-withaction', 'move' ] ]
		);

		$this->assertThrowErrorPageError( $status, $expected );
	}

	public function testThrowErrorPageError_blocked() {
		$blockErrorFormatter = $this->createNoOpMock( BlockErrorFormatter::class, [ 'getMessages' ] );
		$blockErrorFormatter->method( 'getMessages' )->willReturn( [ new RawMessage( 'testing' ) ] );

		$formatterFactory = $this->createNoOpMock( FormatterFactory::class, [ 'getBlockErrorFormatter' ] );
		$formatterFactory->method( 'getBlockErrorFormatter' )->willReturn( $blockErrorFormatter );

		$this->setService( 'FormatterFactory', $formatterFactory );

		$status = $this->makeBlockedStatus();
		$block = $status->getBlock();
		$expected = new UserBlockedError( $block );

		$this->assertThrowErrorPageError( $status, $expected );
	}

	public function testThrowErrorPageError_throttled() {
		$status = PermissionStatus::newEmpty();
		$status->setRateLimitExceeded();
		$expected = new ThrottledError();

		$this->assertThrowErrorPageError( $status, $expected );
	}

}
