<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Amir E. Aharoni
 * based on LanguageSkTest.php
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSk.php */
class LanguageSkTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'sk' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'few', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'other', 5 ),
			array( 'other', 11 ),
			array( 'other', 20 ),
			array( 'other', 25 ),
			array( 'other', 200 ),
		);
	}
}
