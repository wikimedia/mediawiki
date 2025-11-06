<?php

namespace MediaWiki\Tests\Integration\Block;

use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @group Blocking
 * @group Database
 */
class BlockUserTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private User $user;
	private BlockUserFactory $blockUserFactory;

	protected function setUp(): void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();

		// Prepare factory
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testValidTarget() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test block'
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->user->getBlock();
		$this->assertSame( 'test block', $block->getReasonComment()->text );
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertFalse( $block->getHideName() );
		$this->assertFalse( $block->isCreateAccountBlocked() );
		$this->assertTrue( $block->isUsertalkEditAllowed() );
		$this->assertFalse( $block->isEmailBlocked() );
		$this->assertTrue( $block->isAutoblocking() );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testHideUser() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->getTestUser( [ 'sysop', 'suppress' ] )->getUser(),
			'infinity',
			'test hideuser',
			[
				'isHideUser' => true
			]
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test hideuser', $block->getReasonComment()->text );
		$this->assertTrue( $block->getHideName() );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testExistingPage() {
		$this->getExistingTestPage( 'Existing Page' );
		$pageRestriction = PageRestriction::class;
		$page = $pageRestriction::newFromTitle( 'Existing Page' );
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->getTestUser( [ 'sysop', 'suppress' ] )->getUser(),
			'infinity',
			'test existingpage',
			[],
			[ $page ]
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test existingpage', $block->getReasonComment()->text );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testNonexistentPage() {
		$pageRestriction = PageRestriction::class;
		$page = $pageRestriction::newFromTitle( 'nonexistent' );
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->getTestUser( [ 'sysop', 'suppress' ] )->getUser(),
			'infinity',
			'test nonexistentpage',
			[],
			[ $page ]
		)->placeBlock();
		$this->assertStatusError( 'cant-block-nonexistent-page', $status );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlockInternal
	 */
	public function testReblock() {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );
		$priorBlock = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $priorBlock );
		$this->assertSame( 'test block', $priorBlock->getReasonComment()->text );

		$blockId = $priorBlock->getId();

		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockAnonUltimateAuthority(),
			'infinity',
			'test reblock'
		)->placeBlockUnsafe( /*reblock=*/false );
		$this->assertStatusError( 'ipb_already_blocked', $reblockStatus );

		$this->user->clearInstanceCache();
		$block = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( $blockId, $block->getId() );

		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test reblock'
		)->placeBlockUnsafe( /*reblock=*/true );
		$this->assertStatusGood( $reblockStatus );

		$this->user->clearInstanceCache();
		$block = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test reblock', $block->getReasonComment()->text );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlockInternal
	 */
	public function testPostHook() {
		$hookBlock = false;
		$hookPriorBlock = false;
		$this->setTemporaryHook(
			'BlockIpComplete',
			static function ( $block, $legacyUser, $priorBlock )
			use ( &$hookBlock, &$hookPriorBlock )
			{
				$hookBlock = $block;
				$hookPriorBlock = $priorBlock;
			}
		);

		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );
		$priorBlock = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $priorBlock );
		$this->assertSame( $priorBlock->getId(), $hookBlock->getId() );
		$this->assertNull( $hookPriorBlock );

		$hookBlock = false;
		$hookPriorBlock = false;
		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test reblock'
		)->placeBlockUnsafe( /*reblock=*/true );
		$this->assertStatusGood( $reblockStatus );

		$this->user->clearInstanceCache();
		$newBlock = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $newBlock );
		$this->assertSame( $newBlock->getId(), $hookBlock->getId() );
		$this->assertSame( $priorBlock->getId(), $hookPriorBlock->getId() );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlockInternal
	 */
	public function testIPBlockAllowedAutoblockPreserved() {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test block with autoblocking',
			[ 'isAutoblocking' => true ]
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );
		/** @var DatabaseBlock $block */
		$block = $blockStatus->getValue();

		$target = '1.2.3.4';
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$autoBlockId = $blockStore->doAutoblock( $block, $target );
		$this->assertNotFalse( $autoBlockId );

		$hookPriorBlock = false;
		$this->setTemporaryHook(
			'BlockIpComplete',
			static function ( $block, $legacyUser, $priorBlock )
			use ( &$hookPriorBlock )
			{
				$hookPriorBlock = $priorBlock;
			}
		);

		$IPBlockStatus = $this->blockUserFactory->newBlockUser(
			$target,
			$this->mockRegisteredUltimateAuthority(),
			'infinity',
			'test IP block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $IPBlockStatus );
		$IPBlock = $IPBlockStatus->getValue();
		$this->assertInstanceOf( DatabaseBlock::class, $IPBlock );
		$this->assertNull( $hookPriorBlock );

		$blockIds = array_map(
			static function ( DatabaseBlock $block ) {
				return $block->getId();
			},
			$blockStore->newListFromTarget( $target, null, /*fromPrimary=*/true )
		);
		$this->assertContains( $autoBlockId, $blockIds );
		$this->assertContains( $IPBlock->getId(), $blockIds );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlockUnsafe
	 */
	public function testTooManyContribs() {
		// Set the contrib limit to zero so that it fails with one edit
		$this->overrideConfigValue( MainConfigNames::HideUserContribLimit, 0 );
		// Reset the stored instance
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		// Make the edit
		$this->editPage( 'BlockUserTest', 'test', '', NS_MAIN, $this->user );
		// Try to block the user with the hideuser option
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->getTestUser( [ 'sysop', 'suppress' ] )->getUser(),
			'infinity',
			'test block',
			[ 'isHideUser' => true ]
		)->placeBlockUnsafe();
		$this->assertStatusError( 'ipb_hide_invalid', $blockStatus );
	}

	/**
	 * @covers \MediaWiki\Block\BlockUser::placeBlockUnsafe
	 */
	public function testUpdateWithTooManyContribs() {
		$this->overrideConfigValue( MainConfigNames::HideUserContribLimit, 0 );
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		$this->editPage( 'BlockUserTest', 'test', '', NS_MAIN, $this->user );
		$performer = $this->getTestUser( [ 'sysop', 'suppress' ] )->getUser();
		// Make a regular block, without the hideuser option
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->user,
			$performer,
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );

		// Try to change the block to include the hideuser option, which should
		// fail due to the edit
		$blockStatus = $this->blockUserFactory->newUpdateBlock(
			$blockStatus->value,
			$performer,
			'infinity',
			'test block',
			[ 'isHideUser' => true ]
		)->placeBlockUnsafe();
		$this->assertStatusError( 'ipb_hide_invalid', $blockStatus );
	}

}
