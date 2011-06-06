<?php

/**
 * Tests for IEUrlExtension::findIE6Extension
 */
class IEUrlExtensionTest extends MediaWikiTestCase {
	function testSimple() {
		$this->assertEquals( 
			'y',
			IEUrlExtension::findIE6Extension( 'x.y' ),
			'Simple extension'
		);
	}

	function testSimpleNoExt() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'x' ),
			'No extension'
		);
	}

	function testEmpty() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '' ),
			'Empty string'
		);
	}

	function testQuestionMark() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '?' ),
			'Question mark only'
		);
	}

	function testExtQuestionMark() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '.x?' ),
			'Extension then question mark'
		);
	}

	function testQuestionMarkExt() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '?.x' ),
			'Question mark then extension'
		);
	}

	function testInvalidChar() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.x*' ),
			'Extension with invalid character'
		);
	}

	function testInvalidCharThenExtension() {
		$this->assertEquals(
			'x',
			IEUrlExtension::findIE6Extension( '*.x' ),
			'Invalid character followed by an extension'
		);
	}

	function testMultipleQuestionMarks() {
		$this->assertEquals(
			'c',
			IEUrlExtension::findIE6Extension( 'a?b?.c?.d?e?f' ),
			'Multiple question marks'
		);
	}

	function testExeException() {
		$this->assertEquals(
			'd',
			IEUrlExtension::findIE6Extension( 'a?b?.exe?.d?.e' ),
			'.exe exception'
		);
	}

	function testExeException2() {
		$this->assertEquals(
			'exe',
			IEUrlExtension::findIE6Extension( 'a?b?.exe' ),
			'.exe exception 2'
		);
	}

	function testHash() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a#b.c' ),
			'Hash character preceding extension'
		);
	}

	function testHash2() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( 'a?#b.c' ),
			'Hash character preceding extension 2'
		);
	}

	function testDotAtEnd() {
		$this->assertEquals(
			'',
			IEUrlExtension::findIE6Extension( '.' ),
			'Dot at end of string'
		);
	}
}
