<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSe.php */
class LanguageSeTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'se' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPluralThreeForms */
	function testPluralThreeForms( $result, $value ) {
		$forms = array( 'one', 'two', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPluralThreeForms() {
		return array (
			array( 'other', 0 ),
			array( 'one',   1 ),
			array( 'two',   2 ),
			array( 'other', 3 ),
		);
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'other', 0 ),
			array( 'one',   1 ),
			array( 'other', 2 ),
			array( 'other', 3 ),
		);
	}
}
