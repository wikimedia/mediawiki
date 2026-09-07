<?php

namespace MediaWiki\Tests\Integration\Block;

use MediaWiki\Block\BlockUser;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\BlockUser
 */
class BlockUserTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	private User $target;
	private Authority $performer;
	private BlockUserFactory $blockUserFactory;

	protected function setUp(): void {
		parent::setUp();

		// Prepare users
		$this->target = $this->getTestUser()->getUser();
		$this->performer = $this->getTestUser( [ 'sysop', 'suppress' ] )->getAuthority();

		// Prepare factory
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
	}

	public function testValidTarget() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test block'
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->target->getBlock();
		$this->assertSame( 'test block', $block->getReasonComment()->text );
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertFalse( $block->getHideName() );
		$this->assertFalse( $block->isCreateAccountBlocked() );
		$this->assertTrue( $block->isUsertalkEditAllowed() );
		$this->assertFalse( $block->isEmailBlocked() );
		$this->assertTrue( $block->isAutoblocking() );

		$this->assertBlockLog( 'block', 'block', $this->performer->getUser(), $block );
	}

	private function assertBlockLog(
		string $expectedLogType,
		string $expectedLogAction,
		UserIdentity $performer,
		DatabaseBlock $block,
		array $expectedBlockFlags = [],
		array $blockRestrictions = [],
		int $logDeletedFlags = 0
	): void {
		$actualBlockId = $block->getId();

		// Get the most recent log for this block ID (reblocks use the same block ID
		// and for this test we should assume the caller wants to test the just
		// inserted block)
		$associatedLoggingId = $this->getDb()->newSelectQueryBuilder()
			->select( 'log_id' )
			->from( 'logging' )
			->where( [
				'log_params ' . $this->getDb()->buildLike(
					$this->getDb()->anyString(),
					'"blockId";i:' . $actualBlockId . ';',
					$this->getDb()->anyString()
				),
			] )
			->orderBy( 'log_id', SelectQueryBuilder::SORT_DESC )
			->caller( __METHOD__ )
			->fetchField();
		$this->assertNotFalse( $associatedLoggingId, 'Could not find logging row for the provided block' );

		$this->newSelectQueryBuilder()
			->select( [
				'log_type', 'log_action', 'log_title', 'log_namespace', 'comment_text', 'actor_name', 'log_deleted',
			] )
			->from( 'logging' )
			->join( 'comment', null, 'log_comment_id = comment_id' )
			->join( 'actor', null, 'log_actor = actor_id' )
			->where( [ 'log_id' => $associatedLoggingId ] )
			->caller( __METHOD__ )
			->assertRowValue( [
				$expectedLogType,
				$expectedLogAction,
				Title::newFromText( $block->getTargetUserIdentity()->getName() )->getDBkey(),
				NS_USER,
				$block->getReasonComment()->text,
				$performer->getName(),
				$logDeletedFlags
			] );

		$actualLogParams = $this->newSelectQueryBuilder()
			->select( [ 'log_params' ] )
			->from( 'logging' )
			->where( [ 'log_id' => $associatedLoggingId ] )
			->caller( __METHOD__ )
			->fetchField();
		$expectedLogParams = [
			'5::duration' => $block->getExpiry(),
			'6::flags' => implode( ',', $expectedBlockFlags ),
			'sitewide' => $block->isSitewide(),
			'blockId' => $block->getId(),
		];
		if ( $blockRestrictions ) {
			$expectedLogParams['7::restrictions'] = $blockRestrictions;
		}

		$this->assertArrayEquals( $expectedLogParams, LogEntryBase::extractParams( $actualLogParams ), false, true );
	}

	public function testHideUser() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test hideuser',
			[
				'isHideUser' => true
			]
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test hideuser', $block->getReasonComment()->text );
		$this->assertTrue( $block->getHideName() );
		$this->assertTrue( $block->getHideBlock() );

		$this->assertBlockLog( 'suppress', 'block', $this->performer->getUser(), $block, [ 'hiddenname' ] );
	}

	public function testHideBlock(): void {
		$status = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test hideblock',
			[ 'isHideBlock' => true ]
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test hideblock', $block->getReasonComment()->text );
		$this->assertTrue( $block->getHideBlock() );
		$this->assertFalse( $block->getHideName() );

		$this->assertBlockLog( 'suppress', 'block', $this->performer->getUser(), $block, [ 'hiddenblock' ] );
	}

	public function testExistingPage() {
		$this->getExistingTestPage( 'Existing Page' );
		$pageRestriction = PageRestriction::class;
		$page = $pageRestriction::newFromTitle( 'Existing Page' );
		$status = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test existingpage',
			[],
			[ $page ]
		)->placeBlock();
		$this->assertStatusGood( $status );
		$block = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test existingpage', $block->getReasonComment()->text );

		$this->assertBlockLog(
			'block',
			'block',
			$this->performer->getUser(),
			$block,
			[],
			[ 'pages' => [ 'Existing Page' ] ]
		);
	}

	public function testNonexistentPage() {
		$pageRestriction = PageRestriction::class;
		$page = $pageRestriction::newFromTitle( 'nonexistent' );
		$status = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test nonexistentpage',
			[],
			[ $page ]
		)->placeBlock();
		$this->assertStatusError( 'cant-block-nonexistent-page', $status );
	}

	public function testReblock() {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );
		$priorBlock = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $priorBlock );
		$this->assertSame( 'test block', $priorBlock->getReasonComment()->text );

		$blockId = $priorBlock->getId();

		$this->assertBlockLog( 'block', 'block', $this->performer->getUser(), $priorBlock );

		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test reblock'
		)->placeBlockUnsafe();
		$this->assertStatusError( 'ipb_already_blocked', $reblockStatus );

		$this->target->clearInstanceCache();
		$block = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( $blockId, $block->getId() );

		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test reblock'
		)->placeBlockUnsafe( BlockUser::CONFLICT_REBLOCK );
		$this->assertStatusGood( $reblockStatus );

		$this->target->clearInstanceCache();
		$block = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test reblock', $block->getReasonComment()->text );

		$this->assertBlockLog( 'block', 'reblock', $this->performer->getUser(), $block );
	}

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
			$this->target,
			$this->performer,
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );
		$priorBlock = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $priorBlock );
		$this->assertSame( $priorBlock->getId(), $hookBlock->getId() );
		$this->assertNull( $hookPriorBlock );

		$hookBlock = false;
		$hookPriorBlock = false;
		$reblockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test reblock'
		)->placeBlockUnsafe( BlockUser::CONFLICT_REBLOCK );
		$this->assertStatusGood( $reblockStatus );

		$this->target->clearInstanceCache();
		$newBlock = $this->target->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $newBlock );
		$this->assertSame( $newBlock->getId(), $hookBlock->getId() );
		$this->assertSame( $priorBlock->getId(), $hookPriorBlock->getId() );
	}

	public function testIPBlockAllowedAutoblockPreserved() {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
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
			$this->performer,
			'infinity',
			'test IP block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $IPBlockStatus );
		$IPBlock = $IPBlockStatus->getValue();
		$this->assertInstanceOf( DatabaseBlock::class, $IPBlock );
		$this->assertNull( $hookPriorBlock );

		$blockIds = array_map(
			static fn ( DatabaseBlock $block ) => $block->getId(),
			$blockStore->newListFromTarget( $target, null, /*fromPrimary=*/true )
		);
		$this->assertContains( $autoBlockId, $blockIds );
		$this->assertContains( $IPBlock->getId(), $blockIds );
	}

	public function testTooManyContribs() {
		// Set the contrib limit to zero so that it fails with one edit
		$this->overrideConfigValue( MainConfigNames::HideUserContribLimit, 0 );
		// Reset the stored instance
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		// Make the edit
		$this->editPage( 'BlockUserTest', 'test', '', NS_MAIN, $this->target );
		// Try to block the user with the hideuser option
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test block',
			[ 'isHideUser' => true ]
		)->placeBlockUnsafe();
		$this->assertStatusError( 'ipb_hide_invalid', $blockStatus );
	}

	public function testUpdateWithTooManyContribs() {
		$this->overrideConfigValue( MainConfigNames::HideUserContribLimit, 0 );
		$this->blockUserFactory = $this->getServiceContainer()->getBlockUserFactory();
		$this->editPage( 'BlockUserTest', 'test', '', NS_MAIN, $this->target );
		// Make a regular block, without the hideuser option
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->performer,
			'infinity',
			'test block'
		)->placeBlockUnsafe();
		$this->assertStatusGood( $blockStatus );

		// Try to change the block to include the hideuser option, which should
		// fail due to the edit
		$blockStatus = $this->blockUserFactory->newUpdateBlock(
			$blockStatus->value,
			$this->performer,
			'infinity',
			'test block',
			[ 'isHideUser' => true ]
		)->placeBlockUnsafe();
		$this->assertStatusError( 'ipb_hide_invalid', $blockStatus );
	}

	public function testPlaceBlockForHideUserWhenUserCannotHideUsers(): void {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'hideuser' ] ),
			'infinity',
			'test block',
			[ 'isHideUser' => true ]
		)->placeBlock();
		$this->assertStatusError( 'badaccess-group0', $blockStatus );
	}

	public function testPlaceBlockForHideBlockWhenUserCannotHideUsers(): void {
		$blockStatus = $this->blockUserFactory->newBlockUser(
			$this->target,
			$this->mockRegisteredAuthorityWithoutPermissions( [ 'hideuser' ] ),
			'infinity',
			'test block',
			[ 'isHideBlock' => true ]
		)->placeBlock();
		$this->assertStatusError( 'badaccess-group0', $blockStatus );
	}

}
