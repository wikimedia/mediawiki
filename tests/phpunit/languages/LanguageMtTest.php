<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageMt.php */
class LanguageMtTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'mt' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPluralAllForms */
	function testPluralAllForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPluralAllForms() {
		return array (
			array( 'few',   0 ),
			array( 'one',   1 ),
			array( 'few',   2 ),
			array( 'few',   10 ),
			array( 'many',  11 ),
			array( 'many',  19 ),
			array( 'other', 20 ),
			array( 'other', 99 ),
			array( 'other', 100 ),
			array( 'other', 101 ),
			array( 'few',   102 ),
			array( 'few',   110 ),
			array( 'many',  111 ),
			array( 'many',  119 ),
			array( 'other', 120 ),
			array( 'other', 201 ),
		);
	}

	/** @dataProvider providerPluralTwoForms */
	function testPluralTwoForms( $result, $value ) {
		$forms = array( 'one', 'many' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPluralTwoForms() {
		return array (
			array( 'many',  0 ),
			array( 'one',   1 ),
			array( 'many',  2 ),
			array( 'many',  10 ),
			array( 'many',  11 ),
			array( 'many',  19 ),
			array( 'many',  20 ),
			array( 'many',  99 ),
			array( 'many',  100 ),
			array( 'many',  101 ),
			array( 'many',  102 ),
			array( 'many',  110 ),
			array( 'many',  111 ),
			array( 'many',  119 ),
			array( 'many',  120 ),
			array( 'many',  201 ),
		);
	}
}
