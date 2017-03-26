<?php
/**
 * This file test the CSSMin library shipped with Mediawiki.
 *
 * @author Timo Tijhof
 */

class CSSMinTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$server = 'http://doc.example.org';

		$this->setMwGlobals( [
			'wgServer' => $server,
			'wgCanonicalServer' => $server,
		] );
	}

	/**
	 * @dataProvider mimeTypeProvider
	 */
	public function testGetMimeType( $fileContents, $fileExtension, $expected ) {
		$fileName = wfTempDir() . DIRECTORY_SEPARATOR . uniqid( 'MW_PHPUnit_CSSMinTest_' ) . '.'
			. $fileExtension;
		$this->addTmpFiles( $fileName );
		file_put_contents( $fileName, $fileContents );
		$this->assertSame( $expected, CSSMin::getMimeType( $fileName ) );
	}

	public function mimeTypeProvider() {
		return [
			'JPEG with short extension' => [
				"\xFF\xD8\xFF",
				'jpg',
				'image/jpeg'
			],
			'JPEG with long extension' => [
				"\xFF\xD8\xFF",
				'jpeg',
				'image/jpeg'
			],
			'PNG' => [
				"\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
				'png',
				'image/png'
			],

			'PNG extension but JPEG content' => [
				"\xFF\xD8\xFF",
				'png',
				'image/png'
			],
			'JPEG extension but PNG content' => [
				"\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
				'jpg',
				'image/jpeg'
			],
			'PNG extension but SVG content' => [
				'<?xml version="1.0"?><svg></svg>',
				'png',
				'image/png'
			],
			'SVG extension but PNG content' => [
				"\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
				'svg',
				'image/svg+xml'
			],

			'SVG with all headers' => [
				'<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" '
				. '"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg></svg>',
				'svg',
				'image/svg+xml'
			],
			'SVG with XML header only' => [
				'<?xml version="1.0"?><svg></svg>',
				'svg',
				'image/svg+xml'
			],
			'SVG with DOCTYPE only' => [
				'<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" '
				. '"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg></svg>',
				'svg',
				'image/svg+xml'
			],
			'SVG without any header' => [
				'<svg></svg>',
				'svg',
				'image/svg+xml'
			],
		];
	}

	/**
	 * @dataProvider provideMinifyCases
	 * @covers CSSMin::minify
	 */
	public function testMinify( $code, $expectedOutput ) {
		$minified = CSSMin::minify( $code );

		$this->assertEquals(
			$expectedOutput,
			$minified,
			'Minified output should be in the form expected.'
		);
	}

	public static function provideMinifyCases() {
		return [
			// Whitespace
			[ "\r\t\f \v\n\r", "" ],
			[ "foo, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ],

			// Loose comments
			[ "/* foo */", "" ],
			[ "/*******\n foo\n *******/", "" ],
			[ "/*!\n foo\n */", "" ],

			// Inline comments in various different places
			[ "/* comment */foo, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ],
			[ "foo/* comment */, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ],
			[ "foo,/* comment */ bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ],
			[ "foo, bar/* comment */ {\n\tprop: value;\n}", "foo,bar{prop:value}" ],
			[ "foo, bar {\n\t/* comment */prop: value;\n}", "foo,bar{prop:value}" ],
			[ "foo, bar {\n\tprop: /* comment */value;\n}", "foo,bar{prop:value}" ],
			[ "foo, bar {\n\tprop: value /* comment */;\n}", "foo,bar{prop:value }" ],
			[ "foo, bar {\n\tprop: value; /* comment */\n}", "foo,bar{prop:value; }" ],

			// Keep track of things that aren't as minified as much as they
			// could be (T37493)
			[ 'foo { prop: value ;}', 'foo{prop:value }' ],
			[ 'foo { prop : value; }', 'foo{prop :value}' ],
			[ 'foo { prop: value ; }', 'foo{prop:value }' ],
			[ 'foo { font-family: "foo" , "bar"; }', 'foo{font-family:"foo" ,"bar"}' ],
			[ "foo { src:\n\turl('foo') ,\n\turl('bar') ; }", "foo{src:url('foo') ,url('bar') }" ],

			// Interesting cases with string values
			// - Double quotes, single quotes
			[ 'foo { content: ""; }', 'foo{content:""}' ],
			[ "foo { content: ''; }", "foo{content:''}" ],
			[ 'foo { content: "\'"; }', 'foo{content:"\'"}' ],
			[ "foo { content: '\"'; }", "foo{content:'\"'}" ],
			// - Whitespace in string values
			[ 'foo { content: " "; }', 'foo{content:" "}' ],
		];
	}

	/**
	 * This tests funky parameters to CSSMin::remap. testRemapRemapping tests
	 * the basic functionality.
	 *
	 * @dataProvider provideRemapCases
	 * @covers CSSMin::remap
	 */
	public function testRemap( $message, $params, $expectedOutput ) {
		$remapped = call_user_func_array( 'CSSMin::remap', $params );

		$messageAdd = " Case: $message";
		$this->assertEquals(
			$expectedOutput,
			$remapped,
			'CSSMin::remap should return the expected url form.' . $messageAdd
		);
	}

	public static function provideRemapCases() {
		// Parameter signature:
		// CSSMin::remap( $code, $local, $remote, $embedData = true )
		return [
			[
				'Simple case',
				[ 'foo { prop: url(bar.png); }', false, 'http://example.org', false ],
				'foo { prop: url(http://example.org/bar.png); }',
			],
			[
				'Without trailing slash',
				[ 'foo { prop: url(../bar.png); }', false, 'http://example.org/quux', false ],
				'foo { prop: url(http://example.org/bar.png); }',
			],
			[
				'With trailing slash on remote (T29052)',
				[ 'foo { prop: url(../bar.png); }', false, 'http://example.org/quux/', false ],
				'foo { prop: url(http://example.org/bar.png); }',
			],
			[
				'Guard against stripping double slashes from query',
				[ 'foo { prop: url(bar.png?corge=//grault); }', false, 'http://example.org/quux/', false ],
				'foo { prop: url(http://example.org/quux/bar.png?corge=//grault); }',
			],
			[
				'Expand absolute paths',
				[ 'foo { prop: url(/w/skin/images/bar.png); }', false, 'http://example.org/quux', false ],
				'foo { prop: url(http://doc.example.org/w/skin/images/bar.png); }',
			],
		];
	}

	/**
	 * This tests basic functionality of CSSMin::remap. testRemapRemapping tests funky parameters.
	 *
	 * @dataProvider provideRemapRemappingCases
	 * @covers CSSMin::remap
	 */
	public function testRemapRemapping( $message, $input, $expectedOutput ) {
		$localPath = __DIR__ . '/../../data/cssmin';
		$remotePath = 'http://localhost/w';

		$realOutput = CSSMin::remap( $input, $localPath, $remotePath );
		$this->assertEquals( $expectedOutput, $realOutput, "CSSMin::remap: $message" );
	}

	public static function provideIsRemoteUrl() {
		return [
			[ true, 'http://localhost/w/red.gif?123' ],
			[ true, 'https://example.org/x.png' ],
			[ true, '//example.org/x.y.z/image.png' ],
			[ true, '//localhost/styles.css?query=yes' ],
			[ true, 'data:image/gif;base64,R0lGODlhAQABAIAAAP8AADAAACwAAAAAAQABAAACAkQBADs=' ],
			[ false, 'x.gif' ],
			[ false, '/x.gif' ],
			[ false, './x.gif' ],
			[ false, '../x.gif' ],
		];
	}

	/**
	 * @dataProvider provideIsRemoteUrl
	 * @cover CSSMin::isRemoteUrl
	 */
	public function testIsRemoteUrl( $expect, $url ) {
		$this->assertEquals( CSSMinTestable::isRemoteUrl( $url ), $expect );
	}

	public static function provideIsLocalUrls() {
		return [
			[ false, 'x.gif' ],
			[ true, '/x.gif' ],
			[ false, './x.gif' ],
			[ false, '../x.gif' ],
		];
	}

	/**
	 * @dataProvider provideIsLocalUrls
	 * @cover CSSMin::isLocalUrl
	 */
	public function testIsLocalUrl( $expect, $url ) {
		$this->assertEquals( CSSMinTestable::isLocalUrl( $url ), $expect );
	}

	public static function provideRemapRemappingCases() {
		// red.gif and green.gif are one-pixel 35-byte GIFs.
		// large.png is a 35K PNG that should be non-embeddable.
		// Full paths start with http://localhost/w/.
		// Timestamps in output are replaced with 'timestamp'.

		// data: URIs for red.gif, green.gif, circle.svg
		$red   = 'data:image/gif;base64,R0lGODlhAQABAIAAAP8AADAAACwAAAAAAQABAAACAkQBADs=';
		$green = 'data:image/gif;base64,R0lGODlhAQABAIAAAACAADAAACwAAAAAAQABAAACAkQBADs=';
		$svg = 'data:image/svg+xml,%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A'
			. '%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%228%22%20height%3D'
			. '%228%22%3E%0A%3Ccircle%20cx%3D%224%22%20cy%3D%224%22%20r%3D%222%22%2F%3E%0A%3C%2Fsvg%3E%0A';

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[
				'Regular file',
				'foo { background: url(red.gif); }',
				'foo { background: url(http://localhost/w/red.gif?34ac6); }',
			],
			[
				'Regular file (missing)',
				'foo { background: url(theColorOfHerHair.gif); }',
				'foo { background: url(http://localhost/w/theColorOfHerHair.gif); }',
			],
			[
				'Remote URL',
				'foo { background: url(http://example.org/w/foo.png); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			],
			[
				'Protocol-relative remote URL',
				'foo { background: url(//example.org/w/foo.png); }',
				'foo { background: url(//example.org/w/foo.png); }',
			],
			[
				'Remote URL with query',
				'foo { background: url(http://example.org/w/foo.png?query=yes); }',
				'foo { background: url(http://example.org/w/foo.png?query=yes); }',
			],
			[
				'Protocol-relative remote URL with query',
				'foo { background: url(//example.org/w/foo.png?query=yes); }',
				'foo { background: url(//example.org/w/foo.png?query=yes); }',
			],
			[
				'Domain-relative URL',
				'foo { background: url(/static/foo.png); }',
				'foo { background: url(http://doc.example.org/static/foo.png); }',
			],
			[
				'Domain-relative URL with query',
				'foo { background: url(/static/foo.png?query=yes); }',
				'foo { background: url(http://doc.example.org/static/foo.png?query=yes); }',
			],
			[
				'Remote URL (unnecessary quotes not preserved)',
				'foo { background: url("http://example.org/w/foo.png"); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			],
			[
				'Embedded file',
				'foo { /* @embed */ background: url(red.gif); }',
				"foo { background: url($red); background: url(http://localhost/w/red.gif?34ac6)!ie; }",
			],
			[
				'Embedded file, other comments before the rule',
				"foo { /* Bar. */ /* @embed */ background: url(red.gif); }",
				"foo { /* Bar. */ background: url($red); /* Bar. */ background: url(http://localhost/w/red.gif?34ac6)!ie; }",
			],
			[
				'Can not re-embed data: URIs',
				"foo { /* @embed */ background: url($red); }",
				"foo { background: url($red); }",
			],
			[
				'Can not remap data: URIs',
				"foo { background: url($red); }",
				"foo { background: url($red); }",
			],
			[
				'Can not embed remote URLs',
				'foo { /* @embed */ background: url(http://example.org/w/foo.png); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			],
			[
				'Embedded file (inline @embed)',
				'foo { background: /* @embed */ url(red.gif); }',
				"foo { background: url($red); "
					. "background: url(http://localhost/w/red.gif?34ac6)!ie; }",
			],
			[
				'Can not embed large files',
				'foo { /* @embed */ background: url(large.png); }',
				"foo { background: url(http://localhost/w/large.png?e3d1f); }",
			],
			[
				'SVG files are embedded without base64 encoding and unnecessary IE 6 and 7 fallback',
				'foo { /* @embed */ background: url(circle.svg); }',
				"foo { background: url($svg); }",
			],
			[
				'Two regular files in one rule',
				'foo { background: url(red.gif), url(green.gif); }',
				'foo { background: url(http://localhost/w/red.gif?34ac6), '
					. 'url(http://localhost/w/green.gif?13651); }',
			],
			[
				'Two embedded files in one rule',
				'foo { /* @embed */ background: url(red.gif), url(green.gif); }',
				"foo { background: url($red), url($green); "
					. "background: url(http://localhost/w/red.gif?34ac6), "
					. "url(http://localhost/w/green.gif?13651)!ie; }",
			],
			[
				'Two embedded files in one rule (inline @embed)',
				'foo { background: /* @embed */ url(red.gif), /* @embed */ url(green.gif); }',
				"foo { background: url($red), url($green); "
					. "background: url(http://localhost/w/red.gif?34ac6), "
					. "url(http://localhost/w/green.gif?13651)!ie; }",
			],
			[
				'Two embedded files in one rule (inline @embed), one too large',
				'foo { background: /* @embed */ url(red.gif), /* @embed */ url(large.png); }',
				"foo { background: url($red), url(http://localhost/w/large.png?e3d1f); "
					. "background: url(http://localhost/w/red.gif?34ac6), "
					. "url(http://localhost/w/large.png?e3d1f)!ie; }",
			],
			[
				'Practical example with some noise',
				'foo { /* @embed */ background: #f9f9f9 url(red.gif) 0 0 no-repeat; }',
				"foo { background: #f9f9f9 url($red) 0 0 no-repeat; "
					. "background: #f9f9f9 url(http://localhost/w/red.gif?34ac6) 0 0 no-repeat!ie; }",
			],
			[
				'Does not mess with other properties',
				'foo { color: red; background: url(red.gif); font-size: small; }',
				'foo { color: red; background: url(http://localhost/w/red.gif?34ac6); font-size: small; }',
			],
			[
				'Spacing and miscellanea not changed (1)',
				'foo {   background:    url(red.gif);  }',
				'foo {   background:    url(http://localhost/w/red.gif?34ac6);  }',
			],
			[
				'Spacing and miscellanea not changed (2)',
				'foo {background:url(red.gif)}',
				'foo {background:url(http://localhost/w/red.gif?34ac6)}',
			],
			[
				'Spaces within url() parentheses are ignored',
				'foo { background: url( red.gif ); }',
				'foo { background: url(http://localhost/w/red.gif?34ac6); }',
			],
			[
				'@import rule to local file (should we remap this?)',
				'@import url(/styles.css)',
				'@import url(http://doc.example.org/styles.css)',
			],
			[
				'@import rule to URL (should we remap this?)',
				'@import url(//localhost/styles.css?query=yes)',
				'@import url(//localhost/styles.css?query=yes)',
			],
			[
				'Simple case with comments before url',
				'foo { prop: /* some {funny;} comment */ url(bar.png); }',
				'foo { prop: /* some {funny;} comment */ url(http://localhost/w/bar.png); }',
			],
			[
				'Simple case with comments after url',
				'foo { prop: url(red.gif)/* some {funny;} comment */ ; }',
				'foo { prop: url(http://localhost/w/red.gif?34ac6)/* some {funny;} comment */ ; }',
			],
			[
				'Embedded file with comment before url',
				'foo { /* @embed */ background: /* some {funny;} comment */ url(red.gif); }',
				"foo { background: /* some {funny;} comment */ url($red); background: /* some {funny;} comment */ url(http://localhost/w/red.gif?34ac6)!ie; }",
			],
			[
				'Embedded file with comments inside and outside the rule',
				'foo { /* @embed */ background: url(red.gif) /* some {foo;} comment */; /* some {bar;} comment */ }',
				"foo { background: url($red) /* some {foo;} comment */; background: url(http://localhost/w/red.gif?34ac6) /* some {foo;} comment */!ie; /* some {bar;} comment */ }",
			],
			[
				'Embedded file with comment outside the rule',
				'foo { /* @embed */ background: url(red.gif); /* some {funny;} comment */ }',
				"foo { background: url($red); background: url(http://localhost/w/red.gif?34ac6)!ie; /* some {funny;} comment */ }",
			],
			[
				'Rule with two urls, each with comments',
				'{ background: /*asd*/ url(something.png); background: /*jkl*/ url(something.png); }',
				'{ background: /*asd*/ url(http://localhost/w/something.png); background: /*jkl*/ url(http://localhost/w/something.png); }',
			],
			[
				'Sanity check for offending line from jquery.ui.theme.css (T62077)',
				'.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #d3d3d3/*{borderColorDefault}*/; background: #e6e6e6/*{bgColorDefault}*/ url(images/ui-bg_glass_75_e6e6e6_1x400.png)/*{bgImgUrlDefault}*/ 50%/*{bgDefaultXPos}*/ 50%/*{bgDefaultYPos}*/ repeat-x/*{bgDefaultRepeat}*/; font-weight: normal/*{fwDefault}*/; color: #555555/*{fcDefault}*/; }',
				'.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #d3d3d3/*{borderColorDefault}*/; background: #e6e6e6/*{bgColorDefault}*/ url(http://localhost/w/images/ui-bg_glass_75_e6e6e6_1x400.png)/*{bgImgUrlDefault}*/ 50%/*{bgDefaultXPos}*/ 50%/*{bgDefaultYPos}*/ repeat-x/*{bgDefaultRepeat}*/; font-weight: normal/*{fwDefault}*/; color: #555555/*{fcDefault}*/; }',
			],
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * This tests basic functionality of CSSMin::buildUrlValue.
	 *
	 * @dataProvider provideBuildUrlValueCases
	 * @covers CSSMin::buildUrlValue
	 */
	public function testBuildUrlValue( $message, $input, $expectedOutput ) {
		$this->assertEquals(
			$expectedOutput,
			CSSMin::buildUrlValue( $input ),
			"CSSMin::buildUrlValue: $message"
		);
	}

	public static function provideBuildUrlValueCases() {
		return [
			[
				'Full URL',
				'scheme://user@domain:port/~user/fi%20le.png?query=yes&really=y+s',
				'url(scheme://user@domain:port/~user/fi%20le.png?query=yes&really=y+s)',
			],
			[
				'data: URI',
				'data:image/png;base64,R0lGODlh/+==',
				'url(data:image/png;base64,R0lGODlh/+==)',
			],
			[
				'URL with quotes',
				"https://en.wikipedia.org/wiki/Wendy's",
				"url(\"https://en.wikipedia.org/wiki/Wendy's\")",
			],
			[
				'URL with parentheses',
				'https://en.wikipedia.org/wiki/Boston_(band)',
				'url("https://en.wikipedia.org/wiki/Boston_(band)")',
			],
		];
	}

	/**
	 * Seperated because they are currently broken (T37492)
	 *
	 * @group Broken
	 * @dataProvider provideStringCases
	 * @covers CSSMin::remap
	 */
	public function testMinifyWithCSSStringValues( $code, $expectedOutput ) {
		$this->testMinifyOutput( $code, $expectedOutput );
	}

	public static function provideStringCases() {
		return [
			// String values should be respected
			// - More than one space in a string value
			[ 'foo { content: "  "; }', 'foo{content:"  "}' ],
			// - Using a tab in a string value (turns into a space)
			[ "foo { content: '\t'; }", "foo{content:'\t'}" ],
			// - Using css-like syntax in string values
			[
				'foo::after { content: "{;}"; position: absolute; }',
				'foo::after{content:"{;}";position:absolute}'
			],
		];
	}
}

class CSSMinTestable extends CSSMin {
	// Make some protected methods public
	public static function isRemoteUrl( $maybeUrl ) {
		return parent::isRemoteUrl( $maybeUrl );
	}
	public static function isLocalUrl( $maybeUrl ) {
		return parent::isLocalUrl( $maybeUrl );
	}
}
