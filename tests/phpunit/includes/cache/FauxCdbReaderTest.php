<?php
use MediaWiki\Cache\FauxCdbReader;

/**
 * @group Cache
 * @covers MediaWiki\Cache\FauxCdbReader
 */
class FauxCdbReaderTest extends \PHPUnit_Framework_TestCase {

	public function provideConstructor_fail() {
		return [
			[ 'Foo' ],
			[ [ "x" => 1, "y" => 2 ] ],
		];
	}

	/**
	 * @dataProvider provideConstructor_fail
	 */
	public function testConstructor_fail( $data ) {
		$this->setExpectedException( 'InvalidArgumentException' );

		new FauxCdbReader( $data );
	}

	public function testClose() {
		$reader = new FauxCdbReader( [ 'foo' => 'FOO' ] );
		$reader->close();

		$this->assertFalse( $reader->get( 'foo' ) );
	}

	public function testGet() {
		$reader = new FauxCdbReader( [ 'foo' => 'FOO' ] );

		$this->assertSame( 'FOO', $reader->get( 'foo' ) );
		$this->assertFalse( $reader->get( 'xyz' ) );
	}

	public function testExists() {
		$reader = new FauxCdbReader( [ 'foo' => 'FOO' ] );

		$this->assertTrue( $reader->exists( 'foo' ) );
		$this->assertFalse( $reader->exists( 'xyz' ) );
	}

	public function testFirstKey() {
		$reader = new FauxCdbReader( [
			'one' => 'ONE',
			'two' => 'TWO',
		] );

		$this->assertSame( 'one', $reader->firstkey() );
		$this->assertSame( 'one', $reader->firstkey() );

		$reader->nextkey();
		$this->assertSame( 'one', $reader->firstkey() );
	}

	public function testFirstKey_empty() {
		$reader = new FauxCdbReader( [] );

		$this->assertFalse( $reader->firstkey() );
	}

	public function testNextKey() {
		$reader = new FauxCdbReader( [
			'one' => 'ONE',
			'two' => 'TWO',
		] );

		$this->assertSame( 'one', $reader->nextkey() );
		$this->assertSame( 'two', $reader->nextkey() );

		$reader->firstkey();
		$this->assertSame( 'two', $reader->nextkey() );
		$this->assertFalse( $reader->nextkey() );

		$reader->firstkey();
		$this->assertSame( 'two', $reader->nextkey() );
	}

}
