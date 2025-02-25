<?php

/**
 * @group Sanitizer
 */
class SanitizerUnitTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideDecodeCharReferences
	 * @covers Sanitizer::decodeCharReferences
	 */
	public function testDecodeCharReferences( string $expected, string $input ) {
		$this->assertSame( $expected, Sanitizer::decodeCharReferences( $input ) );
	}

	public function provideDecodeCharReferences() {
		return [
			'decode named entities' => [
				"\u{00E9}cole",
				'&eacute;cole',
			],
			'decode numeric entities' => [
				"\u{0108}io bonas dans l'\u{00E9}cole!",
				"&#x108;io bonas dans l'&#233;cole!",
			],
			'decode mixed numeric/named entities' => [
				"\u{0108}io bonas dans l'\u{00E9}cole!",
				"&#x108;io bonas dans l'&eacute;cole!",
			],
			'decode mixed complex entities' => [
				"\u{0108}io bonas dans l'\u{00E9}cole! (mais pas &#x108;io dans l'&eacute;cole)",
				"&#x108;io bonas dans l'&eacute;cole! (mais pas &amp;#x108;io dans l'&#38;eacute;cole)",
			],
			'Invalid ampersand' => [
				'a & b',
				'a & b',
			],
			'Invalid named entity' => [
				'&foo;',
				'&foo;',
			],
			'Invalid numbered entity' => [
				UtfNormal\Constants::UTF8_REPLACEMENT,
				"&#88888888888888;",
			],
		];
	}

	/**
	 * @dataProvider provideTagAttributesToDecode
	 * @covers Sanitizer::decodeTagAttributes
	 */
	public function testDecodeTagAttributes( $expected, $attributes, $message = '' ) {
		$this->assertSame( $expected,
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
	 * @dataProvider provideCssCommentsFixtures
	 * @covers Sanitizer::checkCss
	 */
	public function testCssCommentsChecking( $expected, $css, $message = '' ) {
		$this->assertSame( $expected,
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
		$this->assertSame(
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
			[ '&#x338;', "\u{0338}" ],
			[ '&#x338;', '&#x338;' ],
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
	 * @dataProvider provideStripAllTags
	 *
	 * @covers Sanitizer::stripAllTags()
	 * @covers \MediaWiki\Parser\RemexStripTagHandler
	 *
	 * @param string $input
	 * @param string $expected
	 */
	public function testStripAllTags( $input, $expected ) {
		$this->assertSame( $expected, Sanitizer::stripAllTags( $input ) );
	}

	public function provideStripAllTags() {
		return [
			[ '<p>Foo</p>', 'Foo' ],
			[ '<p id="one">Foo</p><p id="two">Bar</p>', 'Foo Bar' ],
			[ "<p>Foo</p>\n<p>Bar</p>", 'Foo Bar' ],
			[ '<p>Hello &lt;strong&gt; wor&#x6c;&#100; caf&eacute;</p>', 'Hello <strong> world café' ],
			[
				'<p><small data-foo=\'bar"&lt;baz>quux\'><a href="./Foo">Bar</a></small> Whee!</p>',
				'Bar Whee!'
			],
			[ '1<span class="<?php">2</span>3', '123' ],
			[ '1<span class="<?">2</span>3', '123' ],
			[ '<th>1</th><td>2</td>', '1 2' ],
			[ '<style>.hello { display: block; }</style>', '' ],
			[ 'Foo<style>p { color: red; }</style>Bar', 'FooBar' ],
			[ '<script>var test = true;</script>', '' ],
		];
	}

}
