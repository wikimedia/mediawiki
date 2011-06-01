<?php

/**
 * Tests for WebRequest::findIE6Extension
 */
class FindIE6ExtensionTest extends MediaWikiTestCase {
	function testSimple() {
		$this->assertEquals( 
			'y',
			WebRequest::findIE6Extension( 'x.y' ),
			'Simple extension'
		);
	}

	function testSimpleNoExt() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( 'x' ),
			'No extension'
		);
	}

	function testEmpty() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( '' ),
			'Empty string'
		);
	}

	function testQuestionMark() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( '?' ),
			'Question mark only'
		);
	}

	function testExtQuestionMark() {
		$this->assertEquals(
			'x',
			WebRequest::findIE6Extension( '.x?' ),
			'Extension then question mark'
		);
	}

	function testQuestionMarkExt() {
		$this->assertEquals(
			'x',
			WebRequest::findIE6Extension( '?.x' ),
			'Question mark then extension'
		);
	}

	function testInvalidChar() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( '.x*' ),
			'Extension with invalid character'
		);
	}

	function testInvalidCharThenExtension() {
		$this->assertEquals(
			'x',
			WebRequest::findIE6Extension( '*.x' ),
			'Invalid character followed by an extension'
		);
	}

	function testMultipleQuestionMarks() {
		$this->assertEquals(
			'c',
			WebRequest::findIE6Extension( 'a?b?.c?.d?e?f' ),
			'Multiple question marks'
		);
	}

	function testExeException() {
		$this->assertEquals(
			'd',
			WebRequest::findIE6Extension( 'a?b?.exe?.d?.e' ),
			'.exe exception'
		);
	}

	function testExeException2() {
		$this->assertEquals(
			'exe',
			WebRequest::findIE6Extension( 'a?b?.exe' ),
			'.exe exception 2'
		);
	}

	function testHash() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( 'a#b.c' ),
			'Hash character preceding extension'
		);
	}

	function testHash2() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( 'a?#b.c' ),
			'Hash character preceding extension 2'
		);
	}

	function testDotAtEnd() {
		$this->assertEquals(
			'',
			WebRequest::findIE6Extension( '.' ),
			'Dot at end of string'
		);
	}
}
