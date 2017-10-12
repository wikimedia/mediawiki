<?php

/**
 * @covers OuterScopeUpdate
 */
class OuterScopeUpdateTest extends PHPUnit_Framework_TestCase {

	public function testDoUpdate() {
		$ran = 0;
		$update = new OuterScopeUpdate( function () use ( &$ran ) {
			$ran++;
		} );
		$this->assertSame( 0, $ran );
		$update->doUpdate();
		$this->assertSame( 1, $ran );
	}
}
