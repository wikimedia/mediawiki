<?php

namespace Wikimedia\Tests\Diff;

use MediaWikiUnitTestCase;
use Wikimedia\Diff\Diff;

/**
 * @author Addshore
 *
 * @group Diff
 */
class DiffTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \Wikimedia\Diff\Diff::getEdits
	 */
	public function testGetEdits() {
		$obj = new Diff( [], [] );
		$obj->edits = 'FooBarBaz';
		$this->assertEquals( 'FooBarBaz', $obj->getEdits() );
	}

}
