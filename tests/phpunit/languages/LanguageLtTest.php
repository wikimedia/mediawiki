<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageLt.php */
class LanguageLtTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Lt' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider provideOneFewOtherCases */
	function testOneFewOtherPlural( $result, $value ) {
		$forms =  array( 'one', 'few', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}
	
	/** @dataProvider provideOneFewCases */
	function testOneFewPlural( $result, $value ) {
		$forms =  array( 'one', 'few' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function provideOneFewOtherCases() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 9 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 20 ),
			array( 'one', 21 ),
			array( 'few', 32 ),
			array( 'one', 41 ),
			array( 'one', 40001 ),
		);
	}
	
	function provideOneFewCases() {
		return array (
			array( 'one', 1 ),
			array( 'few', 15 ),
		);
	}
}
