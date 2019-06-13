<?php

/**
 * @covers MWCallableUpdate
 */
class MWCallableUpdateTest extends PHPUnit\Framework\TestCase {

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

		$update->doUpdate();

		// Ensure it was cancelled
		$this->assertSame( 0, $ran );
	}

	public function testCancelSome() {
		// Prepare update and DB
		$db1 = new DatabaseTestHelper( __METHOD__ );
		$db1->begin( __METHOD__ );
		$db2 = new DatabaseTestHelper( __METHOD__ );
		$db2->begin( __METHOD__ );
		$ran = 0;
		$update = new MWCallableUpdate( function () use ( &$ran ) {
			$ran++;
		}, __METHOD__, [ $db1, $db2 ] );

		// Emulate rollback
		$db1->rollback( __METHOD__ );

		$update->doUpdate();

		// Prevents: "Notice: DB transaction writes or callbacks still pending"
		$db2->rollback( __METHOD__ );

		// Ensure it was cancelled
		$this->assertSame( 0, $ran );
	}

	public function testCancelAll() {
		// Prepare update and DB
		$db1 = new DatabaseTestHelper( __METHOD__ );
		$db1->begin( __METHOD__ );
		$db2 = new DatabaseTestHelper( __METHOD__ );
		$db2->begin( __METHOD__ );
		$ran = 0;
		$update = new MWCallableUpdate( function () use ( &$ran ) {
			$ran++;
		}, __METHOD__, [ $db1, $db2 ] );

		// Emulate rollbacks
		$db1->rollback( __METHOD__ );
		$db2->rollback( __METHOD__ );

		$update->doUpdate();

		// Ensure it was cancelled
		$this->assertSame( 0, $ran );
	}

}
