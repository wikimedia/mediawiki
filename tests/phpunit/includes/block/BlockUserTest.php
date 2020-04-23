<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass BlockUser
 */
class BlockUserTest extends MediaWikiIntegrationTestCase {
	/** @var User */
	private $user;

	/** @var User */
	private $performer;

	/** @var BlockUserFactory */
	private $blockUserFactory;

	protected function setUp() : void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();
		$this->performer = $this->getTestSysop()->getUser();

		// Prepare factory
		$this->blockUserFactory = MediaWikiServices::getInstance()->getBlockUserFactory();
	}

	/**
	 * @covers MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testValidTarget() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->performer,
			'infinity',
			'test block'
		)->placeBlock();
		$this->assertTrue( $status->isOK() );
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
	 * @covers MediaWiki\BLock\BlockUser::placeBlock
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
		$this->assertTrue( $status->isOK() );
		$block = $this->user->getBlock();
		$this->assertInstanceOf( DatabaseBlock::class, $block );
		$this->assertSame( 'test hideuser', $block->getReasonComment()->text );
		$this->assertTrue( $block->getHideName() );
	}
}
