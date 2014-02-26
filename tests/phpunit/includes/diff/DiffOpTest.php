<?php

//Load our FakeDiffOp
require_once( __DIR__ . DIRECTORY_SEPARATOR . 'FakeDiffOp.php' );

/**
 * @covers DiffOp
 * @licence GNU GPL v2+
 * @author Adam Shorland
 */
class DiffOpTest extends MediaWikiTestCase {

	public function testGetType() {
		$obj = new FakeDiffOp();
		$obj->type = 'foo';
		$this->assertEquals( 'foo', $obj->getType() );
	}

	public function testGetOrig() {
		$obj = new FakeDiffOp();
		$obj->orig = array( 'foo' );
		$this->assertEquals( array( 'foo' ), $obj->getOrig() );
	}

	public function testGetClosing() {
		$obj = new FakeDiffOp();
		$obj->closing = array( 'foo' );
		$this->assertEquals( array( 'foo' ), $obj->getClosing() );
	}

	public function testGetClosingWithParameter() {
		$obj = new FakeDiffOp();
		$obj->closing = array( 'foo', 'bar', 'baz' );
		$this->assertEquals( 'foo' , $obj->getClosing( 0 ) );
		$this->assertEquals( 'bar' , $obj->getClosing( 1 ) );
		$this->assertEquals( 'baz' , $obj->getClosing( 2 ) );
		$this->assertEquals( null , $obj->getClosing( 3 ) );
	}

	public function testNorig() {
		$obj = new FakeDiffOp();
		$this->assertEquals( 0, $obj->norig() );
		$obj->orig = array( 'foo' );
		$this->assertEquals( 1, $obj->norig() );
	}

	public function testNclosing() {
		$obj = new FakeDiffOp();
		$this->assertEquals( 0, $obj->nclosing() );
		$obj->closing = array( 'foo' );
		$this->assertEquals( 1, $obj->nclosing() );
	}

}
