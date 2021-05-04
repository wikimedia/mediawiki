<?php

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\HookContainer\HookContainer;

/**
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\BlockActionInfo
 */
class BlockActionInfoTest extends MediaWikiUnitTestCase {
	/** @var HookContainer */
	private $hookContainer;

	protected function setUp(): void {
		parent::setUp();
		$this->hookContainer = $this->createHookContainer();
	}

	/**
	 * @covers ::getAllBlockActions
	 */
	public function testAddBlockAction() {
		$this->hookContainer->register(
			'GetAllBlockActions',
			static function ( array &$actions ) {
				$actions[ 'test' ] = 100;
			}
		);
		$blockActionInfo = new BlockActionInfo( $this->hookContainer );
		$blockActions = $blockActionInfo->getAllBlockActions();

		// Confirm new action is added
		$this->assertContains( 100, $blockActions );
	}

	/**
	 * @dataProvider provideGetIdFromAction
	 * @covers ::getIdFromAction
	 */
	public function testGetIdFromAction( $action, $expected ) {
		$blockActionInfo = new BlockActionInfo( $this->createMock( HookContainer::class ) );
		$this->assertSame(
			$expected,
			$blockActionInfo->getIdFromAction( $action )
		);
	}

	public static function provideGetIdFromAction() {
		return [
			'Valid action' => [ 'upload', 1 ],
			'Invalid action' => [ 'invalidaction', false ],
		];
	}

}
