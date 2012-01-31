<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageHe.php */
class LanguageHeTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'he' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPluralDual */
	function testPluralDual( $result, $value ) {
		$forms = array( 'one', 'many', 'two' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPluralDual() {
		return array (
			array( 'many', 0 ), // Zero -> plural
			array( 'one', 1 ), // Singular
			array( 'two', 2 ), // Dual
			array( 'many', 3 ), // Plural
		);
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'many' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'many', 0 ), // Zero -> plural
			array( 'one', 1 ), // Singular
			array( 'many', 2 ), // Plural, no dual provided
			array( 'many', 3 ), // Plural
		);
	}
}
