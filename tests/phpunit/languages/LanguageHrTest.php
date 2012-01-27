<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageHr.php */
class LanguageHrTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'hr' );
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
			array( 'many', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 4 ),
			array( 'many', 5 ),
			array( 'many', 11 ),
			array( 'many', 20 ),
			array( 'one', 21 ),
			array( 'few', 24 ),
			array( 'many', 25 ),
			array( 'many', 200 ),
		);
	}

}
