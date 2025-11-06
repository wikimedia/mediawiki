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
