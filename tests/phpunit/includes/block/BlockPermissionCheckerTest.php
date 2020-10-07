<?php

use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\BlockPermissionChecker
 */
class BlockPermissionCheckerTest extends MediaWikiLangTestCase {
	/**
	 * @var User
	 */
	private $sysop;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var BlockPermissionCheckerFactory
	 */
	private $factory;

	protected function setUp() : void {
		parent::setUp();

		$this->setGroupPermissions( 'sysop', 'unblockself', false );
		$this->setGroupPermissions( 'bureaucrat', 'unblockself', true );

		$this->sysop = $this->getMutableTestUser( [ 'sysop' ] )->getUser();
		$this->user = $this->getTestUser()->getUser();
		$this->factory = MediaWikiServices::getInstance()
			->getBlockPermissionCheckerFactory();
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testNotBlockedPerformer() {
		$this->assertTrue(
			$this->factory->newBlockPermissionChecker(
				$this->user,
				$this->sysop
			)->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testBlockedPerformer() {
		$block = new DatabaseBlock( [
			'address' => $this->sysop->getName(),
			'user' => $this->sysop->getId(),
			'by' => $this->getMutableTestUser( [ 'sysop' ] )->getUser()->getId(),
			'expiry' => 'infinity',
			'sitewide' => true,
			'enableAutoblock' => true,
		] );
		$block->insert();

		$this->assertEquals(
			'ipbblocked',
			$this->factory->newBlockPermissionChecker(
				$this->user,
				$this->sysop
			)->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testSelfblockedPerformer() {
		$block = new DatabaseBlock( [
			'address' => $this->sysop->getName(),
			'user' => $this->sysop->getId(),
			'by' => $this->sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => true,
			'enableAutoblock' => true,
		] );
		$block->insert();

		$this->assertTrue(
			$this->factory->newBlockPermissionChecker(
				$this->sysop,
				$this->sysop
			)->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testNoUnblockself() {
		$block = new DatabaseBlock( [
			'address' => $this->sysop->getName(),
			'user' => $this->sysop->getId(),
			'by' => $this->getMutableTestUser( [ 'sysop' ] )->getUser()->getId(),
			'expiry' => 'infinity',
			'sitewide' => true,
			'enableAutoblock' => true,
		] );
		$block->insert();

		$this->assertEquals(
			'ipbnounblockself',
			$this->factory->newBlockPermissionChecker(
				$this->sysop,
				$this->sysop
			)->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testHasUnblockself() {
		$bureaucrat = $this->getMutableTestUser( [ 'sysop', 'bureaucrat' ] )->getUser();
		$block = new DatabaseBlock( [
			'address' => $bureaucrat->getName(),
			'user' => $bureaucrat->getId(),
			'by' => $this->getMutableTestUser( [ 'sysop' ] )->getUser()->getId(),
			'expiry' => 'infinity',
			'sitewide' => true,
			'enableAutoblock' => true,
		] );
		$block->insert();

		$this->assertTrue(
			$this->factory->newBlockPermissionChecker(
				$bureaucrat,
				$bureaucrat
			)->checkBlockPermissions()
		);
	}

	/**
	 * @covers ::checkBlockPermissions
	 */
	public function testBlocker() {
		$blockee = $this->sysop;
		$blocker = $this->getMutableTestUser( [ 'sysop' ] )->getUser();

		$block = new DatabaseBlock( [
			'address' => $blockee->getName(),
			'user' => $blockee->getId(),
			'by' => $blocker->getId(),
			'expiry' => 'infinity',
			'sitewide' => true,
			'enableAutoblock' => true,
		] );
		$block->insert();

		$this->assertTrue(
			$this->factory->newBlockPermissionChecker(
				$blocker,
				$blockee
			)->checkBlockPermissions()
		);
	}
}
