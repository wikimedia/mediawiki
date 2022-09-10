<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\UnblockUserFactory;
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

	protected function setUp(): void {
		parent::setUp();

		// Prepare users
		$this->user = $this->getTestUser()->getUser();

		// Prepare factory
		$this->unblockUserFactory = $this->getServiceContainer()->getUnblockUserFactory();
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
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

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
	 * @covers \MediaWiki\Block\UnblockUser::unblockUnsafe
	 */
	public function testNotBlocked() {
		$this->user = User::newFromName( $this->user->getName() ); // Reload the user object
		$status = $this->unblockUserFactory->newUnblockUser(
			$this->user,
			$this->mockRegisteredUltimateAuthority(),
			'test'
		)->unblock();
		$this->assertStatusNotOK( $status );
		$this->assertContains(
			'ipb_cant_unblock',
			array_column( $status->getErrorsArray(), 0 )
		);
	}
}
