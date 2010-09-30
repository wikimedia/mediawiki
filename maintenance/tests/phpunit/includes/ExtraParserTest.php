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

	function tearDown() {
		global $wgMemc;

		$wgMemc = null;
	}

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
