<?php

class SanitizerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( 'wgCleanupPresentationalAttributes', true );

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

	/**
	 * @dataProvider provideDeprecatedAttributes
	 */
	function testDeprecatedAttributes( $input, $tag, $expected, $message = null ) {
		$this->assertEquals( $expected, Sanitizer::fixTagAttributes( $input, $tag ), $message );
	}

	function testDeprecatedAttributesDisabled() {
		global $wgCleanupPresentationalAttributes;

		$wgCleanupPresentationalAttributes = false;

		$this->assertEquals( ' clear="left"', Sanitizer::fixTagAttributes( 'clear="left"', 'br' ), 'Deprecated attributes are not converted to styles when enabled.' );
	}

	public static function provideDeprecatedAttributes() {
		return array(
			array( 'clear="left"', 'br', ' style="clear: left;"', 'Deprecated attributes are converted to styles when enabled.' ),
			array( 'clear="all"', 'br', ' style="clear: both;"', 'clear=all is converted to clear: both; not clear: all;' ),
			array( 'CLEAR="ALL"', 'br', ' style="clear: both;"', 'clear=ALL is not treated differently from clear=all' ),
			array( 'width="100"', 'td', ' style="width: 100px;"', 'Numeric sizes use pixels instead of numbers.' ),
			array( 'width="100%"', 'td', ' style="width: 100%;"', 'Units are allowed in sizes.' ),
			array( 'WIDTH="100%"', 'td', ' style="width: 100%;"', 'Uppercase WIDTH is treated as lowercase width.' ),
			array( 'WiDTh="100%"', 'td', ' style="width: 100%;"', 'Mixed case does not break WiDTh.' ),
			array( 'nowrap="true"', 'td', ' style="white-space: nowrap;"', 'nowrap attribute is output as white-space: nowrap; not something else.' ),
			array( 'nowrap=""', 'td', ' style="white-space: nowrap;"', 'nowrap="" is considered true, not false' ),
			array( 'NOWRAP="true"', 'td', ' style="white-space: nowrap;"', 'nowrap attribute works when uppercase.' ),
			array( 'NoWrAp="true"', 'td', ' style="white-space: nowrap;"', 'nowrap attribute works when mixed-case.' ),
			array( 'align="right"', 'td', ' style="text-align: right;"'   , 'align on table cells gets converted to text-align' ),
			array( 'align="center"', 'td', ' style="text-align: center;"' , 'align on table cells gets converted to text-align' ),
			array( 'align="left"'  , 'div', ' style="text-align: left;"'  , 'align=(left|right) on div elements gets converted to text-align' ),
			array( 'align="center"', 'div', ' style="text-align: center;"', 'align="center" on div elements gets converted to text-align' ),
			array( 'align="left"'  , 'p',   ' style="text-align: left;"'  , 'align on p elements gets converted to text-align' ),
			array( 'align="left"'  , 'h1',  ' style="text-align: left;"'  , 'align on h1 elements gets converted to text-align' ),
			array( 'align="left"'  , 'h1',  ' style="text-align: left;"'  , 'align on h1 elements gets converted to text-align' ),
			array( 'align="left"'  , 'caption',' style="text-align: left;"','align on caption elements gets converted to text-align' ),
			array( 'align="left"'  , 'tfoot',' style="text-align: left;"' , 'align on tfoot elements gets converted to text-align' ),
			array( 'align="left"'  , 'tbody',' style="text-align: left;"' , 'align on tbody elements gets converted to text-align' ),

			# <tr>
			array( 'align="right"' , 'tr', ' style="text-align: right;"' , 'align on table row get converted to text-align' ),
			array( 'align="center"', 'tr', ' style="text-align: center;"', 'align on table row get converted to text-align' ),
			array( 'align="left"'  , 'tr', ' style="text-align: left;"'  , 'align on table row get converted to text-align' ),

			#table
			array( 'align="left"'  , 'table', ' style="float: left;"'    , 'align on table converted to float' ),
			array( 'align="center"', 'table', ' style="margin-left: auto; margin-right: auto;"', 'align center on table converted to margins' ),
			array( 'align="right"' , 'table', ' style="float: right;"'   , 'align on table converted to float' ),
		);
	}

	/**
	 * @dataProvider provideCssCommentsFixtures
	 */
	function testCssCommentsChecking( $expected, $css, $message = '' ) {
		$this->assertEquals(
			$expected,
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
		);
	}
}

