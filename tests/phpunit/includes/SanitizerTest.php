<?php

class SanitizerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

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

	/**
	 * @cover Sanitizer::removeHTMLtags
	 * @dataProvider provideHtml5Tags
	 *
	 * @param String $tag Name of an HTML5 element (ie: 'video')
	 * @param Boolean $escaped Wheter sanitizer let the tag in or escape it (ie: '&lt;video&gt;')
	 */
	function testRemovehtmltagsOnHtml5Tags( $tag, $escaped ) {

		# Enable HTML5 mode
		global $wgHTML5;
		$save = $wgHTML5;
		$wgHTML5 = true;

		if( $escaped ) {
			$this->assertEquals( "&lt;$tag&gt;",
				Sanitizer::removeHTMLtags( "<$tag>" )
			);
		} else {
			$this->assertEquals( "<$tag></$tag>\n",
				Sanitizer::removeHTMLtags( "<$tag>" )
			);
		}
		$wgHTML5 = $save;
	}

	/**
	 * Provide HTML5 tags
	 */
	function provideHtml5Tags() {
		$ESCAPED  = true; # We want tag to be escaped
		$VERBATIM = false;  # We want to keep the tag
		return array(
			array( 'data', $VERBATIM ),
			array( 'mark', $VERBATIM ),
			array( 'time', $VERBATIM ),
			array( 'video', $ESCAPED ),
		);
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

	/**
	 * @dataProvider provideDeprecatedAttributes
	 * @cover Sanitizer::fixTagAttributes
	 */
	function testDeprecatedAttributesUnaltered( $inputAttr, $inputEl, $message = '' ) {
		$this->assertEquals( " $inputAttr",
			Sanitizer::fixTagAttributes( $inputAttr, $inputEl ),
			$message
		);
	}

	public static function provideDeprecatedAttributes() {
		/** array( <attribute>, <element>, [message] ) */
		return array(
			array( 'clear="left"', 'br' ),
			array( 'clear="all"', 'br' ),
			array( 'width="100"', 'td' ),
			array( 'nowrap="true"', 'td' ),
			array( 'nowrap=""', 'td' ),
			array( 'align="right"', 'td' ),
			array( 'align="center"', 'table' ),
			array( 'align="left"', 'tr' ),
			array( 'align="center"', 'div' ),
			array( 'align="left"', 'h1' ),
			array( 'align="left"', 'span' ),
		);
	}

	/**
	 * @dataProvider provideCssCommentsFixtures
	 * @cover Sanitizer::checkCss
	 */
	function testCssCommentsChecking( $expected, $css, $message = '' ) {
		$this->assertEquals( $expected,
			Sanitizer::checkCss( $css ),
			$message
		);
	}

	public static function provideCssCommentsFixtures() {
		/** array( <expected>, <css>, [message] ) */
		return array(
			array( ' ', '/**/' ),
			array( ' ', '/****/' ),
			array( ' ', '/* comment */' ),
			array( ' ', "\\2f\\2a foo \\2a\\2f",
				'Backslash-escaped comments must be stripped (bug 28450)' ),
			array( '', '/* unfinished comment structure',
	 			'Remove anything after a comment-start token' ),
			array( '', "\\2f\\2a unifinished comment'",
	 			'Remove anything after a backslash-escaped comment-start token' ),
			array( '/* insecure input */', 'filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'asdf.png\',sizingMethod=\'scale\');'),
			array( '/* insecure input */', '-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'asdf.png\',sizingMethod=\'scale\')";'),
			array( '/* insecure input */', 'width: expression(1+1);'),
			array( '/* insecure input */', 'background-image: image(asdf.png);'),
			array( '/* insecure input */', 'background-image: -webkit-image(asdf.png);'),
			array( '/* insecure input */', 'background-image: -moz-image(asdf.png);'),
			array( '/* insecure input */', 'background-image: image-set("asdf.png" 1x, "asdf.png" 2x);'),
			array( '/* insecure input */', 'background-image: -webkit-image-set("asdf.png" 1x, "asdf.png" 2x);'),
			array( '/* insecure input */', 'background-image: -moz-image-set("asdf.png" 1x, "asdf.png" 2x);'),
		);
	}
}

