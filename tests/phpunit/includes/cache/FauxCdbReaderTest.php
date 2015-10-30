<?php

/**
 * @group Cache
 * @covers MediaWiki\Cache\FauxCdbReader
 */
class FauxCdbReaderTest extends \PHPUnit_Framework_TestCase {

	public function testConstructor_fail() {
		$this->fail( 'TestMe' );
	}

	public function testClose() {
		$this->fail( 'TestMe' );
	}

	public function testGet() {
		$this->fail( 'TestMe' );
	}

	public function testExists() {
		$this->fail( 'TestMe' );
	}

	public function testFirstKey() {
		$this->fail( 'TestMe' );
	}

	public function testNextKey() {
		$this->fail( 'TestMe' );
	}

}
