<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group ResourceLoader
 * @group CSSMin
 */
class CSSMinTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		// For wfExpandUrl
		$server = 'https://expand.example';
		$this->setMwGlobals( [
			'wgServer' => $server,
			'wgCanonicalServer' => $server,
		] );
	}

	/**
	 * @dataProvider providesReferencedFiles
	 * @covers CSSMin::getLocalFileReferences
	 */
	public function testGetLocalFileReferences( $input, $expected ) {
		$output = CSSMin::getLocalFileReferences( $input, '/' );
		$this->assertEquals(
			$expected,
			$output,
			'getLocalFileReferences() must find the local file properly'
		);
	}

	public static function providesReferencedFiles() {
		// input, array of expected local file names
		return [
			[ 'url("//example.org")', [] ],
			[ 'url("https://example.org")', [] ],
			[ 'url("#default#")', [] ],
			[ 'url("WikiFont-Glyphs.svg#wikiglyph")', [ '/WikiFont-Glyphs.svg' ] ],
			[ 'url("#some-anchor")', [] ],
		];
	}

	/**
	 * @dataProvider provideSerializeStringValue
	 * @covers CSSMin::serializeStringValue
	 */
	public function testSerializeStringValue( $input, $expected ) {
		$output = CSSMin::serializeStringValue( $input );
		$this->assertEquals(
			$expected,
			$output,
			'Serialized output must be in the expected form.'
		);
	}

	public static function provideSerializeStringValue() {
		return [
			[ 'Hello World!', '"Hello World!"' ],
			[ "Null\0Null", "\"Null\u{FFFD}Null\"" ],
			[ '"', '"\\""' ],
			[ "'", '"\'"' ],
			[ "\\", '"\\\\"' ],
			[ "Tab\tTab", '"Tab\\9 Tab"' ],
			[ "Space  tab \t space", '"Space  tab \\9  space"' ],
			[ "Line\nfeed", '"Line\\a feed"' ],
			[ "Return\rreturn", '"Return\\d return"' ],
			[ "Next\u{0085}line", "\"Next\u{0085}line\"" ],
			[ "Del\x7fDel", '"Del\\7f Del"' ],
			[ "nb\u{00A0}sp", "\"nb\u{00A0}sp\"" ],
			[ "AMP&amp;AMP", "\"AMP&amp;AMP\"" ],
			[ '!"#$%&\'()*+,-./0123456789:;<=>?', '"!\\"#$%&\'()*+,-./0123456789:;<=>?"' ],
			[ '@[\\]^_`{|}~', '"@[\\\\]^_`{|}~"' ],
			[ 'ä', '"ä"' ],
			[ 'Ä', '"Ä"' ],
			[ '€', '"€"' ],
			[ '𝒞', '"𝒞"' ], // U+1D49E 'MATHEMATICAL SCRIPT CAPITAL C'
		];
	}

	/**
	 * @dataProvider provideMimeType
	 * @covers CSSMin::getMimeType
	 */
	public function testGetMimeType( $fileContents, $fileExtension, $expected ) {
		// Automatically removed when it falls out of scope (including if the test fails)
		$file = TempFSFile::factory( 'PHPUnit_CSSMinTest_', $fileExtension, wfTempDir() );
		$fileName = $file->getPath();
		file_put_contents( $fileName, $fileContents );
		$this->assertSame( $expected, CSSMin::getMimeType( $fileName ) );
	}

	public static function provideMimeType() {
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

			// Whitespaces after opening and before closing parentheses and brackets
			[ 'a:not( [ href ] ) { prop: url( foobar.png ); }', 'a:not([href]){prop:url(foobar.png)}' ],

			// Ensure that the invalid "url (" will not become the valid "url(" by minification
			[ 'foo { prop: url ( foobar.png ); }', 'foo{prop:url (foobar.png)}' ],
		];
	}

	public static function provideIsRemoteUrl() {
		return [
			[ true, 'http://localhost/w/red.gif?123' ],
			[ true, 'https://example.org/x.png' ],
			[ true, '//example.org/x.y.z/image.png' ],
			[ true, '//localhost/styles.css?query=yes' ],
			[ true, 'data:image/gif;base64,R0lGODlhAQABAIAAAP8AADAAACwAAAAAAQABAAACAkQBADs=' ],
			[ false, '' ],
			[ false, '/' ],
			[ true, '//' ],
			[ false, 'x.gif' ],
			[ false, '/x.gif' ],
			[ false, './x.gif' ],
			[ false, '../x.gif' ],
		];
	}

	/**
	 * @dataProvider provideIsRemoteUrl
	 * @covers CSSMin::isRemoteUrl
	 */
	public function testIsRemoteUrl( $expect, $url ) {
		$class = TestingAccessWrapper::newFromClass( CSSMin::class );
		$this->assertEquals( $class->isRemoteUrl( $url ), $expect );
	}

	public static function provideIsLocalUrls() {
		return [
			[ false, '' ],
			[ false, '/' ],
			[ false, '//' ],
			[ false, 'x.gif' ],
			[ true, '/x.gif' ],
			[ false, './x.gif' ],
			[ false, '../x.gif' ],
		];
	}

	/**
	 * @dataProvider provideIsLocalUrls
	 * @covers CSSMin::isLocalUrl
	 */
	public function testIsLocalUrl( $expect, $url ) {
		$class = TestingAccessWrapper::newFromClass( CSSMin::class );
		$this->assertEquals( $class->isLocalUrl( $url ), $expect );
	}

	/**
	 * This test tests funky parameters to CSSMin::remap.
	 *
	 * @see testRemapRemapping for testing of the basic functionality
	 * @dataProvider provideRemapCases
	 * @covers CSSMin::remap
	 * @covers CSSMin::remapOne
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
				'foo { prop: url(https://expand.example/w/skin/images/bar.png); }',
			],
			[
				"Don't barf at behavior: url(#default#behaviorName) - T162973",
				[ 'foo { behavior: url(#default#bar); }', false, '/w/', false ],
				'foo { behavior: url("#default#bar"); }',
			],
			[
				'Keeps anchors',
				[ 'url(#other)', false, '/', false ],
				'url("#other")'
			],
			[
				'Keeps anchors after a path',
				[ 'url(images/file.svg#id)', false, '/', false ],
				'url("/images/file.svg#id")'
			],
		];
	}

	/**
	 * Cases with empty url() for CSSMin::remap.
	 *
	 * Regression test for T191237.
	 *
	 * @dataProvider provideRemapEmptyUrl
	 * @covers CSSMin
	 */
	public function testRemapEmptyUrl( $params, $expected ) {
		$remapped = call_user_func_array( 'CSSMin::remap', $params );
		$this->assertEquals( $expected, $remapped, 'Ignore empty url' );
	}

	public static function provideRemapEmptyUrl() {
		return [
			'Empty' => [
				[ "background-image: url();", false, '/example', false ],
				"background-image: url();",
			],
			'Single quote' => [
				[ "background-image: url('');", false, '/example', false ],
				"background-image: url('');",
			],
			'Double quote' => [
				[ 'background-image: url("");', false, '/example', false ],
				'background-image: url("");',
			],
			'Single quote with outer spacing' => [
				[ "background-image: url( '' );", false, '/example', false ],
				"background-image: url( '' );",
			],
		];
	}

	/**
	 * This tests the basic functionality of CSSMin::remap.
	 *
	 * @see testRemap for testing of funky parameters
	 * @dataProvider provideRemapRemappingCases
	 * @covers CSSMin
	 */
	public function testRemapRemapping( $message, $input, $expectedOutput ) {
		$localPath = __DIR__ . '/../../data/cssmin';
		$remotePath = 'http://localhost/w';

		$realOutput = CSSMin::remap( $input, $localPath, $remotePath );
		$this->assertEquals( $expectedOutput, $realOutput, "CSSMin::remap: $message" );
	}

	public static function provideRemapRemappingCases() {
		// red.gif and green.gif are one-pixel 35-byte GIFs.
		// large.png is a 35K PNG that should be non-embeddable.
		// Full paths start with http://localhost/w/.
		// Timestamps in output are replaced with 'timestamp'.

		// data: URIs for red.gif, green.gif, circle.svg
		$red   = 'data:image/gif;base64,R0lGODlhAQABAIAAAP8AADAAACwAAAAAAQABAAACAkQBADs=';
		$green = 'data:image/gif;base64,R0lGODlhAQABAIAAAACAADAAACwAAAAAAQABAAACAkQBADs=';
		$svg = 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%228'
			. '%22 height=%228%22 viewBox=%220 0 8 8%22%3E %3Ccircle cx=%224%22 cy=%224%22 '
			. 'r=%222%22/%3E %3Ca xmlns:xlink=%22http://www.w3.org/1999/xlink%22 xlink:title='
			. '%22%3F%3E%22%3Etest%3C/a%3E %3C/svg%3E';

		// phpcs:disable Generic.Files.LineLength
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
				'foo { background: url(https://expand.example/static/foo.png); }',
			],
			[
				'Domain-relative URL with query',
				'foo { background: url(/static/foo.png?query=yes); }',
				'foo { background: url(https://expand.example/static/foo.png?query=yes); }',
			],
			[
				'Path-relative URL with query',
				"foo { background: url(?query=yes); }",
				'foo { background: url(http://localhost/w/?query=yes); }',
			],
			[
				'Remote URL (unnecessary quotes not preserved)',
				'foo { background: url("http://example.org/w/unnecessary-quotes.png"); }',
				'foo { background: url(http://example.org/w/unnecessary-quotes.png); }',
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
				"foo { background: url(\"$svg\"); }",
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
				'@import url(https://expand.example/styles.css)',
			],
			[
				'@import rule to local file (should we remap this?)',
				'@import url(/styles.css)',
				'@import url(https://expand.example/styles.css)',
			],
			[
				'@import rule to URL',
				'@import url(//localhost/styles.css?query=val)',
				'@import url(//localhost/styles.css?query=val)',
			],
			[
				'Background URL (double quotes)',
				'foo { background: url("//localhost/styles.css?quoted=double") }',
				'foo { background: url(//localhost/styles.css?quoted=double) }',
			],
			[
				'Background URL (single quotes)',
				'foo { background: url(\'//localhost/styles.css?quoted=single\') }',
				'foo { background: url(//localhost/styles.css?quoted=single) }',
			],
			[
				'Background URL (double quoted, containing parentheses; T60473)',
				'foo { background: url("//localhost/styles.css?query=(parens)") }',
				'foo { background: url("//localhost/styles.css?query=(parens)") }',
			],
			[
				'Background URL (double quoted, containing single quotes; T60473)',
				'foo { background: url("//localhost/styles.css?quote=\'") }',
				'foo { background: url("//localhost/styles.css?quote=\'") }',
			],
			[
				'Background URL (single quoted, containing double quotes; T60473)',
				'foo { background: url(\'//localhost/styles.css?quote="\') }',
				'foo { background: url("//localhost/styles.css?quote=\"") }',
			],
			[
				'Background URL (double quoted with outer spacing)',
				'foo { background: url( "http://localhost/styles.css?quoted=double" ) }',
				'foo { background: url(http://localhost/styles.css?quoted=double) }',
			],
			[
				'Background URL (single quoted, containing spaces, with outer spacing)',
				"foo { background: url( ' red.gif ' ); }",
				'foo { background: url("http://localhost/w/ red.gif "); }',
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
		// phpcs:enable
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
	 * Separated because they are currently broken (T37492)
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
