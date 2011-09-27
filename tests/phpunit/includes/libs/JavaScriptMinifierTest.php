<?php

class JavaScriptMinifierTest extends MediaWikiTestCase {

	function provideCases() {
		return array(
			// Basic tokens
			array( "\r\t\f \v\n\r", "" ),
			array( "/* Foo *\n*bar\n*/", "" ),
			/**
			 * '  Foo \' bar \
			 *  baz \' quox '  .
			 */
			array( "'  Foo  \\'  bar  \\\n  baz  \\'  quox  '  .length", "'  Foo  \\'  bar  \\\n  baz  \\'  quox  '.length" ),
			array( "\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \"  .length", "\"  Foo  \\\"  bar  \\\n  baz  \\\"  quox  \".length" ),
			array( "// Foo b/ar baz", "" ),
			array( "/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /  .length", "/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /.length" ),
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
			// Token separation
			array( "x  in  y", "x in y" ),
			array( "/x/g  in  y", "/x/g in y" ),
			array( "x  in  30", "x in 30" ),
			array( "x  +  ++  y", "x+ ++y" ),
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
			
			// Tests for things that broke in the past
			// Multiline quoted string
			array( "var foo=\"\\\nblah\\\n\";", "var foo=\"\\\nblah\\\n\";" ),
			// Multiline quoted string followed by string with spaces
			array( "var foo=\"\\\nblah\\\n\";\nvar baz = \" foo \";\n", "var foo=\"\\\nblah\\\n\";var baz=\" foo \";" ),
			// URL in quoted string ( // is not a comment)
			array( "aNode.setAttribute('href','http://foo.bar.org/baz');", "aNode.setAttribute('href','http://foo.bar.org/baz');" ),
			// URL in quoted string after multiline quoted string
			array( "var foo=\"\\\nblah\\\n\";\naNode.setAttribute('href','http://foo.bar.org/baz');", "var foo=\"\\\nblah\\\n\";aNode.setAttribute('href','http://foo.bar.org/baz');" ),
			// Division vs. regex nastiness
			array( "alert( (10+10) / '/'.charCodeAt( 0 ) + '//' );", "alert((10+10)/'/'.charCodeAt(0)+'//');" ),
			array( "if(1)/a /g.exec('Pa ss');", "if(1)/a /g.exec('Pa ss');" ),
			
			// newline insertion after 1000 chars: break after the "++", not before
			array( str_repeat( ';', 996 ) . "if(x++);", str_repeat( ';', 996 ) . "if(x++\n);" ),

			// Unicode letter characters should pass through ok in identifiers (bug 31187)
			array( "var KaŝSkatolVal = {}", 'var KaŝSkatolVal={}'),
			// And also per spec unicode char escape values should work in identifiers,
			// as long as it's a valid char. In future it might get normalized.
			array( "var Ka\\u015dSkatolVal = {}", 'var Ka\\u015dSkatolVal={}'),
		);
	}

	/**
	 * @dataProvider provideCases
	 */
	function testJavaScriptMinifierOutput( $code, $expectedOutput ) {
		$minified = JavaScriptMinifier::minify( $code );

		// JSMin+'s parser will throw an exception if output is not valid JS.
		// suppression of warnings needed for stupid crap
		wfSuppressWarnings();
		$parser = new JSParser();
		wfRestoreWarnings();
		$parser->parse( $minified, 'minify-test.js', 1 );

		$this->assertEquals( $expectedOutput, $minified, "Minified output should be in the form expected." );
	}
}
