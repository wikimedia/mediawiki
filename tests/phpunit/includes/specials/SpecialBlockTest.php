<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\TestingAccessWrapper;

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
		return new SpecialBlock(
			MediaWikiServices::getInstance()->getPermissionManager()
		);
	}

	protected function tearDown() : void {
		parent::tearDown();
		$this->resetTables();
	}

	/**
	 * @covers ::getFormFields()
	 */
	public function testGetFormFields() {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => true,
		] );
		$page = $this->newSpecialPage();
		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$fields = $wrappedPage->getFormFields();
		$this->assertIsArray( $fields );
		$this->assertArrayHasKey( 'Target', $fields );
		$this->assertArrayHasKey( 'Expiry', $fields );
		$this->assertArrayHasKey( 'Reason', $fields );
		$this->assertArrayHasKey( 'CreateAccount', $fields );
		$this->assertArrayHasKey( 'DisableUTEdit', $fields );
		$this->assertArrayHasKey( 'AutoBlock', $fields );
		$this->assertArrayHasKey( 'HardBlock', $fields );
		$this->assertArrayHasKey( 'PreviousTarget', $fields );
		$this->assertArrayHasKey( 'Confirm', $fields );
		$this->assertArrayHasKey( 'EditingRestriction', $fields );
		$this->assertArrayHasKey( 'PageRestrictions', $fields );
		$this->assertArrayHasKey( 'NamespaceRestrictions', $fields );
	}

	/**
	 * @covers ::maybeAlterFormDefaults()
	 */
	public function testMaybeAlterFormDefaults() {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => true,
		] );

		$block = $this->insertBlock();

		// Refresh the block from the database.
		$block = DatabaseBlock::newFromTarget( $block->getTarget() );

		$page = $this->newSpecialPage();

		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$wrappedPage->target = $block->getTarget();
		$fields = $wrappedPage->getFormFields();

		$this->assertSame( (string)$block->getTarget(), $fields['Target']['default'] );
		$this->assertSame( $block->isHardblock(), $fields['HardBlock']['default'] );
		$this->assertSame( $block->isCreateAccountBlocked(), $fields['CreateAccount']['default'] );
		$this->assertSame( $block->isAutoblocking(), $fields['AutoBlock']['default'] );
		$this->assertSame( !$block->isUsertalkEditAllowed(), $fields['DisableUTEdit']['default'] );
		$this->assertSame( $block->getReasonComment()->text, $fields['Reason']['default'] );
		$this->assertSame( 'infinite', $fields['Expiry']['default'] );
	}

	/**
	 * @covers ::maybeAlterFormDefaults()
	 */
	public function testMaybeAlterFormDefaultsPartial() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );

		$block = new DatabaseBlock( [
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
		$block = DatabaseBlock::newFromTarget( $block->getTarget() );

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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormExisting() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$context = RequestContext::getMain();

		// Create a block that will be updated.
		$block = new DatabaseBlock( [
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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertSame( true, $block->isAutoblocking() );
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormRestrictions() {
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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
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

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertSame( [], $block->getRestrictions() );

		// Change to sitewide.
		$data['EditingRestriction'] = 'sitewide';
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = DatabaseBlock::newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertTrue( $block->isSitewide() );
		$this->assertSame( [], $block->getRestrictions() );

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
	 * @dataProvider provideProcessFormErrors
	 * @covers ::processForm()
	 */
	public function testProcessFormErrors( $data, $expected, $options = [] ) {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => true,
		] );

		$performer = $this->getTestSysop()->getUser();
		$target = !empty( $options['blockingSelf'] ) ? $performer : '1.2.3.4';
		$defaultData = [
			'Target' => $target,
			'PreviousTarget' => $target,
			'Expiry' => 'infinity',
			'Confirm' => '0',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		$this->assertEquals( $expected, $result[0] );
	}

	public function provideProcessFormErrors() {
		return [
			'Invalid expiry' => [
				[
					'Expiry' => 'invalid',
				],
				'ipb_expiry_invalid',
			],
			'Expiry is in the past' => [
				[
					'Expiry' => 'yesterday',
				],
				'ipb_expiry_old',
			],
			'Bad ip address' => [
				[
					'Target' => '1.2.3.4/1234',
				],
				'badipaddress',
			],
			'Edit user talk page invalid with no restrictions' => [
				[
					'EditingRestriction' => 'partial',
					'DisableUTEdit' => '1',
					'PageRestrictions' => '',
					'NamespaceRestrictions' => '',
				],
				'ipb-prevent-user-talk-edit',
			],
			'Edit user talk page invalid with namespace restriction != NS_USER_TALK ' => [
				[
					'EditingRestriction' => 'partial',
					'DisableUTEdit' => '1',
					'PageRestrictions' => '',
					'NamespaceRestrictions' => NS_USER,
				],
				'ipb-prevent-user-talk-edit',
			],
			'Blocking self and target changed' => [
				[
					'PreviousTarget' => 'other',
					'Confirm' => '1',
				],
				'ipb-blockingself',
				[
					'blockingSelf' => true,
				],
			],
			'Blocking self and no confirm' => [
				[],
				'ipb-blockingself',
				[
					'blockingSelf' => true,
				],
			],
			'Empty expiry' => [
				[
					'Expiry' => '',
				],
				'ipb_expiry_invalid',
			],
			'Expiry valid but longer than 50 chars' => [
				[
					'Expiry' => '30th September 9999 19:19:19.532453 Europe/Amsterdam',
				],
				'ipb_expiry_invalid',
			],
		];
	}

	/**
	 * @dataProvider provideProcessFormErrorsReblock
	 * @covers ::processForm()
	 */
	public function testProcessFormErrorsReblock( $data, $permissions, $expected ) {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => true,
		] );

		$performer = $this->getTestSysop()->getUser();
		$this->overrideUserPermissions( $performer, $permissions );
		$blockedUser = $this->getTestUser()->getUser();

		$block = new DatabaseBlock( [
			'address' => $blockedUser,
			'by' => $performer->getId(),
			'hideName' => true,
		] );
		$block->insert();

		// Matches the existing block
		$defaultData = [
			'Target' => $blockedUser->getName(),
			'PreviousTarget' => $blockedUser->getName(),
			'Expiry' => 'infinity',
			'DisableUTEdit' => '1',
			'CreateAccount' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '0',
			'HideUser' => '1',
			'Confirm' => '1',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
		$this->assertEquals( $expected, $error );
	}

	public function provideProcessFormErrorsReblock() {
		return [
			'Reblock user with Confirm false' => [
				[
					// Avoid error for hiding user with confirm false
					'HideUser' => '0',
					'Confirm' => '0',
				],
				[ 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock user with Reblock false' => [
				[ 'Reblock' => '0' ],
				[ 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock with confirm True but target has changed' => [
				[ 'PreviousTarget' => '1.2.3.4' ],
				[ 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock with same block' => [
				[ 'HideUser' => '1' ],
				[ 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock hidden user with wrong permissions' => [
				[ 'HideUser' => '0' ],
				[ 'hideuser' => false ],
				'cant-see-hidden-user',
			],
		];
	}

	/**
	 * @dataProvider provideProcessFormErrorsHideUser
	 * @covers ::processForm()
	 */
	public function testProcessFormErrorsHideUser( $data, $permissions, $expected ) {
		$performer = $this->getTestSysop()->getUser();
		$this->overrideUserPermissions( $performer, $permissions );

		$defaultData = [
			'Target' => $this->getTestUser()->getUser(),
			'HideUser' => '1',
			'Expiry' => 'infinity',
			'Confirm' => '1',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		$this->assertEquals( $expected, $result[0] );
	}

	public function provideProcessFormErrorsHideUser() {
		return [
			'HideUser with wrong permissions' => [
				[],
				[ 'hideuser' => '0' ],
				'badaccess-group0',
			],
			'Hideuser with partial block' => [
				[ 'EditingRestriction' => 'partial' ],
				[ 'hideuser' ],
				'ipb_hide_partial',
			],
			'Hideuser with finite expiry' => [
				[ 'Expiry' => '1 hour' ],
				[ 'hideuser' ],
				'ipb_expiry_temp',
			],
			'Hideuser with no confirm' => [
				[ 'Confirm' => '0' ],
				[ 'hideuser' ],
				'ipb-confirmhideuser',
			],
		];
	}

	/**
	 * @covers ::processForm()
	 */
	public function testProcessFormErrorsHideUserProlific() {
		$this->setMwGlobals( [ 'wgHideUserContribLimit' => 0 ] );

		$performer = $this->getTestSysop()->getUser();
		$this->overrideUserPermissions( $performer, [ 'hideuser' ] );

		$userToBlock = $this->getTestUser()->getUser();
		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageSaturn->doEditContent(
			ContentHandler::makeContent( 'content', $pageSaturn->getTitle() ),
			'summary',
			0,
			false,
			$userToBlock
		);

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			[
				'Target' => $userToBlock,
				'HideUser' => '1',
				'Expiry' => 'infinity',
				'Confirm' => '1',
			],
			$context
		);

		$this->assertEquals( 'ipb_hide_invalid', $result[0][0] );
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
		$sitewide,
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

		$block = new DatabaseBlock( [
			'address' => $blockedUser->getName(),
			'user' => $blockedUser->getId(),
			'by' => $blockPerformer->getId(),
			'expiry' => 'infinity',
			'sitewide' => $sitewide,
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
			[ 'u1', 'u2', 'u3', 'u4', 1, true, 'Unrelated users' ],
			[ 'u1', 'u2', 'u1', 'u4', 1, 'ipbblocked', 'Block unrelated while blocked' ],
			[ 'u1', 'u2', 'u1', 'u4', 0, true, 'Block unrelated while partial blocked' ],
			[ 'u1', 'u2', 'u1', 'u1', 1, true, 'Has unblockself' ],
			[ 'nonsysop', 'u2', 'nonsysop', 'nonsysop', 1, 'ipbnounblockself', 'no unblockself' ],
			[ 'nonsysop', 'nonsysop', 'nonsysop', 'nonsysop', 1, true,
				'no unblockself but can de-selfblock'
			],
			[ 'u1', 'u2', 'u1', 'u2', 1, true, 'Can block user who blocked' ],
		];
	}

	protected function insertBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
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
