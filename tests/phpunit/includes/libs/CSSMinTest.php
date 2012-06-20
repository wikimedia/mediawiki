<?php
/**
 * This file test the CSSMin library shipped with Mediawiki.
 *
 * @author Timo Tijhof
 */

class CSSMinTest extends MediaWikiTestCase {
	protected $oldServer = null, $oldCanServer = null;

	function setUp() {
		parent::setUp();

		// Fake $wgServer and $wgCanonicalServer
		global $wgServer, $wgCanonicalServer;
		$this->oldServer = $wgServer;
		$this->oldCanServer = $wgCanonicalServer;
		$wgServer = $wgCanonicalServer = 'http://wiki.example.org';
	}

	function tearDown() {
		// Restore $wgServer and $wgCanonicalServer
		global $wgServer, $wgCanonicalServer;
		$wgServer = $this->oldServer;
		$wgCanonicalServer = $this->oldCanServer;

		parent::tearDown();
	}

	/**
	 * @dataProvider provideMinifyCases
	 */
	function testMinify( $code, $expectedOutput ) {
		$minified = CSSMin::minify( $code );

		$this->assertEquals( $expectedOutput, $minified, 'Minified output should be in the form expected.' );
	}

	function provideMinifyCases() {
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
	 * @dataProvider provideRemapCases
	 */
	function testRemap( $message, $params, $expectedOutput ) {
		$remapped = call_user_func_array( 'CSSMin::remap', $params );

		$messageAdd = " Case: $message";
		$this->assertEquals( $expectedOutput, $remapped, 'CSSMin::remap should return the expected url form.' . $messageAdd );
	}

	function provideRemapCases() {
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
				'foo { prop: url(http://wiki.example.org/w/skin/images/bar.png); }',
			),
		);
	}

	/**
	 * Seperated because they are currently broken (bug 35492)
	 *
	 * @group Broken
	 * @dataProvider provideStringCases
	 */
	function testMinifyWithCSSStringValues( $code, $expectedOutput ) {
		$this->testMinifyOutput( $code, $expectedOutput );
	}

	function provideStringCases() {
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
