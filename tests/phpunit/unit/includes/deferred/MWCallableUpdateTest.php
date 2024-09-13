<?php

use MediaWiki\Deferred\MWCallableUpdate;

/**
 * @covers \MediaWiki\Deferred\MWCallableUpdate
 */
class MWCallableUpdateTest extends MediaWikiUnitTestCase {
	/** @var int */
	private $callbackMethodRan = 0;

	public function testDoUpdate() {
		$ran = 0;
		$ranBy = '';
		$caller = 'caller_name';
		$update = new MWCallableUpdate(
			static function ( $fname ) use ( &$ran, &$ranBy ) {
				$ran++;
				$ranBy = $fname;
			},
			$caller
		);
		$this->assertSame( 0, $ran );
		$this->assertSame( '', $ranBy );
		$update->doUpdate();
		$this->assertSame( 1, $ran );
		$this->assertSame( $caller, $ranBy );

		$this->callbackMethodRan = 0;
		$update = new MWCallableUpdate(
			[ $this, 'callbackMethodWithNoArgs' ],
			$caller
		);
		$this->assertSame( 0, $this->callbackMethodRan );
		$update->doUpdate();
		$this->assertSame( 1, $this->callbackMethodRan );
	}

	public function testCancel() {
		// Prepare update and DB
		$db = new DatabaseTestHelper( __METHOD__ );
		$db->begin( __METHOD__ );
		$ran = 0;
		$update = new MWCallableUpdate( static function () use ( &$ran ) {
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
		$update = new MWCallableUpdate( static function () use ( &$ran ) {
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
		$update = new MWCallableUpdate( static function () use ( &$ran ) {
			$ran++;
		}, __METHOD__, [ $db1, $db2 ] );

		// Emulate rollbacks
		$db1->rollback( __METHOD__ );
		$db2->rollback( __METHOD__ );

		$update->doUpdate();

		// Ensure it was cancelled
		$this->assertSame( 0, $ran );
	}

	public function callbackMethodWithNoArgs() {
		$this->callbackMethodRan++;
		$this->assertSame( 0, func_num_args() );
	}
}
