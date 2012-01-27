<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageCu.php */
class LanguageCuTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'cu' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms =  array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'many', 3 ),
			array( 'many', 4 ),
			array( 'other', 5 ),
			array( 'one', 11 ),
			array( 'other', 20 ),
			array( 'few', 22 ),
			array( 'many', 223 ),
			array( 'other', 200 ),
		);
	}

}
