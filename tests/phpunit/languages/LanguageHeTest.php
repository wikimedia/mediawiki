<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageHe.php */
class LanguageHeTest extends LanguageClassesTestCase {

	/** @dataProvider providerPluralDual */
	function testPluralDual( $result, $value ) {
		$forms = array( 'one', 'two', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providerPluralDual() {
		return array(
			array( 'other', 0 ), // Zero -> plural
			array( 'one', 1 ), // Singular
			array( 'two', 2 ), // Dual
			array( 'other', 3 ), // Plural
		);
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array(
			array( 'other', 0 ), // Zero -> plural
			array( 'one', 1 ), // Singular
			array( 'other', 2 ), // Plural, no dual provided
			array( 'other', 3 ), // Plural
		);
	}

	/** @dataProvider providerGrammar */
	function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	// The comments in the beginning of the line help avoid RTL problems
	// with text editors.
	function providerGrammar() {
		return array(
			array(
				/* result */ 'וויקיפדיה',
				/* word   */ 'ויקיפדיה',
				/* case   */ 'תחילית',
			),
			array(
				/* result */ 'וולפגנג',
				/* word   */ 'וולפגנג',
				/* case   */ 'prefixed',
			),
			array(
				/* result */ 'קובץ',
				/* word   */ 'הקובץ',
				/* case   */ 'תחילית',
			),
			array(
				/* result */ '־Wikipedia',
				/* word   */ 'Wikipedia',
				/* case   */ 'תחילית',
			),
			array(
				/* result */ '־1995',
				/* word   */ '1995',
				/* case   */ 'תחילית',
			),
		);
	}
}
