<?php

class TitleTest extends PHPUnit_Framework_TestCase {
	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );
	}

	function testLegalChars() {
		$titlechars = Title::legalChars();

		foreach ( range( 1, 255 ) as $num ) {
			$chr = chr( $num );
			if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
				$this->assertFalse( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is not a valid titlechar" );
			} else {
				$this->assertTrue( (bool)preg_match( "/[$titlechars]/", $chr ), "chr($num) = $chr is a valid titlechar" );
			}
		}
	}

	/**
	 * Test originally wrote to investigate bug 24343
	 * FIXME : some tests might fail depending on local settings.
	 */
	function testGetURLS() {
		global $wgArticlePath, $wgScript;
	
		$title = Title::newFromText( 'User:Bob#section' );
	
		$this->assertEquals( "$wgScript/User:Bob", $title->getLocalURL(),
			'Title::getLocalURL() does NOT have fragment' );
		$this->assertEquals( "$wgScript/User:Bob", $title->escapeLocalURL(),
			'Title::escapeLocalURL() does NOT have fragment' );
		$this->assertEquals( "$wgScript/User:Bob#section", $title->getLinkURL(),
			'Title::getLinkURL() does have fragment' );
		
		#$this->assertEquals( 'toto', $title->getFullURL()     );
		#$this->assertEquals( 'toto', $title->escapeFullURL()  );
	}
}
