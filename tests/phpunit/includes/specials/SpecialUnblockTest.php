<?php

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\MainConfigNames;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Blocking
 * @group Database
 * @coversDefaultClass SpecialUnblock
 */
class SpecialUnblockTest extends SpecialPageTestBase {
	/**
	 * @inheritDoc
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialUnblock(
			$services->getUnblockUserFactory(),
			$services->getBlockUtils(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getSpecialPageFactory()
		);
	}

	protected function tearDown(): void {
		$this->db->delete( 'ipblocks', '*', __METHOD__ );
		parent::tearDown();
	}

	/**
	 * @dataProvider provideGetFields
	 * @covers ::getFields()
	 */
	public function testGetFields( $target, $expected ) {
		$page = TestingAccessWrapper::newFromObject( $this->newSpecialPage() );
		$page->target = $target;
		$page->block = new DatabaseBlock( [
			'address' => '1.2.3.4',
			'by' => $this->getTestSysop()->getUser(),
		] );

		$fields = $page->getFields();
		$this->assertIsArray( $fields );
		foreach ( $expected as $fieldName ) {
			$this->assertArrayHasKey( $fieldName, $fields );
		}
	}

	public function provideGetFields() {
		return [
			'No target specified' => [
				'',
				[ 'Target', 'Reason' ],
			],
			'Target is not blocked' => [
				'1.1.1.1',
				[ 'Target', 'Reason' ],
			],
			'Target is blocked' => [
				'1.2.3.4',
				[ 'Target', 'Reason', 'Name' ],
			],
		];
	}

	/**
	 * @dataProvider provideProcessUnblockErrors
	 * @covers ::execute()
	 */
	public function testProcessUnblockErrors( $options, $expected ) {
		$performer = $this->getTestSysop()->getUser();

		$target = '1.1.1.1';
		if ( !empty( $options['block'] ) ) {
			$block = new DatabaseBlock( [
				'address' => $target,
				'by' => $performer,
				'hideName' => true,
			] );
			$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );
		}

		if ( !empty( $options['readOnly'] ) ) {
			$this->overrideConfigValue( MainConfigNames::ReadOnly, true );
			$this->expectException( ReadOnlyError::class );
		}

		if ( isset( $options['permissions'] ) ) {
			$this->overrideUserPermissions( $performer, $options['permissions'] );
		}

		$request = new FauxRequest( [
			'wpTarget' => $target,
			'wpReason' => '',
		], true );
		list( $html, ) = $this->executeSpecialPage( '', $request, 'qqx', $performer );

		$this->assertStringContainsString( $expected, $html );
	}

	public function provideProcessUnblockErrors() {
		return [
			'Target is not blocked' => [
				[
					'permissions' => [ 'block', 'hideuser' => true ],
				],
				'ipb_cant_unblock',
			],
			'Wrong permissions for unhiding user' => [
				[
					'block' => true,
					'permissions' => [ 'block', 'hideuser' => false ],
				],
				'unblock-hideuser',
			],
			'Delete block failed' => [
				[
					'block' => true,
					'permissions' => [ 'block', 'hideuser' ],
					'readOnly' => true,
				],
				'ipb_cant_unblock',
			],
		];
	}

	/**
	 * @covers ::execute()
	 */
	public function testProcessUnblockErrorsUnblockSelf() {
		$performer = $this->getTestSysop()->getUser();

		$this->overrideUserPermissions( $performer, [ 'block', 'unblockself' => false ] );

		// Blocker must be different user for unblock self to be disallowed
		$blocker = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'by' => $blocker,
			'address' => $performer,
		] );
		$this->getServiceContainer()->getDatabaseBlockStore()->insertBlock( $block );

		$request = new FauxRequest( [
			'wpTarget' => $performer->getName(),
			'wpReason' => '',
		], true );
		list( $html, ) = $this->executeSpecialPage( '', $request, 'qqx', $performer );

		$this->assertStringContainsString( 'ipbnounblockself', $html );
	}
}
