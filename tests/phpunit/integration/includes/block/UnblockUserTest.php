<?php

namespace MediaWiki\Tests\Block;

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

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblock
	 */
	public function testValidUnblock() {
		$performer = $this->mockRegisteredUltimateAuthority();
		$block = new DatabaseBlock( [
			'address' => $this->user->getName(),
			'by' => $performer->getUser()
		] );
		$this->blockStore->insertBlock( $block );

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

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblock
	 */
	public function testUnblockFailWithHideName() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = new DatabaseBlock( [
			'address' => $this->user->getName(),
			'by' => $performer->getUser(),
			'hideName' => true,
		] );
		$this->blockStore->insertBlock( $block );
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$performer,
			'test'
		)->unblock();
		$this->assertStatusError( 'unblock-hideuser', $status );
	}

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblockUnsafe
	 */
	public function testNotBlocked() {
		$this->user = User::newFromName( $this->user->getName() ); // Reload the user object
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'test'
		)->unblock();
		$this->assertStatusError( 'ipb_cant_unblock', $status );
	}

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblockUnsafe
	 */
	public function testUnblockUnsafeWithSpecificBlock() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = new DatabaseBlock( [
			'address' => $this->user->getName(),
			'by' => $performer->getUser(),
		] );
		$result = $this->blockStore->insertBlock( $block, null );

		$block = $this->blockStore->newFromID( $result['id'] );
		$status = $this->unblockUserFactory->newRemoveBlock(
			$block,
			$performer,
			'test'
		)->unblockUnsafe();

		$this->assertStatusOK( $status );
	}

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblock
	 * @covers \MediaWiki\Block\UnblockUser::unblockUnsafe
	 */
	public function testUnblockWithMultipleBlocks() {
		$performer = $this->getTestUser( [ 'sysop' ] )->getUser();
		$block = new DatabaseBlock( [
			'address' => $this->user->getName(),
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
}
