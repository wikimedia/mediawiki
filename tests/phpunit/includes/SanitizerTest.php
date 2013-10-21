<?php

/**
 * @todo Tests covering decodeCharReferences can be refactored into a single
 * method and dataprovider.
 */
class SanitizerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		AutoLoader::loadClass( 'Sanitizer' );
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testDecodeNamedEntities() {
		$this->assertEquals(
			"\xc3\xa9cole",
			Sanitizer::decodeCharReferences( '&eacute;cole' ),
			'decode named entities'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testDecodeNumericEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&#233;cole!" ),
			'decode numeric entities'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testDecodeMixedEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			Sanitizer::decodeCharReferences( "&#x108;io bonas dans l'&eacute;cole!" ),
			'decode mixed numeric/named entities'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testDecodeMixedComplexEntities() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole! (mais pas &#x108;io dans l'&eacute;cole)",
			Sanitizer::decodeCharReferences(
				"&#x108;io bonas dans l'&eacute;cole! (mais pas &amp;#x108;io dans l'&#38;eacute;cole)"
			),
			'decode mixed complex entities'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testInvalidAmpersand() {
		$this->assertEquals(
			'a & b',
			Sanitizer::decodeCharReferences( 'a & b' ),
			'Invalid ampersand'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testInvalidEntities() {
		$this->assertEquals(
			'&foo;',
			Sanitizer::decodeCharReferences( '&foo;' ),
			'Invalid named entity'
		);
	}

	/**
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testInvalidNumberedEntities() {
		$this->assertEquals( UTF8_REPLACEMENT, Sanitizer::decodeCharReferences( "&#88888888888888;" ), 'Invalid numbered entity' );
	}

	/**
	 * @covers Sanitizer::removeHTMLtags
	 * @dataProvider provideHtml5Tags
	 *
	 * @param String $tag Name of an HTML5 element (ie: 'video')
	 * @param Boolean $escaped Wheter sanitizer let the tag in or escape it (ie: '&lt;video&gt;')
	 */
	public function testRemovehtmltagsOnHtml5Tags( $tag, $escaped ) {
		$this->setMwGlobals( array(
			'wgUseTidy' => false
		) );

		if ( $escaped ) {
			$this->assertEquals( "&lt;$tag&gt;",
				Sanitizer::removeHTMLtags( "<$tag>" )
			);
		} else {
			$this->assertEquals( "<$tag></$tag>\n",
				Sanitizer::removeHTMLtags( "<$tag>" )
			);
		}
	}

	/**
	 * Provide HTML5 tags
	 */
	public static function provideHtml5Tags() {
		$ESCAPED = true; # We want tag to be escaped
		$VERBATIM = false; # We want to keep the tag
		return array(
			array( 'data', $VERBATIM ),
			array( 'mark', $VERBATIM ),
			array( 'time', $VERBATIM ),
			array( 'video', $ESCAPED ),
		);
	}

	function dataRemoveHTMLtags() {
		return array(
			// former testSelfClosingTag
			array(
				'<div>Hello world</div />',
				'<div>Hello world</div>',
				'Self-closing closing div'
			),
			// Make sure special nested HTML5 semantics are not broken
			// http://www.whatwg.org/html/text-level-semantics.html#the-kbd-element
			array(
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'Nested <kbd>.'
			),
			// http://www.whatwg.org/html/text-level-semantics.html#the-sub-and-sup-elements
			array(
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'Nested <var>.'
			),
			// http://www.whatwg.org/html/text-level-semantics.html#the-dfn-element
			array(
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<abbr> inside <dfn>',
			),
		);
	}

	/**
	 * @dataProvider dataRemoveHTMLtags
	 * @covers Sanitizer::removeHTMLtags
	 */
	public function testRemoveHTMLtags( $input, $output, $msg = null ) {
		$GLOBALS['wgUseTidy'] = false;
		$this->assertEquals( $output, Sanitizer::removeHTMLtags( $input ), $msg );
	}

	/**
	 * @dataProvider provideTagAttributesToDecode
	 * @covers Sanitizer::decodeTagAttributes
	 */
	public function testDecodeTagAttributes( $expected, $attributes, $message = '' ) {
		$this->assertEquals( $expected,
			Sanitizer::decodeTagAttributes( $attributes ),
			$message
		);
	}

	public static function provideTagAttributesToDecode() {
		return array(
			array( array( 'foo' => 'bar' ), 'foo=bar', 'Unquoted attribute' ),
			array( array( 'foo' => 'bar' ), '    foo   =   bar    ', 'Spaced attribute' ),
			array( array( 'foo' => 'bar' ), 'foo="bar"', 'Double-quoted attribute' ),
			array( array( 'foo' => 'bar' ), 'foo=\'bar\'', 'Single-quoted attribute' ),
			array( array( 'foo' => 'bar', 'baz' => 'foo' ), 'foo=\'bar\'   baz="foo"', 'Several attributes' ),
			array( array( 'foo' => 'bar', 'baz' => 'foo' ), 'foo=\'bar\'   baz="foo"', 'Several attributes' ),
			array( array( 'foo' => 'bar', 'baz' => 'foo' ), 'foo=\'bar\'   baz="foo"', 'Several attributes' ),
			array( array( ':foo' => 'bar' ), ':foo=\'bar\'', 'Leading :' ),
			array( array( '_foo' => 'bar' ), '_foo=\'bar\'', 'Leading _' ),
			array( array( 'foo' => 'bar' ), 'Foo=\'bar\'', 'Leading capital' ),
			array( array( 'foo' => 'BAR' ), 'FOO=BAR', 'Attribute keys are normalized to lowercase' ),

			# Invalid beginning
			array( array(), '-foo=bar', 'Leading - is forbidden' ),
			array( array(), '.foo=bar', 'Leading . is forbidden' ),
			array( array( 'foo-bar' => 'bar' ), 'foo-bar=bar', 'A - is allowed inside the attribute' ),
			array( array( 'foo-' => 'bar' ), 'foo-=bar', 'A - is allowed inside the attribute' ),
			array( array( 'foo.bar' => 'baz' ), 'foo.bar=baz', 'A . is allowed inside the attribute' ),
			array( array( 'foo.' => 'baz' ), 'foo.=baz', 'A . is allowed as last character' ),
			array( array( 'foo6' => 'baz' ), 'foo6=baz', 'Numbers are allowed' ),

			# This bit is more relaxed than XML rules, but some extensions use
			# it, like ProofreadPage (see bug 27539)
			array( array( '1foo' => 'baz' ), '1foo=baz', 'Leading numbers are allowed' ),
			array( array(), 'foo$=baz', 'Symbols are not allowed' ),
			array( array(), 'foo@=baz', 'Symbols are not allowed' ),
			array( array(), 'foo~=baz', 'Symbols are not allowed' ),
			array( array( 'foo' => '1[#^`*%w/(' ), 'foo=1[#^`*%w/(', 'All kind of characters are allowed as values' ),
			array( array( 'foo' => '1[#^`*%\'w/(' ), 'foo="1[#^`*%\'w/("', 'Double quotes are allowed if quoted by single quotes' ),
			array( array( 'foo' => '1[#^`*%"w/(' ), 'foo=\'1[#^`*%"w/(\'', 'Single quotes are allowed if quoted by double quotes' ),
			array( array( 'foo' => '&"' ), 'foo=&amp;&quot;', 'Special chars can be provided as entities' ),
			array( array( 'foo' => '&foobar;' ), 'foo=&foobar;', 'Entity-like items are accepted' ),
		);
	}

	/**
	 * @dataProvider provideDeprecatedAttributes
	 * @covers Sanitizer::fixTagAttributes
	 */
	public function testDeprecatedAttributesUnaltered( $inputAttr, $inputEl, $message = '' ) {
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
	 * @covers Sanitizer::checkCss
	 */
	public function testCssCommentsChecking( $expected, $css, $message = '' ) {
		$this->assertEquals( $expected,
			Sanitizer::checkCss( $css ),
			$message
		);
	}

	public static function provideCssCommentsFixtures() {
		/** array( <expected>, <css>, [message] ) */
		return array(
			// Valid comments spanning entire input
			array( '/**/', '/**/' ),
			array( '/* comment */', '/* comment */' ),
			// Weird stuff
			array( ' ', '/****/' ),
			array( ' ', '/* /* */' ),
			array( 'display: block;', "display:/* foo */block;" ),
			array( 'display: block;', "display:\\2f\\2a foo \\2a\\2f block;",
				'Backslash-escaped comments must be stripped (bug 28450)' ),
			array( '', '/* unfinished comment structure',
				'Remove anything after a comment-start token' ),
			array( '', "\\2f\\2a unifinished comment'",
				'Remove anything after a backslash-escaped comment-start token' ),
			array( '/* insecure input */', 'filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'asdf.png\',sizingMethod=\'scale\');' ),
			array( '/* insecure input */', '-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'asdf.png\',sizingMethod=\'scale\')";' ),
			array( '/* insecure input */', 'width: expression(1+1);' ),
			array( '/* insecure input */', 'background-image: image(asdf.png);' ),
			array( '/* insecure input */', 'background-image: -webkit-image(asdf.png);' ),
			array( '/* insecure input */', 'background-image: -moz-image(asdf.png);' ),
			array( '/* insecure input */', 'background-image: image-set("asdf.png" 1x, "asdf.png" 2x);' ),
			array( '/* insecure input */', 'background-image: -webkit-image-set("asdf.png" 1x, "asdf.png" 2x);' ),
			array( '/* insecure input */', 'background-image: -moz-image-set("asdf.png" 1x, "asdf.png" 2x);' ),
		);
	}

	/**
	 * Test for support or lack of support for specific attributes in the attribute whitelist.
	 */
	public static function provideAttributeSupport() {
		/** array( <attributes>, <expected>, <message> ) */
		return array(
			array( 'div', ' role="presentation"', ' role="presentation"', 'Support for WAI-ARIA\'s role="presentation".' ),
			array( 'div', ' role="main"', '', "Other WAI-ARIA roles are currently not supported." ),
		);
	}

	/**
	 * @dataProvider provideAttributeSupport
	 * @covers Sanitizer::fixTagAttributes
	 */
	public function testAttributeSupport( $tag, $attributes, $expected, $message ) {
		$this->assertEquals( $expected,
			Sanitizer::fixTagAttributes( $attributes, $tag ),
			$message
		);
	}
}
