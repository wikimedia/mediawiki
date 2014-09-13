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

		$this->setMwGlobals( array(
			'wgServer' => $server,
			'wgCanonicalServer' => $server,
		) );
	}

	/**
	 * @dataProvider provideMinifyCases
	 * @covers CSSMin::minify
	 */
	public function testMinify( $code, $expectedOutput ) {
		$minified = CSSMin::minify( $code );

		$this->assertEquals( $expectedOutput, $minified, 'Minified output should be in the form expected.' );
	}

	public static function provideMinifyCases() {
		return array(
			// Whitespace
			array( "\r\t\f \v\n\r", "" ),
			array( "foo, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ),

			// Loose comments
			array( "/* foo */", "" ),
			array( "/*******\n foo\n *******/", "" ),
			array( "/*!\n foo\n */", "" ),

			// Inline comments in various different places
			array( "/* comment */foo, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ),
			array( "foo/* comment */, bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ),
			array( "foo,/* comment */ bar {\n\tprop: value;\n}", "foo,bar{prop:value}" ),
			array( "foo, bar/* comment */ {\n\tprop: value;\n}", "foo,bar{prop:value}" ),
			array( "foo, bar {\n\t/* comment */prop: value;\n}", "foo,bar{prop:value}" ),
			array( "foo, bar {\n\tprop: /* comment */value;\n}", "foo,bar{prop:value}" ),
			array( "foo, bar {\n\tprop: value /* comment */;\n}", "foo,bar{prop:value }" ),
			array( "foo, bar {\n\tprop: value; /* comment */\n}", "foo,bar{prop:value; }" ),

			// Keep track of things that aren't as minified as much as they
			// could be (bug 35493)
			array( 'foo { prop: value ;}', 'foo{prop:value }' ),
			array( 'foo { prop : value; }', 'foo{prop :value}' ),
			array( 'foo { prop: value ; }', 'foo{prop:value }' ),
			array( 'foo { font-family: "foo" , "bar"; }', 'foo{font-family:"foo" ,"bar"}' ),
			array( "foo { src:\n\turl('foo') ,\n\turl('bar') ; }", "foo{src:url('foo') ,url('bar') }" ),

			// Interesting cases with string values
			// - Double quotes, single quotes
			array( 'foo { content: ""; }', 'foo{content:""}' ),
			array( "foo { content: ''; }", "foo{content:''}" ),
			array( 'foo { content: "\'"; }', 'foo{content:"\'"}' ),
			array( "foo { content: '\"'; }", "foo{content:'\"'}" ),
			// - Whitespace in string values
			array( 'foo { content: " "; }', 'foo{content:" "}' ),
		);
	}

	/**
	 * This tests funky parameters to CSSMin::remap. testRemapRemapping tests the basic functionality.
	 *
	 * @dataProvider provideRemapCases
	 * @covers CSSMin::remap
	 */
	public function testRemap( $message, $params, $expectedOutput ) {
		$remapped = call_user_func_array( 'CSSMin::remap', $params );

		$messageAdd = " Case: $message";
		$this->assertEquals( $expectedOutput, $remapped, 'CSSMin::remap should return the expected url form.' . $messageAdd );
	}

	public static function provideRemapCases() {
		// Parameter signature:
		// CSSMin::remap( $code, $local, $remote, $embedData = true )
		return array(
			array(
				'Simple case',
				array( 'foo { prop: url(bar.png); }', false, 'http://example.org', false ),
				'foo { prop: url(http://example.org/bar.png); }',
			),
			array(
				'Without trailing slash',
				array( 'foo { prop: url(../bar.png); }', false, 'http://example.org/quux', false ),
				'foo { prop: url(http://example.org/quux/../bar.png); }',
			),
			array(
				'With trailing slash on remote (bug 27052)',
				array( 'foo { prop: url(../bar.png); }', false, 'http://example.org/quux/', false ),
				'foo { prop: url(http://example.org/quux/../bar.png); }',
			),
			array(
				'Guard against stripping double slashes from query',
				array( 'foo { prop: url(bar.png?corge=//grault); }', false, 'http://example.org/quux/', false ),
				'foo { prop: url(http://example.org/quux/bar.png?corge=//grault); }',
			),
			array(
				'Expand absolute paths',
				array( 'foo { prop: url(/w/skin/images/bar.png); }', false, 'http://example.org/quux', false ),
				'foo { prop: url(http://doc.example.org/w/skin/images/bar.png); }',
			),
		);
	}

	/**
	 * This tests basic functionality of CSSMin::remap. testRemapRemapping tests funky parameters.
	 *
	 * @dataProvider provideRemapRemappingCases
	 * @covers CSSMin::remap
	 */
	public function testRemapRemapping( $message, $input, $expectedOutput ) {
		$localPath = __DIR__ . '/../../data/cssmin/';
		$remotePath = 'http://localhost/w/';

		$realOutput = CSSMin::remap( $input, $localPath, $remotePath );

		$this->assertEquals(
			$expectedOutput,
			preg_replace( '/\d+-\d+-\d+T\d+:\d+:\d+Z/', 'timestamp', $realOutput ),
			"CSSMin::remap: $message"
		);
	}

	public static function provideRemapRemappingCases() {
		// red.gif and green.gif are one-pixel 35-byte GIFs.
		// large.png is a 35K PNG that should be non-embeddable.
		// Full paths start with http://localhost/w/.
		// Timestamps in output are replaced with 'timestamp'.

		// data: URIs for red.gif and green.gif
		$red   = 'data:image/gif;base64,R0lGODlhAQABAIAAAP8AADAAACwAAAAAAQABAAACAkQBADs=';
		$green = 'data:image/gif;base64,R0lGODlhAQABAIAAAACAADAAACwAAAAAAQABAAACAkQBADs=';

		return array(
			array(
				'Regular file',
				'foo { background: url(red.gif); }',
				'foo { background: url(http://localhost/w/red.gif?timestamp); }',
			),
			array(
				'Regular file (missing)',
				'foo { background: url(theColorOfHerHair.gif); }',
				'foo { background: url(http://localhost/w/theColorOfHerHair.gif); }',
			),
			array(
				'Remote URL',
				'foo { background: url(http://example.org/w/foo.png); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			),
			array(
				'Protocol-relative remote URL',
				'foo { background: url(//example.org/w/foo.png); }',
				'foo { background: url(//example.org/w/foo.png); }',
			),
			array(
				'Remote URL with query',
				'foo { background: url(http://example.org/w/foo.png?query=yes); }',
				'foo { background: url(http://example.org/w/foo.png?query=yes); }',
			),
			array(
				'Protocol-relative remote URL with query',
				'foo { background: url(//example.org/w/foo.png?query=yes); }',
				'foo { background: url(//example.org/w/foo.png?query=yes); }',
			),
			array(
				'Domain-relative URL',
				'foo { background: url(/static/foo.png); }',
				'foo { background: url(http://doc.example.org/static/foo.png); }',
			),
			array(
				'Domain-relative URL with query',
				'foo { background: url(/static/foo.png?query=yes); }',
				'foo { background: url(http://doc.example.org/static/foo.png?query=yes); }',
			),
			array(
				'Remote URL (unnecessary quotes not preserved)',
				'foo { background: url("http://example.org/w/foo.png"); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			),
			array(
				'Embedded file',
				'foo { /* @embed */ background: url(red.gif); }',
				"foo { background: url($red); background: url(http://localhost/w/red.gif?timestamp)!ie; }",
			),
			array(
				'Can not embed remote URLs',
				'foo { /* @embed */ background: url(http://example.org/w/foo.png); }',
				'foo { background: url(http://example.org/w/foo.png); }',
			),
			array(
				'Embedded file (inline @embed)',
				'foo { background: /* @embed */ url(red.gif); }',
				"foo { background: url($red); background: url(http://localhost/w/red.gif?timestamp)!ie; }",
			),
			array(
				'Can not embed large files',
				'foo { /* @embed */ background: url(large.png); }',
				"foo { background: url(http://localhost/w/large.png?timestamp); }",
			),
			array(
				'Two regular files in one rule',
				'foo { background: url(red.gif), url(green.gif); }',
				'foo { background: url(http://localhost/w/red.gif?timestamp), url(http://localhost/w/green.gif?timestamp); }',
			),
			array(
				'Two embedded files in one rule',
				'foo { /* @embed */ background: url(red.gif), url(green.gif); }',
				"foo { background: url($red), url($green); background: url(http://localhost/w/red.gif?timestamp), url(http://localhost/w/green.gif?timestamp)!ie; }",
			),
			array(
				'Two embedded files in one rule (inline @embed)',
				'foo { background: /* @embed */ url(red.gif), /* @embed */ url(green.gif); }',
				"foo { background: url($red), url($green); background: url(http://localhost/w/red.gif?timestamp), url(http://localhost/w/green.gif?timestamp)!ie; }",
			),
			array(
				'Two embedded files in one rule (inline @embed), one too large',
				'foo { background: /* @embed */ url(red.gif), /* @embed */ url(large.png); }',
				"foo { background: url($red), url(http://localhost/w/large.png?timestamp); background: url(http://localhost/w/red.gif?timestamp), url(http://localhost/w/large.png?timestamp)!ie; }",
			),
			array(
				'Practical example with some noise',
				'foo { /* @embed */ background: #f9f9f9 url(red.gif) 0 0 no-repeat; }',
				"foo { background: #f9f9f9 url($red) 0 0 no-repeat; background: #f9f9f9 url(http://localhost/w/red.gif?timestamp) 0 0 no-repeat!ie; }",
			),
			array(
				'Does not mess with other properties',
				'foo { color: red; background: url(red.gif); font-size: small; }',
				'foo { color: red; background: url(http://localhost/w/red.gif?timestamp); font-size: small; }',
			),
			array(
				'Spacing and miscellanea not changed (1)',
				'foo {   background:    url(red.gif);  }',
				'foo {   background:    url(http://localhost/w/red.gif?timestamp);  }',
			),
			array(
				'Spacing and miscellanea not changed (2)',
				'foo {background:url(red.gif)}',
				'foo {background:url(http://localhost/w/red.gif?timestamp)}',
			),
			array(
				'Spaces within url() parentheses are ignored',
				'foo { background: url( red.gif ); }',
				'foo { background: url(http://localhost/w/red.gif?timestamp); }',
			),
			array(
				'@import rule to local file (should we remap this?)',
				'@import url(/styles.css)',
				'@import url(http://doc.example.org/styles.css)',
			),
			array(
				'@import rule to URL (should we remap this?)',
				'@import url(//localhost/styles.css?query=yes)',
				'@import url(//localhost/styles.css?query=yes)',
			),
		);
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
		return array(
			array(
				'Full URL',
				'scheme://user@domain:port/~user/fi%20le.png?query=yes&really=y+s',
				'url(scheme://user@domain:port/~user/fi%20le.png?query=yes&really=y+s)',
			),
			array(
				'data: URI',
				'data:image/png;base64,R0lGODlh/+==',
				'url(data:image/png;base64,R0lGODlh/+==)',
			),
			array(
				'URL with quotes',
				"https://en.wikipedia.org/wiki/Wendy's",
				"url(\"https://en.wikipedia.org/wiki/Wendy's\")",
			),
			array(
				'URL with parentheses',
				'https://en.wikipedia.org/wiki/Boston_(band)',
				'url("https://en.wikipedia.org/wiki/Boston_(band)")',
			),
		);
	}

	/**
	 * Seperated because they are currently broken (bug 35492)
	 *
	 * @group Broken
	 * @dataProvider provideStringCases
	 * @covers CSSMin::remap
	 */
	public function testMinifyWithCSSStringValues( $code, $expectedOutput ) {
		$this->testMinifyOutput( $code, $expectedOutput );
	}

	public static function provideStringCases() {
		return array(
			// String values should be respected
			// - More than one space in a string value
			array( 'foo { content: "  "; }', 'foo{content:"  "}' ),
			// - Using a tab in a string value (turns into a space)
			array( "foo { content: '\t'; }", "foo{content:'\t'}" ),
			// - Using css-like syntax in string values
			array( 'foo::after { content: "{;}"; position: absolute; }', 'foo::after{content:"{;}";position:absolute}' ),
		);
	}
}
