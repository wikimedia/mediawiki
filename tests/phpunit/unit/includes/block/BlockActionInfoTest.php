<?php

use MediaWiki\Block\BlockActionInfo;

/**
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\BlockActionInfo
 */
class BlockActionInfoTest extends MediaWikiUnitTestCase {
	/** @var BlockActionInfo */
	private $blockActionInfo;

	protected function setUp(): void {
		parent::setUp();
		$this->blockActionInfo = new BlockActionInfo(
			$this->createHookContainer( [
				'GetAllBlockActions' => static function ( array &$actions ) {
					$actions[ 'test' ] = 100;
				},
			] )
		);
	}

	/**
	 * @covers ::getAllBlockActions
	 */
	public function testAddBlockAction() {
		// 'test' action in added by hook with id 100
		$blockActions = $this->blockActionInfo->getAllBlockActions();

		// Confirm new action is added
		$this->assertArrayHasKey( 'test', $blockActions, 'Action added via hook' );
		$this->assertSame( 100, $blockActions['test'], 'Id of action added via hook' );
	}

	/**
	 * @dataProvider provideIdAndAction
	 * @param string|false $action False if the id is invalid
	 * @param int|false $id False if the action is invalid
	 * @covers ::getIdFromAction
	 * @covers ::getActionFromId
	 */
	public function testIdAndAction( $action, $id ) {
		if ( $id !== false ) {
			$this->assertSame( $action, $this->blockActionInfo->getActionFromId( $id ) );
		}
		if ( $action !== false ) {
			$this->assertSame( $id, $this->blockActionInfo->getIdFromAction( $action ) );
		}
	}

	public static function provideIdAndAction() {
		return [
			'Valid core action' => [ 'upload', 1 ],
			'Valid hook action' => [ 'test', 100 ],
			'Invalid action name' => [ 'invalid', false ],
			'Invalid action id' => [ false, 200 ],
		];
	}

}
