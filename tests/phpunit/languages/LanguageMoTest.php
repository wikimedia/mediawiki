<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageMo.php */
class LanguageMoTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'mo' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms =  array( 'one', 'few', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}


	function providerPlural() {
		return array (
			array( 'few', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 19 ),
			array( 'few', 119 ),
			array( 'other', 20 ),
			array( 'other', 20.123 ),
			array( 'other', 31 ),
			array( 'other', 200 ),
		);
	}


}
