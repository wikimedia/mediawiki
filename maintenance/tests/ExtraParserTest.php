<?php
/**
 * Parser-related tests that don't suit for parserTests.txt
 */
 
 class ExtraParserTest extends PHPUnit_Framework_TestCase {

	// Bug 8689 - Long numeric lines kill the parser
	function testBug8689() {
		$longLine = '1.' . str_repeat( '1234567890', 100000 ) . "\n";

		$parser = new Parser();
		$t = Title::newFromText( 'Unit test' );
		$options = new ParserOptions();
		$this->assertEquals( "<p>$longLine</p>", 
			$parser->parse( $longLine, $t, $options )->getText() );
	}
 }