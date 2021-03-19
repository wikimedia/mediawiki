<?php

use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;

/**
 * @group Blocking
 * @group Database
 */
class BlockUserTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/** @var User */
	private $user;

	/** @var BlockUserFactory */
	private $blockUserFactory;

	protected function setUp() : void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();

		// Prepare factory
		$this->blockUserFactory = MediaWikiServices::getInstance()->getBlockUserFactory();
	}

	/**
	 * @covers MediaWiki\Block\BlockUser::placeBlock
	 */
	public function testValidTarget() {
		$status = $this->blockUserFactory->newBlockUser(
			$this->user,
			$this->mockAnonUltimateAuthority(),
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
	 * @covers MediaWiki\Block\BlockUser::placeBlock
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
