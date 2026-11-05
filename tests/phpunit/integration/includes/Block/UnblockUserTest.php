<?php

namespace MediaWiki\Tests\Integration\Block;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\UnblockUserFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\UnblockUser
 */
class UnblockUserTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private User $user;
	private UnblockUserFactory $unblockUserFactory;
	private DatabaseBlockStore $blockStore;

	protected function setUp(): void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$this->setGroupPermissions( 'sysop', 'deferredblock', true );

		// Prepare factory
		$this->unblockUserFactory = $this->getServiceContainer()->getUnblockUserFactory();
		$this->blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
	}

	public function testValidUnblock() {
		$performer = $this->mockRegisteredUltimateAuthority();
		$this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $performer->getUser()
		] );

		$this->assertInstanceOf( DatabaseBlock::class, $this->user->getBlock() );
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$performer,
			'test'
		)->unblock();
		$this->assertStatusOK( $status );
		$this->assertNotInstanceOf(
			DatabaseBlock::class,
			User::newFromName(
				$this->user->getName()
			)
			->getBlock()
		);
	}

	public function testUnblockFailWithHideName() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $performer->getUser(),
			'hideName' => true,
		] );
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$performer,
			'test'
		)->unblock();
		$this->assertStatusError( 'unblock-hideuser', $status );
	}

	public static function provideUnblockDeferredBlock(): array {
		return [
			'Deferred block' => [ [], 'deferredblock' ],
			'Hidden deferred block' => [ [ 'hideBlock' => true ], 'suppress' ],
		];
	}

	/** @dataProvider provideUnblockDeferredBlock */
	public function testUnblockDeferredBlock( array $blockParams, string $expectedLogType ): void {
		$performer = $this->getTestUser( [ 'sysop', 'suppress' ] )->getUser();
		$block = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $performer,
			'deferred' => true,
		] + $blockParams );

		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$performer,
			'test'
		)->unblock();
		$this->assertStatusGood( $status );
		$this->assertNull( $this->blockStore->newFromID( $block->getId(), true, false, true ) );

		// Only logged in the private log
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_action' => 'unblock' ] )
			->caller( __METHOD__ )
			->assertResultSet( [ [ $expectedLogType, 'unblock' ] ] );
	}

	public function testUnblockDeferredBlockWithoutPermission(): void {
		$this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $this->getTestSysop()->getUser(),
			'deferred' => true,
		] );
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'deferredblock' ] ),
			'test'
		)->unblock();
		$this->assertStatusError( 'ipb_cant_unblock', $status );
	}

	public function testRemoveDeferredBlockWithoutPermission(): void {
		$block = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $this->getTestSysop()->getUser(),
			'deferred' => true,
			'hideName' => true,
		] );
		$status = $this->unblockUserFactory->newRemoveBlock(
			$block,
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'deferredblock', 'hideuser' ] ),
			'test'
		)->unblock();
		$this->assertStatusError( 'ipb_cant_unblock', $status );
		$this->assertNotNull( $this->blockStore->newFromID( $block->getId(), true, false, true ) );
	}

	public static function provideUnblockIgnoresInvisibleDeferredBlock(): array {
		return [
			'Deferred block' => [ [], false ],
			'Hidden deferred block, performer without hideuser' => [ [ 'hideName' => true ], true ],
		];
	}

	/** @dataProvider provideUnblockIgnoresInvisibleDeferredBlock */
	public function testUnblockIgnoresInvisibleDeferredBlock(
		array $deferredBlockParams,
		bool $canSeeDeferredBlocks
	): void {
		$sysop = $this->getTestSysop()->getUser();
		$deferredBlock = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $sysop,
			'deferred' => true,
		] + $deferredBlockParams );
		$block = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $sysop,
		] );

		if ( !$canSeeDeferredBlocks ) {
			$this->setGroupPermissions( 'sysop', 'deferredblock', false );
		}
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$this->getTestUser( [ 'sysop' ] )->getAuthority(),
			'test'
		)->unblock();
		$this->assertStatusGood( $status );
		$this->assertSame( $block->getId(), $status->getValue()->getId() );
		$this->assertNotNull( $this->blockStore->newFromID( $deferredBlock->getId(), true, false, true ) );
	}

	public function testNotBlocked() {
		$this->user = User::newFromName( $this->user->getName() ); // Reload the user object
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'test'
		)->unblock();
		$this->assertStatusError( 'ipb_cant_unblock', $status );
	}

	public function testUnblockUnsafeWithSpecificBlock() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $this->user,
			'by' => $performer->getUser(),
		] );

		$block = $this->blockStore->newFromID( $block->getId() );
		$status = $this->unblockUserFactory->newRemoveBlock(
			$block,
			$performer,
			'test'
		)->unblockUnsafe();

		$this->assertStatusOK( $status );
	}

	public function testUnblockWithMultipleBlocks() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = $this->blockStore->newUnsaved( [
			'targetUser' => $this->user,
			'by' => $performer->getUser(),
		] );
		$this->blockStore->insertBlock( $block, null );
		$this->blockStore->insertBlock( $block, null );

		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user->getName(),
			$performer,
			'test'
		)->unblock();
		$this->assertStatusError( 'ipb_cant_unblock_multiple_blocks', $status );

		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user->getName(),
			$performer,
			'test'
		)->unblockUnsafe();
		$this->assertStatusError( 'ipb_cant_unblock_multiple_blocks', $status );
	}

	/**
	 * Regression test for T384716.
	 * A user can always remove a block that they created.
	 */
	public function testRemoveSelfImposedBlock() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = $this->blockStore->insertBlockWithParams( [
			'targetUser' => $performer,
			'by' => $performer->getUser(),
		] );
		$status = $this->unblockUserFactory
			->newRemoveBlock( $block, $performer, '' )
			->unblock();
		$this->assertStatusOK( $status );
	}
}
