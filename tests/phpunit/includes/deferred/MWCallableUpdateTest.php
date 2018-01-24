<?php

/**
 * @covers MWCallableUpdate
 */
class MWCallableUpdateTest extends PHPUnit_Framework_TestCase {

	use MediaWikiCoversValidator;

	public function testDoUpdate() {
		$ran = 0;
		$update = new MWCallableUpdate( function () use ( &$ran ) {
			$ran++;
		} );
		$this->assertSame( 0, $ran );
		$update->doUpdate();
		$this->assertSame( 1, $ran );
	}

	public function testCancel() {
		// Prepare update and DB
		$db = new DatabaseTestHelper( __METHOD__ );
		$db->begin( __METHOD__ );
		$ran = 0;
		$update = new MWCallableUpdate( function () use ( &$ran ) {
			$ran++;
		}, __METHOD__, $db );

		// Emulate rollback
		$db->rollback( __METHOD__ );

		// Ensure it was cancelled
		$update->doUpdate();
		$this->assertSame( 0, $ran );
	}
}
