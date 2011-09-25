<?php

class SanitizerTest extends MediaWikiTestCase {

	function setUp() {
		AutoLoader::loadClass( 'Sanitizer' );
	}

	function testDecodeNamedEntities() {
		$this->assertEquals(
			"\xc3\xa9cole",
			Sanitizer::decodeCharReferences( '&eacute;cole' ),
			'decode named entities'
		);
	}

	function testDecodeNumericEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&#233;cole!" ),
			'decode numeric entities'
		);
	}

	function testDecodeMixedEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&eacute;cole!" ),
			'decode mixed numeric/named entities'
		);
	}

	function testDecodeMixedComplexEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole! (mais pas &#x108;io dans l'&eacute;cole)",
			Sanitizer::decodeCharReferences(
				"&#x108;io bonas dans l'&eacute;cole! (mais pas &amp;#x108;io dans l'&#38;eacute;cole)"
			),
			'decode mixed complex entities'
		);
	}

	function testInvalidAmpersand() {
		$this->assertEquals(
			'a & b',
			Sanitizer::decodeCharReferences( 'a & b' ),
			'Invalid ampersand'
		);
	}

	function testInvalidEntities() {
		$this->assertEquals(
			'&foo;',
			Sanitizer::decodeCharReferences( '&foo;' ),
			'Invalid named entity'
		);
	}

	function testInvalidNumberedEntities() {
		$this->assertEquals( UTF8_REPLACEMENT, Sanitizer::decodeCharReferences( "&#88888888888888;" ), 'Invalid numbered entity' );
	}

	function testSelfClosingTag() {
		$GLOBALS['wgUseTidy'] = false;
		$this->assertEquals(
			'<div>Hello world</div>',
			Sanitizer::removeHTMLtags( '<div>Hello world</div />' ),
			'Self-closing closing div'
		);
	}
	
	function testDecodeTagAttributes() {
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=bar' ), array( 'foo' => 'bar' ), 'Unquoted attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( '    foo   =   bar    ' ), array( 'foo' => 'bar' ), 'Spaced attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo="bar"' ), array( 'foo' => 'bar' ), 'Double-quoted attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=\'bar\'' ), array( 'foo' => 'bar' ), 'Single-quoted attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=\'bar\'   baz="foo"' ), array( 'foo' => 'bar', 'baz' => 'foo' ), 'Several attributes' );
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=\'bar\'   baz="foo"' ), array( 'foo' => 'bar', 'baz' => 'foo' ), 'Several attributes' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=\'bar\'   baz="foo"' ), array( 'foo' => 'bar', 'baz' => 'foo' ), 'Several attributes' );
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( ':foo=\'bar\'' ), array( ':foo' => 'bar' ), 'Leading :' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( '_foo=\'bar\'' ), array( '_foo' => 'bar' ), 'Leading _' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'Foo=\'bar\'' ), array( 'foo' => 'bar' ), 'Leading capital' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'FOO=BAR' ), array( 'foo' => 'BAR' ), 'Attribute keys are normalized to lowercase' );
		
		# Invalid beginning
		$this->assertEquals( Sanitizer::decodeTagAttributes( '-foo=bar' ), array(), 'Leading - is forbidden' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( '.foo=bar' ), array(), 'Leading . is forbidden' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo-bar=bar' ), array( 'foo-bar' => 'bar' ), 'A - is allowed inside the attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo-=bar' ), array( 'foo-' => 'bar' ), 'A - is allowed inside the attribute' );
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo.bar=baz' ), array( 'foo.bar' => 'baz' ), 'A . is allowed inside the attribute' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo.=baz' ), array( 'foo.' => 'baz' ), 'A . is allowed as last character' );
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo6=baz' ), array( 'foo6' => 'baz' ), 'Numbers are allowed' );
		
		# This bit is more relaxed than XML rules, but some extensions use it, like ProofreadPage (see bug 27539)
		$this->assertEquals( Sanitizer::decodeTagAttributes( '1foo=baz' ), array( '1foo' => 'baz' ), 'Leading numbers are allowed' );
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo$=baz' ), array(), 'Symbols are not allowed' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo@=baz' ), array(), 'Symbols are not allowed' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo~=baz' ), array(), 'Symbols are not allowed' );
		
		
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=1[#^`*%w/(' ), array( 'foo' => '1[#^`*%w/(' ), 'All kind of characters are allowed as values' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo="1[#^`*%\'w/("' ), array( 'foo' => '1[#^`*%\'w/(' ), 'Double quotes are allowed if quoted by single quotes' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=\'1[#^`*%"w/(\'' ), array( 'foo' => '1[#^`*%"w/(' ), 'Single quotes are allowed if quoted by double quotes' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=&amp;&quot;' ), array( 'foo' => '&"' ), 'Special chars can be provided as entities' );
		$this->assertEquals( Sanitizer::decodeTagAttributes( 'foo=&foobar;' ), array( 'foo' => '&foobar;' ), 'Entity-like items are accepted' );
	}

	function testDeprecatedAttributes() {
		$GLOBALS['wgCleanupPresentationalAttributes'] = true;
		$this->assertEquals( Sanitizer::fixTagAttributes( 'clear="left"', 'br' ), ' style="clear: left;"', 'Deprecated attributes are converted to styles when enabled.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'clear="all"', 'br' ), ' style="clear: both;"', 'clear=all is converted to clear: both; not clear: all;' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'CLEAR="ALL"', 'br' ), ' style="clear: both;"', 'clear=ALL is not treated differently from clear=all' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'width="100"', 'td' ), ' style="width: 100px;"', 'Numeric sizes use pixels instead of numbers.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'width="100%"', 'td' ), ' style="width: 100%;"', 'Units are allowed in sizes.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'WIDTH="100%"', 'td' ), ' style="width: 100%;"', 'Uppercase WIDTH is treated as lowercase width.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'WiDTh="100%"', 'td' ), ' style="width: 100%;"', 'Mixed case does not break WiDTh.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'nowrap="true"', 'td' ), ' style="white-space: nowrap;"', 'nowrap attribute is output as white-space: nowrap; not something else.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'nowrap=""', 'td' ), ' style="white-space: nowrap;"', 'nowrap="" is considered true, not false' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'NOWRAP="true"', 'td' ), ' style="white-space: nowrap;"', 'nowrap attribute works when uppercase.' );
		$this->assertEquals( Sanitizer::fixTagAttributes( 'NoWrAp="true"', 'td' ), ' style="white-space: nowrap;"', 'nowrap attribute works when mixed-case.' );
		$GLOBALS['wgCleanupPresentationalAttributes'] = false;
		$this->assertEquals( Sanitizer::fixTagAttributes( 'clear="left"', 'br' ), ' clear="left"', 'Deprecated attributes are not converted to styles when enabled.' );
	}
}

