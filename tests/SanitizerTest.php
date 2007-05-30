<?php

class SanitizerTest extends PHPUnit_Framework_TestCase {
	function testDecodeNamed() {
		$this->assertEquals(
			"\xc3\xa9cole",
			Sanitizer::decodeCharReferences( '&eacute;cole' ) );
	}

	function testDecodeNumbered() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&#233;cole!" ) );
	}

	function testDecodeMixed() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&eacute;cole!" ) );
	}

	function testDecodeMixedComplex() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole! (mais pas &#x108;io dans l'&eacute;cole)",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&eacute;cole! (mais pas &amp;#x108;io dans l'&#38;eacute;cole)" ) );
	}

	function testDecodeInvalidAmp() {
		$this->assertEquals(
			"a & b",
			Sanitizer::decodeCharReferences( "a & b" ) );
	}

	function testDecodeInvalidNamed() {
		$this->assertEquals(
			"&foo;",
			Sanitizer::decodeCharReferences( "&foo;" ) );
	}

	function testDecodeInvalidNumbered() {
		$this->assertEquals(
			UTF8_REPLACEMENT,
			Sanitizer::decodeCharReferences( "&#88888888888888;" ) );
	}

	/* TODO: many more! */
}

?>
