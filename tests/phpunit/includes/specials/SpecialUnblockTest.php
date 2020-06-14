<?php

use MediaWiki\Block\DatabaseBlock;
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
		return new SpecialUnblock();
	}

	protected function tearDown() : void {
		$this->db->delete( 'ipblocks', '*', __METHOD__ );
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
			'by' => $this->getTestSysop()->getUser()->getId(),
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
	 * @covers ::processUnblock()
	 */
	public function testProcessUnblockErrors( $options, $expected ) {
		$performer = $this->getTestSysop()->getUser();

		$target = '1.1.1.1';
		if ( !empty( $options['block'] ) ) {
			$block = new DatabaseBlock( [
				'address' => $target,
				'by' => $performer->getId(),
				'hideName' => true,
			] );
			$block->insert();
		}

		if ( !empty( $options['readOnly'] ) ) {
			$this->setMwGlobals( [ 'wgReadOnly' => true ] );
		}

		if ( isset( $options['permissions'] ) ) {
			$this->overrideUserPermissions( $performer, $options['permissions'] );
		}

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );
		$result = $this->newSpecialPage()->processUnblock(
			[ 'Target' => $target ],
			$context
		);

		$error = is_array( $result[0] ) ? $result[0][0] : $result[0];
		$this->assertSame( $expected, $error );
	}

	public function provideProcessUnblockErrors() {
		return [
			'Target is not blocked' => [
				[
					'permissions' => [ 'hideuser' ],
				],
				'ipb_cant_unblock',
			],
			'Wrong permissions for unhiding user' => [
				[
					'block' => true,
					'permissions' => [ 'hideuser' => false ],
				],
				'unblock-hideuser',
			],
			'Delete block failed' => [
				[
					'block' => true,
					'permissions' => [ 'hideuser' ],
					'readOnly' => true,
				],
				'ipb_cant_unblock',
			],
		];
	}

	/**
	 * @covers ::processUnblock()
	 */
	public function testProcessUnblockErrorsUnblockSelf() {
		$performer = $this->getTestSysop()->getUser();

		$this->overrideUserPermissions( $performer, [ 'unblockself' => false ] );

		// Blocker must be different user for unblock self to be disallowed
		$blocker = $this->getTestUser()->getUser();
		$block = new DatabaseBlock( [
			'by' => $blocker->getId(),
			'address' => $performer,
		] );
		$block->insert();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setUser( $performer );

		$this->expectException( ErrorPageError::class );
		$result = $this->newSpecialPage()->processUnblock(
			[ 'Target' => $performer ],
			$context
		);
	}
}
