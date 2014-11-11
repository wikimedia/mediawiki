<?php
/**
 * @author Amir E. Aharoni
 * based on LanguageBe_tarask.php
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageRu.php */
class LanguageRuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * Test explicit plural forms - n=FormN forms
	 * @covers Language::convertPlural
	 */
	public function testExplicitPlural() {
		$forms = array( 'one', 'few', 'many', 'other', '12=dozen' );
		$this->assertEquals( 'dozen', $this->getLang()->convertPlural( 12, $forms ) );
		$forms = array( 'one', 'few', 'many', '100=hundred', 'other', '12=dozen' );
		$this->assertEquals( 'hundred', $this->getLang()->convertPlural( 100, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return array(
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

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = array( '1=one', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return array(
			array( 'one', 1 ),
			array( 'other', 11 ),
			array( 'other', 91 ),
			array( 'other', 121 ),
		);
	}

	/**
	 * @dataProvider providerGrammar
	 * @covers Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		return array(
			array(
				'Википедии',
				'Википедия',
				'genitive',
			),
			array(
				'Викитеки',
				'Викитека',
				'genitive',
			),
			array(
				'Викитеке',
				'Викитека',
				'prepositional',
			),
			array(
				'Викисклада',
				'Викисклад',
				'genitive',
			),
			array(
				'Викискладе',
				'Викисклад',
				'prepositional',
			),
			array(
				'Викиданных',
				'Викиданные',
				'prepositional',
			),
			array(
				'русского',
				'русский',
				'languagegen',
			),
			array(
				'немецкого',
				'немецкий',
				'languagegen',
			),
			array(
				'иврита',
				'иврит',
				'languagegen',
			),
			array(
				'эсперанто',
				'эсперанто',
				'languagegen',
			),
			array(
				'русском',
				'русский',
				'languageprep',
			),
			array(
				'немецком',
				'немецкий',
				'languageprep',
			),
			array(
				'идише',
				'идиш',
				'languageprep',
			),
			array(
				'эсперанто',
				'эсперанто',
				'languageprep',
			),
			array(
				'по-русски',
				'русский',
				'languageadverb',
			),
			array(
				'по-немецки',
				'немецкий',
				'languageadverb',
			),
			array(
				'на иврите',
				'иврит',
				'languageadverb',
			),
			array(
				'на эсперанто',
				'эсперанто',
				'languageadverb',
			),
			array(
				'на языке гуарани',
				'гуарани',
				'languageadverb',
			),
		);
	}
}
