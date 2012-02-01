<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSgs.php */
class LanguageSgsTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Sgs' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providePluralAllForms */
	function testPluralAllForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providePluralAllForms() {
		return array (
			array( 'many',  0 ),
			array( 'one',   1 ),
			array( 'few',   2 ),
			array( 'other', 3 ),
			array( 'many',  10 ),
			array( 'many',  11 ),
			array( 'many',  12 ),
			array( 'many',  19 ),
			array( 'other', 20 ),
			array( 'many',  100 ),
			array( 'one',   101 ),
			array( 'many',  111 ),
			array( 'many',  112 ),
		);
	}

	/** @dataProvider providePluralTwoForms */
	function testPluralTwoForms( $result, $value ) {
		$forms =  array( 'one', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providePluralTwoForms() {
		return array (
			array( 'other', 0 ),
			array( 'one',   1 ),
			array( 'other', 2 ),
			array( 'other', 3 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 12 ),
			array( 'other', 19 ),
			array( 'other', 20 ),
			array( 'other', 100 ),
			array( 'one',   101 ),
			array( 'other', 111 ),
			array( 'other', 112 ),
		);
	}
}
