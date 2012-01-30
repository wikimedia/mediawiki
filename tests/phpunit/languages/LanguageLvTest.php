<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageLv.php */
class LanguageLvTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'lv' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms =  array( 'one', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'other', 0 ), #this must be zero form as per CLDR
			array( 'one', 1 ),
			array( 'other', 11 ),
			array( 'one', 21 ),
			array( 'other', 411 ),
			array( 'other', 12.345 ),
			array( 'other', 20 ),
			array( 'one', 31 ),
			array( 'other', 200 ),
		);
	}

}
