<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\EditResult;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Storage\EditResult
 */
class EditResultTest extends MediaWikiUnitTestCase {

	public function provideIsRevertEditResults() {
		return [
			'a new page' => [
				new EditResult(
					true,
					false,
					null,
					null,
					null,
					false,
					false,
					[]
				),
				false
			],
			'a regular edit, not a revert' => [
				new EditResult(
					false,
					false,
					null,
					null,
					null,
					false,
					false,
					[]
				),
				false
			],
			'a revert' => [
				new EditResult(
					false,
					1,
					EditResult::REVERT_ROLLBACK,
					2,
					2,
					true,
					false,
					[ 'mw-rollback' ]
				),
				true
			]
		];
	}

	/**
	 * @covers \MediaWiki\Storage\EditResult::isRevert()
	 * @dataProvider provideIsRevertEditResults
	 * @param EditResult $er
	 * @param bool $isRevert
	 */
	public function testIsRevert( EditResult $er, bool $isRevert ) {
		$this->assertSame( $isRevert, $er->isRevert(), 'isRevert()' );
	}

	public function provideGetRevertMethodEditResults() {
		return [
			'an undo' => [
				new EditResult(
					false,
					false,
					EditResult::REVERT_UNDO,
					1,
					1,
					false,
					false,
					[ 'mw-undo' ]
				),
				EditResult::REVERT_UNDO,
				[ 'mw-undo' ]
			],
			'a rollback' => [
				new EditResult(
					false,
					false,
					EditResult::REVERT_ROLLBACK,
					1,
					1,
					false,
					false,
					[ 'mw-rollback' ]
				),
				EditResult::REVERT_ROLLBACK,
				[ 'mw-rollback' ]
			],
			'not a revert' => [
				new EditResult(
					false,
					false,
					null,
					null,
					null,
					false,
					false,
					[]
				),
				null,
				[]
			],
			'manual revert without an explicit revert mode' => [
				new EditResult(
					false,
					false,
					EditResult::REVERT_MANUAL,
					1,
					1,
					false,
					false,
					[]
				),
				EditResult::REVERT_MANUAL,
				[]
			]
		];
	}

	/**
	 * @covers       \MediaWiki\Storage\EditResult::getRevertMethod()
	 * @covers       \MediaWiki\Storage\EditResult::getRevertTags()
	 * @dataProvider provideGetRevertMethodEditResults
	 *
	 * @param EditResult $er
	 * @param int|null $revertMethod
	 * @param array $tags
	 */
	public function testGetRevertMethodAndTags( EditResult $er, ?int $revertMethod, array $tags ) {
		$this->assertSame( $revertMethod, $er->getRevertMethod(), 'getRevertMethod()' );
		$this->assertArrayEquals( $tags, $er->getRevertTags(), 'getRevertTags()' );
	}

	public function provideGetUndidRevIdEditResults() {
		return [
			// an undo, should return the oldest undid revision ID
			'an undo' => [
				new EditResult(
					false,
					false,
					EditResult::REVERT_UNDO,
					123,
					125,
					false,
					false,
					[ 'mw-undo' ]
				),
				123
			],
			// a revert, but not an undo, should return 0
			'a rollback' => [
				new EditResult(
					false,
					100,
					EditResult::REVERT_ROLLBACK,
					123,
					123,
					true,
					false,
					[ 'mw-rollback' ]
				),
				0
			],
			'not a revert' => [
				new EditResult(
					false,
					false,
					null,
					null,
					null,
					false,
					false,
					[]
				),
				0
			]
		];
	}

	/**
	 * @covers \MediaWiki\Storage\EditResult::getUndidRevId()
	 * @dataProvider provideGetUndidRevIdEditResults
	 * @param EditResult $er
	 * @param int|null $undidRevId
	 */
	public function testGetUndidRevId( EditResult $er, ?int $undidRevId ) {
		$this->assertSame( $undidRevId, $er->getUndidRevId(), 'getUndidRevId()' );
	}
}
