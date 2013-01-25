<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageHe.php */
class LanguageHeTest extends LanguageClassesTestCase {

	/** @dataProvider providePlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'two', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/** @dataProvider providePlural */
	function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	function providePlural() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'other', 3 ),
			array( 'other', 10 ),
		);
	}

	/** @dataProvider provideGrammar */
	function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	// The comments in the beginning of the line help avoid RTL problems
	// with text editors.
	function provideGrammar() {
		return array (
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
