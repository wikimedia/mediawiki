<?php
/**
 * @author Amir E. Aharoni
 * based on LanguageBe_tarask.php
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageUk.php */
class LanguageUkTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Uk' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providePluralFourForms */
	function testPluralFourForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providePluralFourForms() {
		return array (
			array( 'one', 1 ),
			array( 'many', 11 ),
			array( 'one', 91 ),
			array( 'one', 121 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'few', 334 ),
			array( 'many', 5 ),
			array( 'many', 15 ),
			array( 'many', 120 ),
		);
	}
	/** @dataProvider providePluralTwoForms */
	function testPluralTwoForms( $result, $value ) {
		$forms = array( 'one', 'several' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}
	function providePluralTwoForms() {
		return array (
			array( 'one', 1 ),
			array( 'several', 11 ),
			array( 'several', 91 ),
			array( 'several', 121 ),
		);
	}
}
