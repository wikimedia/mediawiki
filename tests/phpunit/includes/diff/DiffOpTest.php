<?php
/**
 * @author Addshore
 *
 * @group Diff
 */
class DiffOpTest extends MediaWikiTestCase {

	/**
	 * @covers DiffOp::getType
	 */
	public function testGetType() {
		$obj = new FakeDiffOp();
		$obj->type = 'foo';
		$this->assertEquals( 'foo', $obj->getType() );
	}

	/**
	 * @covers DiffOp::getOrig
	 */
	public function testGetOrig() {
		$obj = new FakeDiffOp();
		$obj->orig = [ 'foo' ];
		$this->assertEquals( [ 'foo' ], $obj->getOrig() );
	}

	/**
	 * @covers DiffOp::getClosing
	 */
	public function testGetClosing() {
		$obj = new FakeDiffOp();
		$obj->closing = [ 'foo' ];
		$this->assertEquals( [ 'foo' ], $obj->getClosing() );
	}

	/**
	 * @covers DiffOp::getClosing
	 */
	public function testGetClosingWithParameter() {
		$obj = new FakeDiffOp();
		$obj->closing = [ 'foo', 'bar', 'baz' ];
		$this->assertEquals( 'foo', $obj->getClosing( 0 ) );
		$this->assertEquals( 'bar', $obj->getClosing( 1 ) );
		$this->assertEquals( 'baz', $obj->getClosing( 2 ) );
		$this->assertEquals( null, $obj->getClosing( 3 ) );
	}

	/**
	 * @covers DiffOp::norig
	 */
	public function testNorig() {
		$obj = new FakeDiffOp();
		$this->assertEquals( 0, $obj->norig() );
		$obj->orig = [ 'foo' ];
		$this->assertEquals( 1, $obj->norig() );
	}

	/**
	 * @covers DiffOp::nclosing
	 */
	public function testNclosing() {
		$obj = new FakeDiffOp();
		$this->assertEquals( 0, $obj->nclosing() );
		$obj->closing = [ 'foo' ];
		$this->assertEquals( 1, $obj->nclosing() );
	}

}
