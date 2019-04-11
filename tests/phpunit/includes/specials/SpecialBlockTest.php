<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass SpecialBlock
 */
class SpecialBlockTest extends SpecialPageTestBase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		return new SpecialBlock();
	}

	public function tearDown() {
		parent::tearDown();
		$this->resetTables();
	}

	/**
	 * @covers ::getFormFields()
	 */
	public function testGetFormFields() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => false,
			'wgBlockAllowsUTEdit' => true,
		] );
		$page = $this->newSpecialPage();
		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$fields = $wrappedPage->getFormFields();
		$this->assertInternalType( 'array', $fields );
		$this->assertArrayHasKey( 'Target', $fields );
		$this->assertArrayHasKey( 'Expiry', $fields );
		$this->assertArrayHasKey( 'Reason', $fields );
		$this->assertArrayHasKey( 'CreateAccount', $fields );
		$this->assertArrayHasKey( 'DisableUTEdit', $fields );
		$this->assertArrayHasKey( 'AutoBlock', $fields );
		$this->assertArrayHasKey( 'HardBlock', $fields );
		$this->assertArrayHasKey( 'PreviousTarget', $fields );
		$this->assertArrayHasKey( 'Confirm', $fields );

		$this->assertArrayNotHasKey( 'EditingRestriction', $fields );
		$this->assertArrayNotHasKey( 'PageRestrictions', $fields );
		$this->assertArrayNotHasKey( 'NamespaceRestrictions', $fields );
	}

	/**
	 * @covers ::getFormFields()
	 */
	public function testGetFormFieldsPartialBlocks() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => true,
		] );
		$page = $this->newSpecialPage();
		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$fields = $wrappedPage->getFormFields();

		$this->assertArrayHasKey( 'EditingRestriction', $fields );
		$this->assertArrayHasKey( 'PageRestrictions', $fields );
		$this->assertArrayHasKey( 'NamespaceRestrictions', $fields );
	}

	/**
	 * @covers ::maybeAlterFormDefaults()
	 */
	public function testMaybeAlterFormDefaults() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => false,
			'wgBlockAllowsUTEdit' => true,
		] );

		$block = $this->insertBlock();

		// Refresh the block from the database.
		$block = Block::newFromTarget( $block->getTarget() );

		$page = $this->newSpecialPage();

		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$wrappedPage->target = $block->getTarget();
		$fields = $wrappedPage->getFormFields();

		$this->assertSame( (string)$block->getTarget(), $fields['Target']['default'] );
		$this->assertSame( $block->isHardblock(), $fields['HardBlock']['default'] );
		$this->assertSame( $block->isCreateAccountBlocked(), $fields['CreateAccount']['default'] );
		$this->assertSame( $block->isAutoblocking(), $fields['AutoBlock']['default'] );
		$this->assertSame( !$block->isUsertalkEditAllowed(), $fields['DisableUTEdit']['default'] );
		$this->assertSame( $block->getReason(), $fields['Reason']['default'] );
		$this->assertSame( 'infinite', $fields['Expiry']['default'] );
	}

	/**
	 * @covers ::maybeAlterFormDefaults()
	 */
	public function testMaybeAlterFormDefaultsPartial() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => true,
		] );

		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );

		$block = new \Block( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 0,
			'enableAutoblock' => true,
		] );

		$block->setRestrictions( [
			new PageRestriction( 0, $pageSaturn->getId() ),
			new PageRestriction( 0, $pageMars->getId() ),
			new NamespaceRestriction( 0, NS_TALK ),
			// Deleted page.
			new PageRestriction( 0, 999999 ),
		] );

		$block->insert();

		// Refresh the block from the database.
		$block = Block::newFromTarget( $block->getTarget() );

		$page = $this->newSpecialPage();

		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$wrappedPage->target = $block->getTarget();
		$fields = $wrappedPage->getFormFields();

		$titles = [
			$pageMars->getTitle()->getPrefixedText(),
			$pageSaturn->getTitle()->getPrefixedText(),
		];

		$this->assertSame( (string)$block->getTarget(), $fields['Target']['default'] );
		$this->assertSame( 'partial', $fields['EditingRestriction']['default'] );
		$this->assertSame( implode( "\n", $titles ), $fields['PageRestrictions']['default'] );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessForm() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => false,
		] );
		$badActor = $this->getTestUser()->getUser();
		$context = RequestContext::getMain();

		$page = $this->newSpecialPage();
		$reason = 'test';
		$expiry = 'infinity';
		$data = [
			'Target' => (string)$badActor,
			'Expiry' => 'infinity',
			'Reason' => [
				$reason,
			],
			'Confirm' => '1',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '1',
			'HideUser' => '0',
			'Watch' => '0',
		];
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormExisting() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => false,
		] );
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$context = RequestContext::getMain();

		// Create a block that will be updated.
		$block = new \Block( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 0,
			'enableAutoblock' => false,
		] );
		$block->insert();

		$page = $this->newSpecialPage();
		$reason = 'test';
		$expiry = 'infinity';
		$data = [
			'Target' => (string)$badActor,
			'Expiry' => 'infinity',
			'Reason' => [
				$reason,
			],
			'Confirm' => '1',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '1',
			'HideUser' => '0',
			'Watch' => '0',
		];
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertSame( '1', $block->isAutoblocking() );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormRestrictions() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => true,
		] );
		$badActor = $this->getTestUser()->getUser();
		$context = RequestContext::getMain();

		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );

		$titles = [
			$pageSaturn->getTitle()->getText(),
			$pageMars->getTitle()->getText(),
		];

		$page = $this->newSpecialPage();
		$reason = 'test';
		$expiry = 'infinity';
		$data = [
			'Target' => (string)$badActor,
			'Expiry' => 'infinity',
			'Reason' => [
				$reason,
			],
			'Confirm' => '1',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '1',
			'HideUser' => '0',
			'Watch' => '0',
			'EditingRestriction' => 'partial',
			'PageRestrictions' => implode( "\n", $titles ),
			'NamespaceRestrictions' => '',
		];
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertCount( 2, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
			new PageRestriction( $block->getId(), $pageSaturn->getId() ),
		] ) );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormRestrictionsChange() {
		$this->setMwGlobals( [
			'wgEnablePartialBlocks' => true,
		] );
		$badActor = $this->getTestUser()->getUser();
		$context = RequestContext::getMain();

		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );

		$titles = [
			$pageSaturn->getTitle()->getText(),
			$pageMars->getTitle()->getText(),
		];

		// Create a partial block.
		$page = $this->newSpecialPage();
		$reason = 'test';
		$expiry = 'infinity';
		$data = [
			'Target' => (string)$badActor,
			'Expiry' => 'infinity',
			'Reason' => [
				$reason,
			],
			'Confirm' => '1',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '1',
			'HideUser' => '0',
			'Watch' => '0',
			'EditingRestriction' => 'partial',
			'PageRestrictions' => implode( "\n", $titles ),
			'NamespaceRestrictions' => '',
		];
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertCount( 2, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
			new PageRestriction( $block->getId(), $pageSaturn->getId() ),
		] ) );

		// Remove a page from the partial block.
		$data['PageRestrictions'] = $pageMars->getTitle()->getText();
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertCount( 1, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
		] ) );

		// Remove the last page from the block.
		$data['PageRestrictions'] = '';
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertCount( 0, $block->getRestrictions() );

		// Change to sitewide.
		$data['EditingRestriction'] = 'sitewide';
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = Block::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReason() );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertTrue( $block->isSitewide() );
		$this->assertCount( 0, $block->getRestrictions() );

		// Ensure that there are no restrictions where the blockId is 0.
		$count = $this->db->selectRowCount(
			'ipblocks_restrictions',
			'*',
			[ 'ir_ipb_id' => 0 ],
			__METHOD__
		);
		$this->assertSame( 0, $count );
	}

	/**
	 * @dataProvider provideCheckUnblockSelf
	 * @covers ::checkUnblockSelf
	 */
	public function testCheckUnblockSelf(
		$blockedUser,
		$blockPerformer,
		$adjustPerformer,
		$adjustTarget,
		$expectedResult,
		$reason
	) {
		$this->setMwGlobals( [
			'wgBlockDisablesLogin' => false,
		] );
		$this->setGroupPermissions( 'sysop', 'unblockself', true );
		$this->setGroupPermissions( 'user', 'block', true );
		// Getting errors about creating users in db in provider.
		// Need to use mutable to ensure different named testusers.
		$users = [
			'u1' => TestUserRegistry::getMutableTestUser( __CLASS__, 'sysop' )->getUser(),
			'u2' => TestUserRegistry::getMutableTestUser( __CLASS__, 'sysop' )->getUser(),
			'u3' => TestUserRegistry::getMutableTestUser( __CLASS__, 'sysop' )->getUser(),
			'u4' => TestUserRegistry::getMutableTestUser( __CLASS__, 'sysop' )->getUser(),
			'nonsysop' => $this->getTestUser()->getUser()
		];
		foreach ( [ 'blockedUser', 'blockPerformer', 'adjustPerformer', 'adjustTarget' ] as $var ) {
			$$var = $users[$$var];
		}

		$block = new \Block( [
			'address' => $blockedUser->getName(),
			'user' => $blockedUser->getId(),
			'by' => $blockPerformer->getId(),
			'expiry' => 'infinity',
			'sitewide' => 1,
			'enableAutoblock' => true,
		] );

		$block->insert();

		$this->assertSame(
			SpecialBlock::checkUnblockSelf( $adjustTarget, $adjustPerformer ),
			$expectedResult,
			$reason
		);
	}

	public function provideCheckUnblockSelf() {
		// 'blockedUser', 'blockPerformer', 'adjustPerformer', 'adjustTarget'
		return [
			[ 'u1', 'u2', 'u3', 'u4', true, 'Unrelated users' ],
			[ 'u1', 'u2', 'u1', 'u4', 'ipbblocked', 'Block unrelated while blocked' ],
			[ 'u1', 'u2', 'u1', 'u1', true, 'Has unblockself' ],
			[ 'nonsysop', 'u2', 'nonsysop', 'nonsysop', 'ipbnounblockself', 'no unblockself' ],
			[ 'nonsysop', 'nonsysop', 'nonsysop', 'nonsysop', true, 'no unblockself but can de-selfblock' ],
			[ 'u1', 'u2', 'u1', 'u2', true, 'Can block user who blocked' ],
		];
	}

	protected function insertBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new \Block( [
			'address' => $badActor->getName(),
			'user' => $badActor->getId(),
			'by' => $sysop->getId(),
			'expiry' => 'infinity',
			'sitewide' => 1,
			'enableAutoblock' => true,
		] );

		$block->insert();

		return $block;
	}

	protected function resetTables() {
		$this->db->delete( 'ipblocks', '*', __METHOD__ );
		$this->db->delete( 'ipblocks_restrictions', '*', __METHOD__ );
	}

	/**
	 * Get a BlockRestrictionStore instance
	 *
	 * @return BlockRestrictionStore
	 */
	private function getBlockRestrictionStore() : BlockRestrictionStore {
		$loadBalancer = $this->getMockBuilder( LoadBalancer::class )
					   ->disableOriginalConstructor()
					   ->getMock();

		return new BlockRestrictionStore( $loadBalancer );
	}
}
