<?php

class JavaScriptMinifierTest extends PHPUnit_Framework_TestCase {

	public static function provideCases() {
		return array(

			// Basic whitespace and comments that should be stripped entirely
			array( "\r\t\f \v\n\r", "" ),
			array( "/* Foo *\n*bar\n*/", "" ),

			/**
			 * Slashes used inside block comments (bug 26931).
			 * At some point there was a bug that caused this comment to be ended at '* /',
			 * causing /M... to be left as the beginning of a regex.
			 */
			array(
				"/**\n * Foo\n * {\n * 'bar' : {\n * "
					. "//Multiple rules with configurable operators\n * 'baz' : false\n * }\n */",
				"" ),

			/**
			 * '  Foo \' bar \
			 *  baz \' quox '  .
			 */
			array(
				"'  Foo  \\'  bar  \\\n  baz  \\'  quox  '  .length",
				"'  Foo  \\'  bar  \\\n  baz  \\'  quox  '.length"
			),
			array(
				"\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \"  .length",
				"\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \".length"
			),
			array( "// Foo b/ar baz", "" ),
			array(
				"/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /  .length",
				"/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /.length"
			),

			// HTML comments
			array( "<!-- Foo bar", "" ),
			array( "<!-- Foo --> bar", "" ),
			array( "--> Foo", "" ),
			array( "x --> y", "x-->y" ),

			// Semicolon insertion
			array( "(function(){return\nx;})", "(function(){return\nx;})" ),
			array( "throw\nx;", "throw\nx;" ),
			array( "while(p){continue\nx;}", "while(p){continue\nx;}" ),
			array( "while(p){break\nx;}", "while(p){break\nx;}" ),
			array( "var\nx;", "var x;" ),
			array( "x\ny;", "x\ny;" ),
			array( "x\n++y;", "x\n++y;" ),
			array( "x\n!y;", "x\n!y;" ),
			array( "x\n{y}", "x\n{y}" ),
			array( "x\n+y;", "x+y;" ),
			array( "x\n(y);", "x(y);" ),
			array( "5.\nx;", "5.\nx;" ),
			array( "0xFF.\nx;", "0xFF.x;" ),
			array( "5.3.\nx;", "5.3.x;" ),

			// Semicolon insertion between an expression having an inline
			// comment after it, and a statement on the next line (bug 27046).
			array(
				"var a = this //foo bar \n for ( b = 0; c < d; b++ ) {}",
				"var a=this\nfor(b=0;c<d;b++){}"
			),

			// Token separation
			array( "x  in  y", "x in y" ),
			array( "/x/g  in  y", "/x/g in y" ),
			array( "x  in  30", "x in 30" ),
			array( "x  +  ++  y", "x+ ++y" ),
			array( "x ++  +  y", "x++ +y" ),
			array( "x  /  /y/.exec(z)", "x/ /y/.exec(z)" ),

			// State machine
			array( "/  x/g", "/  x/g" ),
			array( "(function(){return/  x/g})", "(function(){return/  x/g})" ),
			array( "+/  x/g", "+/  x/g" ),
			array( "++/  x/g", "++/  x/g" ),
			array( "x/  x/g", "x/x/g" ),
			array( "(/  x/g)", "(/  x/g)" ),
			array( "if(/  x/g);", "if(/  x/g);" ),
			array( "(x/  x/g)", "(x/x/g)" ),
			array( "([/  x/g])", "([/  x/g])" ),
			array( "+x/  x/g", "+x/x/g" ),
			array( "{}/  x/g", "{}/  x/g" ),
			array( "+{}/  x/g", "+{}/x/g" ),
			array( "(x)/  x/g", "(x)/x/g" ),
			array( "if(x)/  x/g", "if(x)/  x/g" ),
			array( "for(x;x;{}/  x/g);", "for(x;x;{}/x/g);" ),
			array( "x;x;{}/  x/g", "x;x;{}/  x/g" ),
			array( "x:{}/  x/g", "x:{}/  x/g" ),
			array( "switch(x){case y?z:{}/  x/g:{}/  x/g;}", "switch(x){case y?z:{}/x/g:{}/  x/g;}" ),
			array( "function x(){}/  x/g", "function x(){}/  x/g" ),
			array( "+function x(){}/  x/g", "+function x(){}/x/g" ),

			// Multiline quoted string
			array( "var foo=\"\\\nblah\\\n\";", "var foo=\"\\\nblah\\\n\";" ),

			// Multiline quoted string followed by string with spaces
			array(
				"var foo=\"\\\nblah\\\n\";\nvar baz = \" foo \";\n",
				"var foo=\"\\\nblah\\\n\";var baz=\" foo \";"
			),

			// URL in quoted string ( // is not a comment)
			array(
				"aNode.setAttribute('href','http://foo.bar.org/baz');",
				"aNode.setAttribute('href','http://foo.bar.org/baz');"
			),

			// URL in quoted string after multiline quoted string
			array(
				"var foo=\"\\\nblah\\\n\";\naNode.setAttribute('href','http://foo.bar.org/baz');",
				"var foo=\"\\\nblah\\\n\";aNode.setAttribute('href','http://foo.bar.org/baz');"
			),

			// Division vs. regex nastiness
			array(
				"alert( (10+10) / '/'.charCodeAt( 0 ) + '//' );",
				"alert((10+10)/'/'.charCodeAt(0)+'//');"
			),
			array( "if(1)/a /g.exec('Pa ss');", "if(1)/a /g.exec('Pa ss');" ),

			// newline insertion after 1000 chars: break after the "++", not before
			array( str_repeat( ';', 996 ) . "if(x++);", str_repeat( ';', 996 ) . "if(x++\n);" ),

			// Unicode letter characters should pass through ok in identifiers (bug 31187)
			array( "var KaŝSkatolVal = {}", 'var KaŝSkatolVal={}' ),

			// Per spec unicode char escape values should work in identifiers,
			// as long as it's a valid char. In future it might get normalized.
			array( "var Ka\\u015dSkatolVal = {}", 'var Ka\\u015dSkatolVal={}' ),

			// Some structures that might look invalid at first sight
			array( "var a = 5.;", "var a=5.;" ),
			array( "5.0.toString();", "5.0.toString();" ),
			array( "5..toString();", "5..toString();" ),
			array( "5...toString();", false ),
			array( "5.\n.toString();", '5..toString();' ),
		);
	}

	/**
	 * @dataProvider provideCases
	 * @covers JavaScriptMinifier::minify
	 */
	public function testJavaScriptMinifierOutput( $code, $expectedOutput ) {
		$minified = JavaScriptMinifier::minify( $code );

		// JSMin+'s parser will throw an exception if output is not valid JS.
		// suppression of warnings needed for stupid crap
		wfSuppressWarnings();
		$parser = new JSParser();
		wfRestoreWarnings();
		$parser->parse( $minified, 'minify-test.js', 1 );

		$this->assertEquals(
			$expectedOutput,
			$minified,
			"Minified output should be in the form expected."
		);
	}

	public static function provideExponentLineBreaking() {
		return array(
			array(
				// This one gets interpreted all together by the prior code;
				// no break at the 'E' happens.
				'1.23456789E55',
			),
			array(
				// This one breaks under the bad code; splits between 'E' and '+'
				'1.23456789E+5',
			),
			array(
				// This one breaks under the bad code; splits between 'E' and '-'
				'1.23456789E-5',
			),
		);
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
