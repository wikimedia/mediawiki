<?php

/**
 * @todo Tests covering decodeCharReferences can be refactored into a single
 * method and dataprovider.
 *
 * @group Sanitizer
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
			// https://html.spec.whatwg.org/multipage/semantics.html#the-kbd-element
			[
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'<kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd>',
				'Nested <kbd>.'
			],
			// https://html.spec.whatwg.org/multipage/semantics.html#the-sub-and-sup-elements
			[
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'<var>x<sub><var>i</var></sub></var>, <var>y<sub><var>i</var></sub></var>',
				'Nested <var>.'
			],
			// https://html.spec.whatwg.org/multipage/semantics.html#the-dfn-element
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
			[ [ 'עברית' => 'bar' ], 'עברית=bar', 'Non-Latin attribute' ],
			[ [ '६' => 'bar' ], '६=bar', 'Devanagari number' ],
			[ [ '搭𨋢' => 'bar' ], '搭𨋢=bar', 'Non-BMP character' ],
			[ [], 'ńgh=bar', 'Combining accent is not allowed' ],
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
			# it, like ProofreadPage (see T29539)
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
		/** [ <attribute>, <element>, [message] ] */
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
		/** [ <expected>, <css>, [message] ] */
		return [
			// Valid comments spanning entire input
			[ '/**/', '/**/' ],
			[ '/* comment */', '/* comment */' ],
			// Weird stuff
			[ ' ', '/****/' ],
			[ ' ', '/* /* */' ],
			[ 'display: block;', "display:/* foo */block;" ],
			[ 'display: block;', "display:\\2f\\2a foo \\2a\\2f block;",
				'Backslash-escaped comments must be stripped (T30450)' ],
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
			[ '/* insecure input */', 'foo: attr( title, url );' ],
			[ '/* insecure input */', 'foo: attr( title url );' ],
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
	 * Test Sanitizer::escapeId
	 *
	 * @dataProvider provideEscapeId
	 * @covers Sanitizer::escapeId
	 */
	public function testEscapeId( $input, $output ) {
		$this->assertEquals(
			$output,
			Sanitizer::escapeId( $input, [ 'noninitial', 'legacy' ] )
		);
	}

	public static function provideEscapeId() {
		return [
			[ '+', '.2B' ],
			[ '&', '.26' ],
			[ '=', '.3D' ],
			[ ':', ':' ],
			[ ';', '.3B' ],
			[ '@', '.40' ],
			[ '$', '.24' ],
			[ '-_.', '-_.' ],
			[ '!', '.21' ],
			[ '*', '.2A' ],
			[ '/', '.2F' ],
			[ '[]', '.5B.5D' ],
			[ '<>', '.3C.3E' ],
			[ '\'', '.27' ],
			[ '§', '.C2.A7' ],
			[ 'Test:A & B/Here', 'Test:A_.26_B.2FHere' ],
			[ 'A&B&amp;C&amp;amp;D&amp;amp;amp;E', 'A.26B.26amp.3BC.26amp.3Bamp.3BD.26amp.3Bamp.3Bamp.3BE' ],
		];
	}

	/**
	 * Test escapeIdReferenceList for consistency with escapeIdForAttribute
	 *
	 * @dataProvider provideEscapeIdReferenceList
	 * @covers Sanitizer::escapeIdReferenceList
	 */
	public function testEscapeIdReferenceList( $referenceList, $id1, $id2 ) {
		$this->assertEquals(
			Sanitizer::escapeIdReferenceList( $referenceList ),
			Sanitizer::escapeIdForAttribute( $id1 )
				. ' '
				. Sanitizer::escapeIdForAttribute( $id2 )
		);
	}

	public static function provideEscapeIdReferenceList() {
		/** [ <reference list>, <individual id 1>, <individual id 2> ] */
		return [
			[ 'foo bar', 'foo', 'bar' ],
			[ '#1 #2', '#1', '#2' ],
			[ '+1 +2', '+1', '+2' ],
		];
	}

	/**
	 * @dataProvider provideIsReservedDataAttribute
	 * @covers Sanitizer::isReservedDataAttribute
	 */
	public function testIsReservedDataAttribute( $attr, $expected ) {
		$this->assertSame( $expected, Sanitizer::isReservedDataAttribute( $attr ) );
	}

	public static function provideIsReservedDataAttribute() {
		return [
			[ 'foo', false ],
			[ 'data', false ],
			[ 'data-foo', false ],
			[ 'data-mw', true ],
			[ 'data-ooui', true ],
			[ 'data-parsoid', true ],
			[ 'data-mw-foo', true ],
			[ 'data-ooui-foo', true ],
			[ 'data-mwfoo', true ], // could be false but this is how it's implemented currently
		];
	}

	/**
	 * @dataProvider provideEscapeIdForStuff
	 *
	 * @covers Sanitizer::escapeIdForAttribute()
	 * @covers Sanitizer::escapeIdForLink()
	 * @covers Sanitizer::escapeIdForExternalInterwiki()
	 * @covers Sanitizer::escapeIdInternal()
	 *
	 * @param string $stuff
	 * @param string[] $config
	 * @param string $id
	 * @param string|false $expected
	 * @param int|null $mode
	 */
	public function testEscapeIdForStuff( $stuff, array $config, $id, $expected, $mode = null ) {
		$func = "Sanitizer::escapeIdFor{$stuff}";
		$iwFlavor = array_pop( $config );
		$this->setMwGlobals( [
			'wgFragmentMode' => $config,
			'wgExternalInterwikiFragmentMode' => $iwFlavor,
		] );
		$escaped = call_user_func( $func, $id, $mode );
		self::assertEquals( $expected, $escaped );
	}

	public function provideEscapeIdForStuff() {
		// Test inputs and outputs
		$text = 'foo тест_#%!\'()[]:<>&&amp;&amp;amp;';
		$legacyEncoded = 'foo_.D1.82.D0.B5.D1.81.D1.82_.23.25.21.27.28.29.5B.5D:.3C.3E' .
			'.26.26amp.3B.26amp.3Bamp.3B';
		$html5Encoded = 'foo_тест_#%!\'()[]:<>&&amp;&amp;amp;';
		$html5Experimental = 'foo_тест_!_()[]:<>_amp;_amp;amp;';

		// Settings: last element is $wgExternalInterwikiFragmentMode, the rest is $wgFragmentMode
		$legacy = [ 'legacy', 'legacy' ];
		$legacyNew = [ 'legacy', 'html5', 'legacy' ];
		$newLegacy = [ 'html5', 'legacy', 'legacy' ];
		$new = [ 'html5', 'legacy' ];
		$allNew = [ 'html5', 'html5' ];
		$experimentalLegacy = [ 'html5-legacy', 'legacy', 'legacy' ];
		$newExperimental = [ 'html5', 'html5-legacy', 'legacy' ];

		return [
			// Pure legacy: how MW worked before 2017
			[ 'Attribute', $legacy, $text, $legacyEncoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $legacy, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $legacy, $text, $legacyEncoded ],
			[ 'ExternalInterwiki', $legacy, $text, $legacyEncoded ],

			// Transition to a new world: legacy links with HTML5 fallback
			[ 'Attribute', $legacyNew, $text, $legacyEncoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $legacyNew, $text, $html5Encoded, Sanitizer::ID_FALLBACK ],
			[ 'Link', $legacyNew, $text, $legacyEncoded ],
			[ 'ExternalInterwiki', $legacyNew, $text, $legacyEncoded ],

			// New world: HTML5 links, legacy fallbacks
			[ 'Attribute', $newLegacy, $text, $html5Encoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $newLegacy, $text, $legacyEncoded, Sanitizer::ID_FALLBACK ],
			[ 'Link', $newLegacy, $text, $html5Encoded ],
			[ 'ExternalInterwiki', $newLegacy, $text, $legacyEncoded ],

			// Distant future: no legacy fallbacks, but still linking to leagacy wikis
			[ 'Attribute', $new, $text, $html5Encoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $new, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $new, $text, $html5Encoded ],
			[ 'ExternalInterwiki', $new, $text, $legacyEncoded ],

			// Just before the heat death of universe: external interwikis are also HTML5 \m/
			[ 'Attribute', $allNew, $text, $html5Encoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $allNew, $text, false, Sanitizer::ID_FALLBACK ],
			[ 'Link', $allNew, $text, $html5Encoded ],
			[ 'ExternalInterwiki', $allNew, $text, $html5Encoded ],

			// Someone flipped $wgExperimentalHtmlIds on
			[ 'Attribute', $experimentalLegacy, $text, $html5Experimental, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $experimentalLegacy, $text, $legacyEncoded, Sanitizer::ID_FALLBACK ],
			[ 'Link', $experimentalLegacy, $text, $html5Experimental ],
			[ 'ExternalInterwiki', $experimentalLegacy, $text, $legacyEncoded ],

			// Migration from $wgExperimentalHtmlIds to modern HTML5
			[ 'Attribute', $newExperimental, $text, $html5Encoded, Sanitizer::ID_PRIMARY ],
			[ 'Attribute', $newExperimental, $text, $html5Experimental, Sanitizer::ID_FALLBACK ],
			[ 'Link', $newExperimental, $text, $html5Encoded ],
			[ 'ExternalInterwiki', $newExperimental, $text, $legacyEncoded ],
		];
	}

	/**
	 * @dataProvider provideStripAllTags
	 *
	 * @covers Sanitizer::stripAllTags()
	 * @covers RemexStripTagHandler
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testStripAllTags( $input, $expected ) {
		$this->assertEquals( $expected, Sanitizer::stripAllTags( $input ) );
	}

	public function provideStripAllTags() {
		return [
			[ '<p>Foo</p>', 'Foo' ],
			[ '<p id="one">Foo</p><p id="two">Bar</p>', 'FooBar' ],
			[ "<p>Foo</p>\n<p>Bar</p>", 'Foo Bar' ],
			[ '<p>Hello &lt;strong&gt; wor&#x6c;&#100; caf&eacute;</p>', 'Hello <strong> world café' ],
			[
				'<p><small data-foo=\'bar"&lt;baz>quux\'><a href="./Foo">Bar</a></small> Whee!</p>',
				'Bar Whee!'
			],
			[ '1<span class="<?php">2</span>3', '123' ],
			[ '1<span class="<?">2</span>3', '123' ],
		];
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers Sanitizer::escapeIdInternal()
	 */
	public function testInvalidFragmentThrows() {
		$this->setMwGlobals( 'wgFragmentMode', [ 'boom!' ] );
		Sanitizer::escapeIdForAttribute( 'This should throw' );
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @covers Sanitizer::escapeIdForAttribute()
	 */
	public function testNoPrimaryFragmentModeThrows() {
		$this->setMwGlobals( 'wgFragmentMode', [ 666 => 'html5' ] );
		Sanitizer::escapeIdForAttribute( 'This should throw' );
	}

	/**
	 * @expectedException UnexpectedValueException
	 * @covers Sanitizer::escapeIdForLink()
	 */
	public function testNoPrimaryFragmentModeThrows2() {
		$this->setMwGlobals( 'wgFragmentMode', [ 666 => 'html5' ] );
		Sanitizer::escapeIdForLink( 'This should throw' );
	}
}
