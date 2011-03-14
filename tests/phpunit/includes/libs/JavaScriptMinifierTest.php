<?php

class JavaScriptMinifierTest extends MediaWikiTestCase {

	function provideCases() {
		return array(
			// Basic tokens
			array( "\r\t\f \v\n\r", "" ),
			array( "/* Foo *\n*bar\n*/", "" ),
			array( "'  Foo  \\'  bar  \\\n  baz  \\'  quox  '  .", "'  Foo  \\'  bar  \\\n  baz  \\'  quox  '." ),
			array( '\"  Foo  \\"  bar  \\\n  baz  \\"  quox  "  .', '\"  Foo  \\"  bar  \\\n  baz  \\"  quox  ".' ),
			array( "// Foo b/ar baz", "" ),
			array( "/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /  .", "/  Foo  \\/  bar  [  /  \\]  /  ]  baz  /." ),
			// HTML comments
			array( "<!-- Foo bar", "" ),
			array( "<!-- Foo --> bar", "" ),
			array( "--> Foo", "" ),
			array( "x --> y", "x-->y" ),
			// Semicolon insertion
			array( "return\nx;", "return\nx;" ),
			array( "throw\nx;", "throw\nx;" ),
			array( "continue\nx;", "continue\nx;" ),
			array( "break\nx;", "break\nx;" ),
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
			array( "return/  x/g", "return/  x/g" ),
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
			array( "+function x(){}/  x/g", "+function x(){}/x/g" )
		);
	}

	/**
	 * @dataProvider provideCases
	 */
	function testJavaScriptMinifierOutput( $code, $expectedOutput ) {
		$this->assertEquals( $expectedOutput, JavaScriptMinifier::minify( $code ) );
	}
}
