<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguagePl.php */
class LanguagePlTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'pl' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPluralFourForms */
	function testPluralFourForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPluralFourForms() {
		return array (
			array( 'many',  0 ),
			array( 'one',   1 ),
			array( 'few',   2 ),
			array( 'few',   3 ),
			array( 'few',   4 ),
			array( 'many',  5 ),
			array( 'many',  9 ),
			array( 'many',  10 ),
			array( 'many',  11 ),
			array( 'many',  21 ),
			array( 'few',   22 ),
			array( 'few',   23 ),
			array( 'few',   24 ),
			array( 'many',  25 ),
			array( 'many',  200 ),
			array( 'many',  201 ),
		);
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'many' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'many',  0 ),
			array( 'one',   1 ),
			array( 'many',  2 ),
			array( 'many',  3 ),
			array( 'many',  4 ),
			array( 'many',  5 ),
			array( 'many',  9 ),
			array( 'many',  10 ),
			array( 'many',  11 ),
			array( 'many',  21 ),
			array( 'many',  22 ),
			array( 'many',  23 ),
			array( 'many',  24 ),
			array( 'many',  25 ),
			array( 'many',  200 ),
			array( 'many',  201 ),
		);
	}
}
