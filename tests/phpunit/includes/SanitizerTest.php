<?php

/**
 * @todo Tests covering decodeCharReferences can be refactored into a single
 * method and dataprovider.
 */
class SanitizerTest extends MediaWikiTestCase {

	protected function tearDown() {
		MWTidy::destroySingleton();
		parent::tearDown();
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
		$this->assertEquals(
			UtfNormal\Constants::UTF8_REPLACEMENT,
			Sanitizer::decodeCharReferences( "&#88888888888888;" ),
			'Invalid numbered entity'
		);
	}

	/**
	 * @covers Sanitizer::removeHTMLtags
	 * @dataProvider provideHtml5Tags
	 *
	 * @param string $tag Name of an HTML5 element (ie: 'video')
	 * @param bool $escaped Whether sanitizer let the tag in or escape it (ie: '&lt;video&gt;')
	 */
	public function testRemovehtmltagsOnHtml5Tags( $tag, $escaped ) {
		MWTidy::setInstance( false );

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
		return [
			[ 'data', $VERBATIM ],
			[ 'mark', $VERBATIM ],
			[ 'time', $VERBATIM ],
			[ 'video', $ESCAPED ],
		];
	}

	function dataRemoveHTMLtags() {
		return [
			// former testSelfClosingTag
			[
				'<div>Hello world</div />',
				'<div>Hello world</div>',
				'Self-closing closing div'
			],
			// Make sure special nested HTML5 semantics are not broken
			// http://www.whatwg.org/html/text-level-semantics.html#the-kbd-element
			[
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'Nested <kbd>.'
			],
			// http://www.whatwg.org/html/text-level-semantics.html#the-sub-and-sup-elements
			[
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'Nested <var>.'
			],
			// http://www.whatwg.org/html/text-level-semantics.html#the-dfn-element
			[
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<dfn><abbr title="Garage Door Opener">GDO</abbr></dfn>',
				'<abbr> inside <dfn>',
			],
		];
	}

