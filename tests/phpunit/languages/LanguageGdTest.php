<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGd.php */
class LanguageGdTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'gd' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		// The CLDR ticket for this plural forms is not same as mw plural forms. See http://unicode.org/cldr/trac/ticket/2883
		$forms =  array( 'Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}
	function providerPlural() {
		return array (
			array( 'Form 6', 0 ),
			array( 'Form 1', 1 ),
			array( 'Form 2', 2 ),
			array( 'Form 3', 11 ),
			array( 'Form 4', 12 ),
			array( 'Form 5', 3 ),
			array( 'Form 5', 19 ),
			array( 'Form 6', 200 ),
		);
	}

}
