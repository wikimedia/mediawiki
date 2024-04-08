<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialBlock;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass \MediaWiki\Specials\SpecialBlock
 */
class SpecialBlockTest extends SpecialPageTestBase {
	use MockAuthorityTrait;

	private $blockStore;

	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialBlock(
			$services->getBlockUtils(),
			$services->getBlockPermissionCheckerFactory(),
			$services->getBlockUserFactory(),
			$this->blockStore,
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getBlockActionInfo(),
			$services->getTitleFormatter(),
			$services->getNamespaceInfo()
		);
	}

	protected function setUp(): void {
		parent::setUp();
		$this->blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
	}

	/**
	 * @covers ::getFormFields
	 */
	public function testGetFormFields() {
		$this->overrideConfigValues( [
			MainConfigNames::BlockAllowsUTEdit => true,
			MainConfigNames::EnablePartialActionBlocks => true,
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
		$this->assertArrayHasKey( 'ActionRestrictions', $fields );
	}

	/**
	 * @covers ::getFormFields
	 */
	public function testGetFormFieldsActionRestrictionDisabled() {
		$this->overrideConfigValue( MainConfigNames::EnablePartialActionBlocks, false );
		$page = $this->newSpecialPage();
		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$fields = $wrappedPage->getFormFields();
		$this->assertArrayNotHasKey( 'ActionRestrictions', $fields );
	}

	/**
	 * @covers ::maybeAlterFormDefaults
	 */
	public function testMaybeAlterFormDefaults() {
		$this->overrideConfigValue( MainConfigNames::BlockAllowsUTEdit, true );

		$block = $this->insertBlock();

		// Refresh the block from the database.
		$block = $this->blockStore->newFromTarget( $block->getTargetUserIdentity() );

		$page = $this->newSpecialPage();

		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$wrappedPage->target = $block->getTargetUserIdentity();
		$fields = $wrappedPage->getFormFields();

		$this->assertSame( $block->getTargetName(), $fields['Target']['default'] );
		$this->assertSame( $block->isHardblock(), $fields['HardBlock']['default'] );
		$this->assertSame( $block->isCreateAccountBlocked(), $fields['CreateAccount']['default'] );
		$this->assertSame( $block->isAutoblocking(), $fields['AutoBlock']['default'] );
		$this->assertSame( !$block->isUsertalkEditAllowed(), $fields['DisableUTEdit']['default'] );
		$this->assertSame( $block->getReasonComment()->text, $fields['Reason']['default'] );
		$this->assertSame( 'infinite', $fields['Expiry']['default'] );
	}

	/**
	 * @covers ::maybeAlterFormDefaults
	 */
	public function testMaybeAlterFormDefaultsPartial() {
		$this->overrideConfigValue( MainConfigNames::EnablePartialActionBlocks, true );
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );
		$actionId = 100;

		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
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
			new ActionRestriction( 0, $actionId ),
		] );

		$this->blockStore->insertBlock( $block );

		// Refresh the block from the database.
		$block = $this->blockStore->newFromTarget( $block->getTargetUserIdentity() );

		$page = $this->newSpecialPage();

		$wrappedPage = TestingAccessWrapper::newFromObject( $page );
		$wrappedPage->target = $block->getTargetUserIdentity();
		$fields = $wrappedPage->getFormFields();

		$titles = [
			$pageMars->getTitle()->getPrefixedText(),
			$pageSaturn->getTitle()->getPrefixedText(),
		];

		$this->assertSame( $block->getTargetName(), $fields['Target']['default'] );
		$this->assertSame( 'partial', $fields['EditingRestriction']['default'] );
		$this->assertSame( implode( "\n", $titles ), $fields['PageRestrictions']['default'] );
		$this->assertSame( [ $actionId ], $fields['ActionRestrictions']['default'] );
	}

	/**
	 * @covers ::processForm
	 */
	public function testProcessForm() {
		$badActor = $this->getTestUser()->getUserIdentity();
		$context = RequestContext::getMain();
		$context->setUser( $this->getTestSysop()->getUser() );

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

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
	}

	/**
	 * @covers ::processForm
	 */
	public function testProcessFormExisting() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();
		$context = RequestContext::getMain();
		$context->setUser( $sysop );

		// Create a block that will be updated.
		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
			'sitewide' => 0,
			'enableAutoblock' => false,
		] );
		$this->blockStore->insertBlock( $block );

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

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertTrue( $block->isAutoblocking() );
	}

	/**
	 * @covers ::processForm
	 */
	public function testProcessFormRestrictions() {
		$this->overrideConfigValue( MainConfigNames::EnablePartialActionBlocks, true );

		$badActor = $this->getTestUser()->getUser();
		$context = RequestContext::getMain();
		$context->setUser( $this->getTestSysop()->getUser() );

		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageMars = $this->getExistingTestPage( 'Mars' );
		$actionId = 100;

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
			'ActionRestrictions' => [ $actionId ],
		];
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertCount( 3, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
			new PageRestriction( $block->getId(), $pageSaturn->getId() ),
			new ActionRestriction( $block->getId(), $actionId ),
		] ) );
	}

	/**
	 * @covers ::processForm
	 */
	public function testProcessFormRestrictionsChange() {
		$badActor = $this->getTestUser()->getUser();
		$context = RequestContext::getMain();
		$context->setUser( $this->getTestSysop()->getUser() );

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
			'CreateAccount' => '1',
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

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertTrue( $block->isCreateAccountBlocked() );
		$this->assertCount( 2, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
			new PageRestriction( $block->getId(), $pageSaturn->getId() ),
		] ) );

		// Remove a page from the partial block.
		$data['PageRestrictions'] = $pageMars->getTitle()->getText();
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertTrue( $block->isCreateAccountBlocked() );
		$this->assertCount( 1, $block->getRestrictions() );
		$this->assertTrue( $this->getBlockRestrictionStore()->equals( $block->getRestrictions(), [
			new PageRestriction( $block->getId(), $pageMars->getId() ),
		] ) );

		// Remove the last page from the block.
		$data['PageRestrictions'] = '';
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertFalse( $block->isSitewide() );
		$this->assertTrue( $block->isCreateAccountBlocked() );
		$this->assertSame( [], $block->getRestrictions() );

		// Change to sitewide.
		$data['EditingRestriction'] = 'sitewide';
		$result = $page->processForm( $data, $context );

		$this->assertTrue( $result );

		$block = $this->blockStore->newFromTarget( $badActor );
		$this->assertSame( $reason, $block->getReasonComment()->text );
		$this->assertSame( $expiry, $block->getExpiry() );
		$this->assertTrue( $block->isSitewide() );
		$this->assertSame( [], $block->getRestrictions() );

		// Ensure that there are no restrictions where the blockId is 0.
		$count = $this->db->newSelectQueryBuilder()
			->select( '*' )
			->from( 'ipblocks_restrictions' )
			->where( [ 'ir_ipb_id' => 0 ] )
			->caller( __METHOD__ )->fetchRowCount();
		$this->assertSame( 0, $count );
	}

	/**
	 * @dataProvider provideProcessFormUserTalkEditFlag
	 * @covers ::processForm
	 */
	public function testProcessFormUserTalkEditFlag( $options, $expected ) {
		$this->overrideConfigValue( MainConfigNames::BlockAllowsUTEdit, $options['configAllowsUserTalkEdit'] );

		$performer = $this->getTestSysop()->getUser();
		$target = $this->getTestUser()->getUser();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$data = [
			'Target' => $target,
			'PreviousTarget' => $target,
			'Expiry' => 'infinity',
			'CreateAccount' => '1',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '0',
			'Watch' => '0',
			'Confirm' => '1',
			'DisableUTEdit' => $options['optionBlocksUserTalkEdit'],
		];

		if ( !$options['userTalkNamespaceBlocked'] ) {
			$data['EditingRestriction'] = 'partial';
			$data['PageRestrictions'] = '';
			$data['NamespaceRestrictions'] = '';
		}

		$result = $this->newSpecialPage()->processForm(
			$data,
			$context
		);

		if ( is_string( $expected ) ) {
			$this->assertStatusError( $expected, $result );
		} else {
			$block = $this->blockStore->newFromTarget( $target );
			$this->assertSame( $expected, $block->isUsertalkEditAllowed() );
		}
	}

	/**
	 * Test cases for whether own user talk edit is allowed, with different combinations of:
	 * - whether user talk namespace blocked
	 * - config BlockAllowsUTEdit true/false
	 * - block option specifying whether to block own user talk edit
	 * For more about the desired behaviour, see T252892.
	 *
	 * @return array
	 */
	public static function provideProcessFormUserTalkEditFlag() {
		return [
			'Always allowed if user talk namespace not blocked' => [
				[
					'userTalkNamespaceBlocked' => false,
					'configAllowsUserTalkEdit' => true,
					'optionBlocksUserTalkEdit' => false,
				],
				true,
			],
			'Always allowed if user talk namespace not blocked (config is false)' => [
				[
					'userTalkNamespaceBlocked' => false,
					'configAllowsUserTalkEdit' => false,
					'optionBlocksUserTalkEdit' => false,
				],
				true,
			],
			'Error if user talk namespace not blocked, but option blocks user talk edit' => [
				[
					'userTalkNamespaceBlocked' => false,
					'configAllowsUserTalkEdit' => true,
					'optionBlocksUserTalkEdit' => true,
				],
				'ipb-prevent-user-talk-edit',
			],
			'Always blocked if user talk namespace blocked and wgBlockAllowsUTEdit is false' => [
				[
					'userTalkNamespaceBlocked' => true,
					'configAllowsUserTalkEdit' => false,
					'optionBlocksUserTalkEdit' => false,
				],
				false,
			],
			'Option used if user talk namespace blocked and config is true (blocked)' => [
				[
					'userTalkNamespaceBlocked' => true,
					'configAllowsUserTalkEdit' => true,
					'optionBlocksUserTalkEdit' => true,
				],
				false,
			],
			'Option used if user talk namespace blocked and config is true (not blocked)' => [
				[
					'userTalkNamespaceBlocked' => true,
					'configAllowsUserTalkEdit' => true,
					'optionBlocksUserTalkEdit' => false,
				],
				true,
			],
		];
	}

	/**
	 * @dataProvider provideProcessFormErrors
	 * @covers ::processForm
	 */
	public function testProcessFormErrors( $data, $expected, $options = [] ) {
		$this->overrideConfigValue( MainConfigNames::BlockAllowsUTEdit, true );

		$performer = $this->getTestSysop()->getUser();
		$target = !empty( $options['blockingSelf'] ) ? $performer : '1.2.3.4';
		$defaultData = [
			'Target' => $target,
			'PreviousTarget' => $target,
			'Expiry' => 'infinity',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '0',
			'Confirm' => '0',
			'Watch' => '0',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		if ( $result instanceof Status ) {
			$this->assertStatusMessage( $expected, $result );
		} else {
			$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
			$this->assertEquals( $expected, $error );
		}
	}

	public static function provideProcessFormErrors() {
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
			'Edit user talk page invalid with namespace restriction !== NS_USER_TALK ' => [
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
	 * @covers ::processForm
	 */
	public function testProcessFormErrorsReblock( $data, $permissions, $expected ) {
		$this->overrideConfigValue( MainConfigNames::BlockAllowsUTEdit, true );

		$performer = $this->getTestSysop()->getUser();
		$this->overrideUserPermissions( $performer, $permissions );
		$blockedUser = $this->getTestUser()->getUser();

		$block = new DatabaseBlock( [
			'address' => $blockedUser,
			'by' => $performer,
			'hideName' => true,
		] );
		$this->blockStore->insertBlock( $block );

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
			'Watch' => '0',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		if ( $result instanceof Status ) {
			$this->assertStatusMessage( $expected, $result );
		} else {
			$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
			$this->assertEquals( $expected, $error );
		}
	}

	public static function provideProcessFormErrorsReblock() {
		return [
			'Reblock user with Confirm false' => [
				[
					// Avoid error for hiding user with confirm false
					'HideUser' => '0',
					'Confirm' => '0',
				],
				[ 'block', 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock user with Reblock false' => [
				[ 'Reblock' => '0' ],
				[ 'block', 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock with confirm True but target has changed' => [
				[ 'PreviousTarget' => '1.2.3.4' ],
				[ 'block', 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock with same block' => [
				[ 'HideUser' => '1' ],
				[ 'block', 'hideuser' ],
				'ipb_already_blocked',
			],
			'Reblock hidden user with wrong permissions' => [
				[ 'HideUser' => '0' ],
				[ 'block', 'hideuser' => false ],
				'cant-see-hidden-user',
			],
		];
	}

	/**
	 * @dataProvider provideProcessFormErrorsHideUser
	 * @covers ::processForm
	 */
	public function testProcessFormErrorsHideUser( $data, $permissions, $expected ) {
		$performer = $this->getTestSysop()->getUser();
		$this->overrideUserPermissions( $performer, array_merge( $permissions, [ 'block' ] ) );

		$defaultData = [
			'Target' => $this->getTestUser()->getUser(),
			'HideUser' => '1',
			'Expiry' => 'infinity',
			'Confirm' => '1',
			'CreateAccount' => '0',
			'DisableUTEdit' => '0',
			'DisableEmail' => '0',
			'HardBlock' => '0',
			'AutoBlock' => '0',
			'Watch' => '0',
		];

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$result = $this->newSpecialPage()->processForm(
			array_merge( $defaultData, $data ),
			$context
		);

		if ( $result instanceof Status ) {
			$this->assertStatusMessage( $expected, $result );
		} else {
			$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
			$this->assertEquals( $expected, $error );
		}
	}

	public static function provideProcessFormErrorsHideUser() {
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
	 * @covers ::processForm
	 */
	public function testProcessFormErrorsHideUserProlific() {
		$this->overrideConfigValue( MainConfigNames::HideUserContribLimit, 0 );

		$performer = $this->mockRegisteredUltimateAuthority();
		$userToBlock = $this->getTestUser()->getUser();
		$pageSaturn = $this->getExistingTestPage( 'Saturn' );
		$pageSaturn->doUserEditContent(
			ContentHandler::makeContent( 'content', $pageSaturn->getTitle() ),
			$userToBlock,
			'summary'
		);

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setAuthority( $performer );

		$result = $this->newSpecialPage()->processForm(
			[
				'Target' => $userToBlock,
				'CreateAccount' => '1',
				'HideUser' => '1',
				'Expiry' => 'infinity',
				'Confirm' => '1',
				'DisableUTEdit' => '0',
				'DisableEmail' => '0',
				'HardBlock' => '0',
				'AutoBlock' => '0',
				'Watch' => '0',
			],
			$context
		);

		if ( $result instanceof Status ) {
			$this->assertStatusMessage( 'ipb_hide_invalid', $result );
		} else {
			$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
			$this->assertEquals( 'ipb_hide_invalid', $error );
		}
	}

	/**
	 * @dataProvider provideGetTargetAndType
	 * @covers ::getTargetAndTypeInternal
	 */
	public function testGetTargetAndType( $par, $requestData, $expectedTarget ) {
		$request = $requestData ? new FauxRequest( $requestData ) : null;
		/** @var SpecialBlock $page */
		$page = TestingAccessWrapper::newFromObject( $this->newSpecialPage() );
		[ $target, $type ] = $page->getTargetAndTypeInternal( $par, $request );
		$this->assertSame( $expectedTarget, $target );
	}

	public static function provideGetTargetAndType() {
		$invalidTarget = '';
		return [
			'Choose \'wpTarget\' parameter first' => [
				'2.2.2.0/24',
				[
					'wpTarget' => '1.1.1.0/24',
					'ip' => '3.3.3.0/24',
					'wpBlockAddress' => '4.4.4.0/24',
				],
				'1.1.1.0/24',
			],
			'Choose subpage parameter second' => [
				'2.2.2.0/24',
				[
					'wpTarget' => $invalidTarget,
					'ip' => '3.3.3.0/24',
					'wpBlockAddress' => '4.4.4.0/24',
				],
				'2.2.2.0/24',
			],
			'Choose \'ip\' parameter third' => [
				$invalidTarget,
				[
					'wpTarget' => $invalidTarget,
					'ip' => '3.3.3.0/24',
					'wpBlockAddress' => '4.4.4.0/24',
				],
				'3.3.3.0/24',
			],
			'Choose \'wpBlockAddress\' parameter fourth' => [
				$invalidTarget,
				[
					'wpTarget' => $invalidTarget,
					'ip' => $invalidTarget,
					'wpBlockAddress' => '4.4.4.0/24',
				],
				'4.4.4.0/24',
			],
			'No web request' => [
				'2.2.2.0/24',
				false,
				'2.2.2.0/24',
			],
			'No valid request data or subpage parameter' => [
				null,
				[],
				null,
			],
		];
	}

	protected function insertBlock() {
		$badActor = $this->getTestUser()->getUser();
		$sysop = $this->getTestSysop()->getUser();

		$block = new DatabaseBlock( [
			'address' => $badActor,
			'by' => $sysop,
			'expiry' => 'infinity',
			'sitewide' => 1,
			'enableAutoblock' => true,
		] );

		$this->blockStore->insertBlock( $block );

		return $block;
	}

	/**
	 * Get a BlockRestrictionStore instance
	 *
	 * @return BlockRestrictionStore
	 */
	private function getBlockRestrictionStore(): BlockRestrictionStore {
		$dbProvider = $this->createMock( IConnectionProvider::class );

		return new BlockRestrictionStore( $dbProvider );
	}
}
