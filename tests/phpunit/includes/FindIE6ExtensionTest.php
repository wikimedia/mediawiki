<?php

/**
 * Tests for WebRequest::findIE6Extension
 */
class FindIE6ExtensionTest extends MediaWikiTestCase {
	function testSimple() {
		$this->assertEquals( 
			WebRequest::findIE6Extension( 'x.y' ),
			'y',
			'Simple extension'
		);
	}

	function testSimpleNoExt() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'x' ),
			'',
			'No extension'
		);
	}

	function testEmpty() {
		$this->assertEquals(
			WebRequest::findIE6Extension( '' ),
			'',
			'Empty string'
		);
	}

	function testQuestionMark() {
		$this->assertEquals(
			WebRequest::findIE6Extension( '?' ),
			'',
			'Question mark only'
		);
	}

	function testExtQuestionMark() {
		$this->assertEquals(
			WebRequest::findIE6Extension( '.x?' ),
			'x',
			'Extension then question mark'
		);
	}

	function testQuestionMarkExt() {
		$this->assertEquals(
			WebRequest::findIE6Extension( '?.x' ),
			'x',
			'Question mark then extension'
		);
	}

	function testInvalidChar() {
		$this->assertEquals(
			WebRequest::findIE6Extension( '.x*' ),
			'',
			'Extension with invalid character'
		);
	}

	function testMultipleQuestionMarks() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'a?b?.c?.d?e?f' ),
			'c',
			'Multiple question marks'
		);
	}

	function testExeException() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'a?b?.exe?.d?.e' ),
			'd',
			'.exe exception'
		);
	}

	function testExeException2() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'a?b?.exe' ),
			'exe',
			'.exe exception 2'
		);
	}

	function testHash() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'a#b.c' ),
			'',
			'Hash character preceding extension'
		);
	}

	function testHash2() {
		$this->assertEquals(
			WebRequest::findIE6Extension( 'a?#b.c' ),
			'',
			'Hash character preceding extension 2'
		);
	}
}
