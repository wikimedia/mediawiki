<?php

//Load our FakeDiffOp
require_once __DIR__ . DIRECTORY_SEPARATOR . 'FakeDiffOp.php';

/**
 * @licence GNU GPL v2+
 * @author Adam Shorland
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
		$obj->orig = array( 'foo' );
		$this->assertEquals( array( 'foo' ), $obj->getOrig() );
	}

	/**
	 * @covers DiffOp::getClosing
	 */
	public function testGetClosing() {
		$obj = new FakeDiffOp();
		$obj->closing = array( 'foo' );
		$this->assertEquals( array( 'foo' ), $obj->getClosing() );
	}

	/**
	 * @covers DiffOp::getClosing
	 */
	public function testGetClosingWithParameter() {
		$obj = new FakeDiffOp();
		$obj->closing = array( 'foo', 'bar', 'baz' );
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
		$obj->orig = array( 'foo' );
		$this->assertEquals( 1, $obj->norig() );
	}

	/**
	 * @covers DiffOp::nclosing
	 */
	public function testNclosing() {
		$obj = new FakeDiffOp();
		$this->assertEquals( 0, $obj->nclosing() );
		$obj->closing = array( 'foo' );
		$this->assertEquals( 1, $obj->nclosing() );
	}

}
