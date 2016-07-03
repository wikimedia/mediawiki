<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageHe.php */
class LanguageHeTest extends LanguageClassesTestCase {
	/**
	 * The most common usage for the plural forms is two forms,
	 * for singular and plural. In this case, the second form
	 * is technically dual, but in practice it's used as plural.
	 * In some cases, usually with expressions of time, three forms
	 * are needed - singular, dual and plural.
	 * CLDR also specifies a fourth form for multiples of 10,
	 * which is very rare. It also has a mistake, because
	 * the number 10 itself is supposed to be just plural,
	 * so currently it's overridden in MediaWiki.
	 */

	// @todo the below test*PluralForms test methods can be refactored
	//  to use a single test method and data provider..

	/**
	 * @dataProvider provideTwoPluralForms
	 * @covers Language::convertPlural
	 */
	public function testTwoPluralForms( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider provideThreePluralForms
	 * @covers Language::convertPlural
	 */
	public function testThreePluralForms( $result, $value ) {
		$forms = [ 'one', 'two', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider provideFourPluralForms
	 * @covers Language::convertPlural
	 */
	public function testFourPluralForms( $result, $value ) {
		$forms = [ 'one', 'two', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider provideFourPluralForms
	 * @covers Language::convertPlural
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function provideTwoPluralForms() {
		return [
			[ 'other', 0 ], // Zero - plural
			[ 'one', 1 ], // Singular
			[ 'other', 2 ], // No third form provided, use it as plural
			[ 'other', 3 ], // Plural - other
			[ 'other', 10 ], // No fourth form provided, use it as plural
			[ 'other', 20 ], // No fourth form provided, use it as plural
		];
	}

	public static function provideThreePluralForms() {
		return [
			[ 'other', 0 ], // Zero - plural
			[ 'one', 1 ], // Singular
			[ 'two', 2 ], // Dual
			[ 'other', 3 ], // Plural - other
			[ 'other', 10 ], // No fourth form provided, use it as plural
			[ 'other', 20 ], // No fourth form provided, use it as plural
		];
	}

	public static function provideFourPluralForms() {
		return [
			[ 'other', 0 ], // Zero - plural
			[ 'one', 1 ], // Singular
			[ 'two', 2 ], // Dual
			[ 'other', 3 ], // Plural - other
			[ 'other', 10 ], // 10 is supposed to be plural (other), not "many"
			[ 'many', 20 ], // Fourth form provided - rare, but supported by CLDR
		];
	}

	/**
	 * @dataProvider provideGrammar
	 * @covers Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	// The comments in the beginning of the line help avoid RTL problems
	// with text editors.
	public static function provideGrammar() {
		return [
			[
				/* result */'וויקיפדיה',
				/* word   */'ויקיפדיה',
				/* case   */'תחילית',
			],
			[
				/* result */'וולפגנג',
				/* word   */'וולפגנג',
				/* case   */'prefixed',
			],
			[
				/* result */'קובץ',
				/* word   */'הקובץ',
				/* case   */'תחילית',
			],
			[
				/* result */'־Wikipedia',
				/* word   */'Wikipedia',
				/* case   */'תחילית',
			],
			[
				/* result */'־1995',
				/* word   */'1995',
				/* case   */'תחילית',
			],
		];
	}
}
