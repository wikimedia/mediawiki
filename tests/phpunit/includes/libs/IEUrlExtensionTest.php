<?php

/**
 * Tests for IEUrlExtension::findIE6Extension
 */
class IEUrlExtensionTest extends MediaWikiTestCase {
	public function testSimple() {
		$this->assertEquals(
			'y',
			IEUrlExtension::findIE6Extension( 'x.y' ),
			'Simple extension'
		);
	}

	public function testSimpleNoExt() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'x' ),
			'No extension'
		);
	}

	public function testEmpty() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '' ),
			'Empty string'
		);
	}

	public function testQuestionMark() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '?' ),
			'Question mark only'
		);
	}

	public function testExtQuestionMark() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '.x?' ),
			'Extension then question mark'
		);
	}

	public function testQuestionMarkExt() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '?.x' ),
			'Question mark then extension'
		);
	}

	public function testInvalidChar() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.x*' ),
			'Extension with invalid character'
		);
	}

	public function testInvalidCharThenExtension() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '*.x' ),
			'Invalid character followed by an extension'
		);
	}

	public function testMultipleQuestionMarks() {
		$this->assertEquals(
			'c',
			IEUrlExtension::findIE6Extension( 'a?b?.c?.d?e?f' ),
			'Multiple question marks'
		);
	}

	public function testExeException() {
		$this->assertEquals(
			'd',
			IEUrlExtension::findIE6Extension( 'a?b?.exe?.d?.e' ),
			'.exe exception'
		);
	}

	public function testExeException2() {
		$this->assertEquals(
			'exe',
			IEUrlExtension::findIE6Extension( 'a?b?.exe' ),
			'.exe exception 2'
		);
	}

	public function testHash() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a#b.c' ),
			'Hash character preceding extension'
		);
	}

	public function testHash2() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a?#b.c' ),
			'Hash character preceding extension 2'
		);
	}

	public function testDotAtEnd() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.' ),
			'Dot at end of string'
		);
	}

	public function testTwoDots() {
		$this->assertEquals(
			'z',
			IEUrlExtension::findIE6Extension( 'x.y.z' ),
			'Two dots'
		);
	}
}
