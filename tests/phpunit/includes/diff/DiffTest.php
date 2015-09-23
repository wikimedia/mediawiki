<?php

/**
 * @author Adam Shorland
 *
 * @group Diff
 */
class DiffTest extends MediaWikiTestCase {

	/**
	 * @covers Diff::getEdits
	 */
	public function testGetEdits() {
		$obj = new Diff( array(), array() );
		$obj->edits = 'FooBarBaz';
		$this->assertEquals( 'FooBarBaz', $obj->getEdits() );
	}

}
