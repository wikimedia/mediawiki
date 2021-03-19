<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\UnblockUserFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;
use User;

/**
 * @group Blocking
 * @group Database
 */
class UnblockUserTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var UnblockUserFactory
	 */
	private $unblockUserFactory;

	public function setUp() : void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();

		// Prepare factory
		$this->unblockUserFactory = MediaWikiServices::getInstance()->getUnblockUserFactory();
	}

	private function convertErrorsArray( $arr ) {
		return array_map( static function ( $el ) {
			return $el[0];
		}, $arr );
	}

	/**
	 * @covers \MediaWiki\Block\UnblockUser::unblock
	 */
	public function testValidUnblock() {
		$performer = $this->mockAnonUltimateAuthority();
		$block = new DatabaseBlock( [
			'address' => $this->user->getName(),
			'by' => $performer->getUser()
		] );
		MediaWikiServices::getInstance()->getDatabaseBlockStore()->insertBlock( $block );

		$this->assertInstanceOf( DatabaseBlock::class, $this->user->getBlock() );
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$performer,
			'test'
		)->unblock();
		$this->assertTrue( $status->isOK() );
		$this->assertNotInstanceOf(
			DatabaseBlock::class,
			User::newFromName(
				$this->user->getName()
			)
			->getBlock()
		);
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
		$this->assertFalse( $status->isOK() );
		$this->assertContains(
			'ipb_cant_unblock',
			$this->convertErrorsArray(
				$status->getErrorsArray()
			)
		);
	}
}
