<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageFr.php */
class LanguageFrTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'fr' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providePlural */
	function testPlural( $result, $value ) {
		$forms =  array( 'one', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providePlural() {
		return array (
			array( 'one', 0 ),
			array( 'one', 1 ),
			array( 'other', 2 ),
			array( 'other', 200 ),
		);
	}

}
