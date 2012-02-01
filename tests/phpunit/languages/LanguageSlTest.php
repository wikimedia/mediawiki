<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Amir E. Aharoni
 * based on LanguageSkTest.php
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSl.php */
class LanguageSlTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'sl' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'two', 'few', 'other', 'zero' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'zero',  0 ),
			array( 'one',   1 ),
			array( 'two',   2 ),
			array( 'few',   3 ),
			array( 'few',   4 ),
			array( 'other', 5 ),
			array( 'other', 99 ),
			array( 'other', 100 ),
			array( 'one',   101 ),
			array( 'two',   102 ),
			array( 'few',   103 ),
			array( 'one',   201 ),
		);
	}
}
