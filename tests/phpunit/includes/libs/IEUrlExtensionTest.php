<?php

/**
 * Tests for IEUrlExtension::findIE6Extension
 * @todo tests below for findIE6Extension should be split into...
 *    ...a dataprovider and test method.
 */
class IEUrlExtensionTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testSimple() {
		$this->assertEquals(
			'y',
			IEUrlExtension::findIE6Extension( 'x.y' ),
			'Simple extension'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testSimpleNoExt() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'x' ),
			'No extension'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testEmpty() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '' ),
			'Empty string'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testQuestionMark() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '?' ),
			'Question mark only'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testExtQuestionMark() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '.x?' ),
			'Extension then question mark'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testQuestionMarkExt() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '?.x' ),
			'Question mark then extension'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testInvalidChar() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.x*' ),
			'Extension with invalid character'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testInvalidCharThenExtension() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '*.x' ),
			'Invalid character followed by an extension'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testMultipleQuestionMarks() {
		$this->assertEquals(
			'c',
			IEUrlExtension::findIE6Extension( 'a?b?.c?.d?e?f' ),
			'Multiple question marks'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testExeException() {
		$this->assertEquals(
			'd',
			IEUrlExtension::findIE6Extension( 'a?b?.exe?.d?.e' ),
			'.exe exception'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testExeException2() {
		$this->assertEquals(
			'exe',
			IEUrlExtension::findIE6Extension( 'a?b?.exe' ),
			'.exe exception 2'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testHash() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a#b.c' ),
			'Hash character preceding extension'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testHash2() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a?#b.c' ),
			'Hash character preceding extension 2'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testDotAtEnd() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.' ),
			'Dot at end of string'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testTwoDots() {
		$this->assertEquals(
			'z',
			IEUrlExtension::findIE6Extension( 'x.y.z' ),
			'Two dots'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testScriptQuery() {
		$this->assertEquals(
			'php',
			IEUrlExtension::findIE6Extension( 'example.php?foo=a&bar=b' ),
			'Script with query'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testEscapedScriptQuery() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'example%2Ephp?foo=a&bar=b' ),
			'Script with urlencoded dot and query'
		);
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 */
	public function testEscapedScriptQueryDot() {
		$this->assertEquals(
			'y',
			IEUrlExtension::findIE6Extension( 'example%2Ephp?foo=a.x&bar=b.y' ),
			'Script with urlencoded dot and query with dot'
		);
	}
}
