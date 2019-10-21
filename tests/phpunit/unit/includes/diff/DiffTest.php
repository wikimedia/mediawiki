<?php

/**
 * @author Addshore
 *
 * @group Diff
 */
class DiffTest extends \MediaWikiUnitTestCase {

	/**
	 * @covers Diff::getEdits
	 */
	public function testGetEdits() {
		$obj = new Diff( [], [] );
		$obj->edits = 'FooBarBaz';
		$this->assertEquals( 'FooBarBaz', $obj->getEdits() );
	}

}
