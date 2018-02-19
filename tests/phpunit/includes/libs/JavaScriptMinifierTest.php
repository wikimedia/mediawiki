<?php

class JavaScriptMinifierTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideCases() {
		return [

			// Basic whitespace and comments that should be stripped entirely
			[ "\r\t\f \v\n\r", "" ],
			[ "/* Foo *\n*bar\n*/", "" ],

			/**
			 * Slashes used inside block comments (T28931).
			 * At some point there was a bug that caused this comment to be ended at '* /',
			 * causing /M... to be left as the beginning of a regex.
			 */
			[
				"/**\n * Foo\n * {\n * 'bar' : {\n * "
					. "//Multiple rules with configurable operators\n * 'baz' : false\n * }\n */",
				"" ],

			/**
			 * '  Foo \' bar \
			 *  baz \' quox '  .
			 */
			[
				"'  Foo  \\'  bar  \\\n  baz  \\'  quox  '  .length",
				"'  Foo  \\'  bar  \\\n  baz  \\'  quox  '.length"
			],
			[
				"\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \"  .length",
				"\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \".length"
			],
			[ "// Foo b/ar baz", "" ],
			[
				"/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /  .length",
				"/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /.length"
			],

			// HTML comments
			[ "<!-- Foo bar", "" ],
			[ "<!-- Foo --> bar", "" ],
			[ "--> Foo", "" ],
			[ "x --> y", "x-->y" ],

			// Semicolon insertion
			[ "(function(){return\nx;})", "(function(){return\nx;})" ],
			[ "throw\nx;", "throw\nx;" ],
			[ "while(p){continue\nx;}", "while(p){continue\nx;}" ],
			[ "while(p){break\nx;}", "while(p){break\nx;}" ],
			[ "var\nx;", "var x;" ],
			[ "x\ny;", "x\ny;" ],
			[ "x\n++y;", "x\n++y;" ],
			[ "x\n!y;", "x\n!y;" ],
			[ "x\n{y}", "x\n{y}" ],
			[ "x\n+y;", "x+y;" ],
			[ "x\n(y);", "x(y);" ],
			[ "5.\nx;", "5.\nx;" ],
			[ "0xFF.\nx;", "0xFF.x;" ],
			[ "5.3.\nx;", "5.3.x;" ],

			// Cover failure case for incomplete hex literal
			[ "0x;", false, false ],

			// Cover failure case for number with no digits after E
			[ "1.4E", false, false ],

			// Cover failure case for number with several E
			[ "1.4EE2", false, false ],
			[ "1.4EE", false, false ],

			// Cover failure case for number with several E (nonconsecutive)
			// FIXME: This is invalid, but currently tolerated
			[ "1.4E2E3", "1.4E2 E3", false ],

			// Semicolon insertion between an expression having an inline
			// comment after it, and a statement on the next line (T29046).
			[
				"var a = this //foo bar \n for ( b = 0; c < d; b++ ) {}",
				"var a=this\nfor(b=0;c<d;b++){}"
			],

			// Cover failure case of incomplete regexp at end of file (T75556)
			// FIXME: This is invalid, but currently tolerated
			[ "*/", "*/", false ],

			// Cover failure case of incomplete char class in regexp (T75556)
			// FIXME: This is invalid, but currently tolerated
			[ "/a[b/.test", "/a[b/.test", false ],

			// Cover failure case of incomplete string at end of file (T75556)
			// FIXME: This is invalid, but currently tolerated
			[ "'a", "'a", false ],

			// Token separation
			[ "x  in  y", "x in y" ],
			[ "/x/g  in  y", "/x/g in y" ],
			[ "x  in  30", "x in 30" ],
			[ "x  +  ++  y", "x+ ++y" ],
			[ "x ++  +  y", "x++ +y" ],
			[ "x  /  /y/.exec(z)", "x/ /y/.exec(z)" ],

			// State machine
			[ "/  x/g", "/  x/g" ],
			[ "(function(){return/  x/g})", "(function(){return/  x/g})" ],
			[ "+/  x/g", "+/  x/g" ],
			[ "++/  x/g", "++/  x/g" ],
			[ "x/  x/g", "x/x/g" ],
			[ "(/  x/g)", "(/  x/g)" ],
			[ "if(/  x/g);", "if(/  x/g);" ],
			[ "(x/  x/g)", "(x/x/g)" ],
			[ "([/  x/g])", "([/  x/g])" ],
			[ "+x/  x/g", "+x/x/g" ],
			[ "{}/  x/g", "{}/  x/g" ],
			[ "+{}/  x/g", "+{}/x/g" ],
			[ "(x)/  x/g", "(x)/x/g" ],
			[ "if(x)/  x/g", "if(x)/  x/g" ],
			[ "for(x;x;{}/  x/g);", "for(x;x;{}/x/g);" ],
			[ "x;x;{}/  x/g", "x;x;{}/  x/g" ],
			[ "x:{}/  x/g", "x:{}/  x/g" ],
			[ "switch(x){case y?z:{}/  x/g:{}/  x/g;}", "switch(x){case y?z:{}/x/g:{}/  x/g;}" ],
			[ "function x(){}/  x/g", "function x(){}/  x/g" ],
			[ "+function x(){}/  x/g", "+function x(){}/x/g" ],

			// Multiline quoted string
			[ "var foo=\"\\\nblah\\\n\";", "var foo=\"\\\nblah\\\n\";" ],

			// Multiline quoted string followed by string with spaces
			[
				"var foo=\"\\\nblah\\\n\";\nvar baz = \" foo \";\n",
				"var foo=\"\\\nblah\\\n\";var baz=\" foo \";"
			],

			// URL in quoted string ( // is not a comment)
			[
				"aNode.setAttribute('href','http://foo.bar.org/baz');",
				"aNode.setAttribute('href','http://foo.bar.org/baz');"
			],

			// URL in quoted string after multiline quoted string
			[
				"var foo=\"\\\nblah\\\n\";\naNode.setAttribute('href','http://foo.bar.org/baz');",
				"var foo=\"\\\nblah\\\n\";aNode.setAttribute('href','http://foo.bar.org/baz');"
			],

			// Division vs. regex nastiness
			[
				"alert( (10+10) / '/'.charCodeAt( 0 ) + '//' );",
				"alert((10+10)/'/'.charCodeAt(0)+'//');"
			],
			[ "if(1)/a /g.exec('Pa ss');", "if(1)/a /g.exec('Pa ss');" ],

			// newline insertion after 1000 chars: break after the "++", not before
			[ str_repeat( ';', 996 ) . "if(x++);", str_repeat( ';', 996 ) . "if(x++\n);" ],

			// Unicode letter characters should pass through ok in identifiers (T33187)
			[ "var KaŝSkatolVal = {}", 'var KaŝSkatolVal={}' ],

			// Per spec unicode char escape values should work in identifiers,
			// as long as it's a valid char. In future it might get normalized.
			[ "var Ka\\u015dSkatolVal = {}", 'var Ka\\u015dSkatolVal={}' ],

			// Some structures that might look invalid at first sight
			[ "var a = 5.;", "var a=5.;" ],
			[ "5.0.toString();", "5.0.toString();" ],
			[ "5..toString();", "5..toString();" ],
			// Cover failure case for too many decimal points
			[ "5...toString();", false ],
			[ "5.\n.toString();", '5..toString();' ],

			// Boolean minification (!0 / !1)
			[ "var a = { b: true };", "var a={b:!0};" ],
			[ "var a = { true: 12 };", "var a={true:12};", false ],
			[ "a.true = 12;", "a.true=12;", false ],
			[ "a.foo = true;", "a.foo=!0;" ],
			[ "a.foo = false;", "a.foo=!1;" ],
		];
	}

	/**
	 * @dataProvider provideCases
	 * @covers JavaScriptMinifier::minify
	 * @covers JavaScriptMinifier::parseError
	 */
	public function testMinifyOutput( $code, $expectedOutput, $expectedValid = true ) {
		$minified = JavaScriptMinifier::minify( $code );

		// JSMin+'s parser will throw an exception if output is not valid JS.
		// suppression of warnings needed for stupid crap
		if ( $expectedValid ) {
			Wikimedia\suppressWarnings();
			$parser = new JSParser();
			Wikimedia\restoreWarnings();
			$parser->parse( $minified, 'minify-test.js', 1 );
		}

		$this->assertEquals(
			$expectedOutput,
			$minified,
			"Minified output should be in the form expected."
		);
	}

	public static function provideExponentLineBreaking() {
		return [
			[
				// This one gets interpreted all together by the prior code;
				// no break at the 'E' happens.
				'1.23456789E55',
			],
			[
				// This one breaks under the bad code; splits between 'E' and '+'
				'1.23456789E+5',
			],
			[
				// This one breaks under the bad code; splits between 'E' and '-'
				'1.23456789E-5',
			],
		];
	}

	/**
	 * @dataProvider provideExponentLineBreaking
	 * @covers JavaScriptMinifier::minify
	 */
	public function testExponentLineBreaking( $num ) {
		// Long line breaking was being incorrectly done between the base and
		// exponent part of a number, causing a syntax error. The line should
		// instead break at the start of the number. (T34548)
		$prefix = 'var longVarName' . str_repeat( '_', 973 ) . '=';
		$suffix = ',shortVarName=0;';

		$input = $prefix . $num . $suffix;
		$expected = $prefix . "\n" . $num . $suffix;

		$minified = JavaScriptMinifier::minify( $input );

		$this->assertEquals( $expected, $minified, "Line breaks must not occur in middle of exponent" );
	}
}
