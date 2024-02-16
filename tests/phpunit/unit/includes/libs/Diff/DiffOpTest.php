<?php

namespace Wikimedia\Tests\Diff;

use MediaWikiUnitTestCase;

/**
 * @author Addshore
 *
 * @group Diff
 */
class DiffOpTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \Wikimedia\Diff\DiffOp::getType
	 */
	public function testGetType() {
		$obj = new FakeDiffOp();
		$obj->type = 'foo';
		$this->assertSame( 'foo', $obj->getType() );
	}

	/**
	 * @covers \Wikimedia\Diff\DiffOp::getOrig
	 */
	public function testGetOrig() {
		$obj = new FakeDiffOp();
		$obj->orig = [ 'foo' ];
		$this->assertSame( [ 'foo' ], $obj->getOrig() );
	}

	/**
	 * @covers \Wikimedia\Diff\DiffOp::getClosing
	 */
	public function testGetClosing() {
		$obj = new FakeDiffOp();
		$obj->closing = [ 'foo' ];
		$this->assertSame( [ 'foo' ], $obj->getClosing() );
	}

	/**
	 * @covers \Wikimedia\Diff\DiffOp::getClosing
	 */
	public function testGetClosingWithParameter() {
		$obj = new FakeDiffOp();
		$obj->closing = [ 'foo', 'bar', 'baz' ];
		$this->assertSame( 'foo', $obj->getClosing( 0 ) );
		$this->assertSame( 'bar', $obj->getClosing( 1 ) );
		$this->assertSame( 'baz', $obj->getClosing( 2 ) );
		$this->assertNull( $obj->getClosing( 3 ) );
	}

	/**
	 * @covers \Wikimedia\Diff\DiffOp::norig
	 */
	public function testNorig() {
		$obj = new FakeDiffOp();
		$this->assertSame( 0, $obj->norig() );
		$obj->orig = [ 'foo' ];
		$this->assertSame( 1, $obj->norig() );
	}

	/**
	 * @covers \Wikimedia\Diff\DiffOp::nclosing
	 */
	public function testNclosing() {
		$obj = new FakeDiffOp();
		$this->assertSame( 0, $obj->nclosing() );
		$obj->closing = [ 'foo' ];
		$this->assertSame( 1, $obj->nclosing() );
	}

}
