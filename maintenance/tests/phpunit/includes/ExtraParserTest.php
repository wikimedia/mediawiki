<?php
/**
 * Parser-related tests that don't suit for parserTests.txt
 */

class ExtraParserTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		global $wgMemc;
		global $wgContLang;
		global $wgShowDBErrorBacktrace;

		$wgShowDBErrorBacktrace = true;
		if ( $wgContLang === null ) $wgContLang = new Language;
		$wgMemc = new FakeMemCachedClient;
	}

	// Bug 8689 - Long numeric lines kill the parser
	function testBug8689() {
		global $wgLang;
		global $wgUser;
		$longLine = '1.' . str_repeat( '1234567890', 100000 ) . "\n";

		if ( $wgLang === null ) $wgLang = new Language;
		$parser = new Parser();
		$t = Title::newFromText( 'Unit test' );
		$options = ParserOptions::newFromUser( $wgUser );
		$this->assertEquals( "<p>$longLine</p>",
			$parser->parse( $longLine, $t, $options )->getText() );
	}
 }
