<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGa.php */
class LanguageGaTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'ga' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms =  array( 'one', 'two', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'other', 200 ),
		);
	}

}
