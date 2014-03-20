<?php

/**
 * @covers ReadOnlyError
 * @author Adam Shorland
 */
class ReadOnlyErrorTest extends MediaWikiTestCase {

	public function testConstruction() {
		$e = new ReadOnlyError();
		$this->assertEquals( 'readonly', $e->title );
		$this->assertEquals( 'readonlytext', $e->msg );
		$this->assertEquals( wfReadOnlyReason() ?: array(), $e->params );
	}

}