	/**
	 * @dataProvider dataRemoveHTMLtags
	 * @covers Sanitizer::removeHTMLtags
	 */
	public function testRemoveHTMLtags( $input, $output, $msg = null ) {
		MWTidy::setInstance( false );
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
		return [
			[ [ 'foo' => 'bar' ], 'foo=bar', 'Unquoted attribute' ],
			[ [ 'foo' => 'bar' ], '    foo   =   bar    ', 'Spaced attribute' ],
			[ [ 'foo' => 'bar' ], 'foo="bar"', 'Double-quoted attribute' ],
			[ [ 'foo' => 'bar' ], 'foo=\'bar\'', 'Single-quoted attribute' ],
			[
				[ 'foo' => 'bar', 'baz' => 'foo' ],
				'foo=\'bar\'   baz="foo"',
				'Several attributes'
			],
			[
				[ 'foo' => 'bar', 'baz' => 'foo' ],
				'foo=\'bar\'   baz="foo"',
				'Several attributes'
			],
			[
				[ 'foo' => 'bar', 'baz' => 'foo' ],
				'foo=\'bar\'   baz="foo"',
				'Several attributes'
			],
			[ [ ':foo' => 'bar' ], ':foo=\'bar\'', 'Leading :' ],
			[ [ '_foo' => 'bar' ], '_foo=\'bar\'', 'Leading _' ],
			[ [ 'foo' => 'bar' ], 'Foo=\'bar\'', 'Leading capital' ],
			[ [ 'foo' => 'BAR' ], 'FOO=BAR', 'Attribute keys are normalized to lowercase' ],

			# Invalid beginning
			[ [], '-foo=bar', 'Leading - is forbidden' ],
			[ [], '.foo=bar', 'Leading . is forbidden' ],
			[ [ 'foo-bar' => 'bar' ], 'foo-bar=bar', 'A - is allowed inside the attribute' ],
			[ [ 'foo-' => 'bar' ], 'foo-=bar', 'A - is allowed inside the attribute' ],
			[ [ 'foo.bar' => 'baz' ], 'foo.bar=baz', 'A . is allowed inside the attribute' ],
			[ [ 'foo.' => 'baz' ], 'foo.=baz', 'A . is allowed as last character' ],
			[ [ 'foo6' => 'baz' ], 'foo6=baz', 'Numbers are allowed' ],

			# This bit is more relaxed than XML rules, but some extensions use
			# it, like ProofreadPage (see bug 27539)
			[ [ '1foo' => 'baz' ], '1foo=baz', 'Leading numbers are allowed' ],
			[ [], 'foo$=baz', 'Symbols are not allowed' ],
			[ [], 'foo@=baz', 'Symbols are not allowed' ],
			[ [], 'foo~=baz', 'Symbols are not allowed' ],
			[
				[ 'foo' => '1[#^`*%w/(' ],
				'foo=1[#^`*%w/(',
				'All kind of characters are allowed as values'
			],
			[
				[ 'foo' => '1[#^`*%\'w/(' ],
				'foo="1[#^`*%\'w/("',
				'Double quotes are allowed if quoted by single quotes'
			],
			[
				[ 'foo' => '1[#^`*%"w/(' ],
				'foo=\'1[#^`*%"w/(\'',
				'Single quotes are allowed if quoted by double quotes'
			],
			[ [ 'foo' => '&"' ], 'foo=&amp;&quot;', 'Special chars can be provided as entities' ],
			[ [ 'foo' => '&foobar;' ], 'foo=&foobar;', 'Entity-like items are accepted' ],
		];
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
		return [
			[ 'clear="left"', 'br' ],
			[ 'clear="all"', 'br' ],
			[ 'width="100"', 'td' ],
			[ 'nowrap="true"', 'td' ],
			[ 'nowrap=""', 'td' ],
			[ 'align="right"', 'td' ],
			[ 'align="center"', 'table' ],
			[ 'align="left"', 'tr' ],
			[ 'align="center"', 'div' ],
			[ 'align="left"', 'h1' ],
			[ 'align="left"', 'p' ],
		];
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
		return [
			// Valid comments spanning entire input
			[ '/**/', '/**/' ],
			[ '/* comment */', '/* comment */' ],
			// Weird stuff
			[ ' ', '/****/' ],
			[ ' ', '/* /* */' ],
			[ 'display: block;', "display:/* foo */block;" ],
			[ 'display: block;', "display:\\2f\\2a foo \\2a\\2f block;",
				'Backslash-escaped comments must be stripped (bug 28450)' ],
			[ '', '/* unfinished comment structure',
				'Remove anything after a comment-start token' ],
			[ '', "\\2f\\2a unifinished comment'",
				'Remove anything after a backslash-escaped comment-start token' ],
			[
				'/* insecure input */',
				'filter: progid:DXImageTransform.Microsoft.AlphaImageLoader'
					. '(src=\'asdf.png\',sizingMethod=\'scale\');'
			],
			[
				'/* insecure input */',
				'-ms-filter: "progid:DXImageTransform.Microsoft.AlphaImageLoader'
					. '(src=\'asdf.png\',sizingMethod=\'scale\')";'
			],
			[ '/* insecure input */', 'width: expression(1+1);' ],
			[ '/* insecure input */', 'background-image: image(asdf.png);' ],
			[ '/* insecure input */', 'background-image: -webkit-image(asdf.png);' ],
			[ '/* insecure input */', 'background-image: -moz-image(asdf.png);' ],
			[ '/* insecure input */', 'background-image: image-set("asdf.png" 1x, "asdf.png" 2x);' ],
			[
				'/* insecure input */',
				'background-image: -webkit-image-set("asdf.png" 1x, "asdf.png" 2x);'
			],
			[
				'/* insecure input */',
				'background-image: -moz-image-set("asdf.png" 1x, "asdf.png" 2x);'
			],
		];
	}

	/**
	 * @dataProvider provideEscapeHtmlAllowEntities
	 * @covers Sanitizer::escapeHtmlAllowEntities
	 */
	public function testEscapeHtmlAllowEntities( $expected, $html ) {
		$this->assertEquals(
			$expected,
			Sanitizer::escapeHtmlAllowEntities( $html )
		);
	}

	public static function provideEscapeHtmlAllowEntities() {
		return [
			[ 'foo', 'foo' ],
			[ 'a¡b', 'a&#161;b' ],
			[ 'foo&#039;bar', "foo'bar" ],
			[ '&lt;script&gt;foo&lt;/script&gt;', '<script>foo</script>' ],
		];
	}

	/**
	 * Test escapeIdReferenceList for consistency with escapeId
	 *
	 * @dataProvider provideEscapeIdReferenceList
	 * @covers Sanitizer::escapeIdReferenceList
	 */
	public function testEscapeIdReferenceList( $referenceList, $id1, $id2 ) {
		$this->assertEquals(
			Sanitizer::escapeIdReferenceList( $referenceList, 'noninitial' ),
			Sanitizer::escapeId( $id1, 'noninitial' )
				. ' '
				. Sanitizer::escapeId( $id2, 'noninitial' )
		);
	}

	public static function provideEscapeIdReferenceList() {
		/** array( <reference list>, <individual id 1>, <individual id 2> ) */
		return [
			[ 'foo bar', 'foo', 'bar' ],
			[ '#1 #2', '#1', '#2' ],
			[ '+1 +2', '+1', '+2' ],
		];
	}
}
