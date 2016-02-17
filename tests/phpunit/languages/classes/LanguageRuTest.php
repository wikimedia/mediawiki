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
		$forms = [ 'one', 'few', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * Test explicit plural forms - n=FormN forms
	 * @covers Language::convertPlural
	 */
	public function testExplicitPlural() {
		$forms = [ 'one', 'few', 'many', 'other', '12=dozen' ];
		$this->assertEquals( 'dozen', $this->getLang()->convertPlural( 12, $forms ) );
		$forms = [ 'one', 'few', 'many', '100=hundred', 'other', '12=dozen' ];
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
		return [
			[ 'one', 1 ],
			[ 'many', 11 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'few', 334 ],
			[ 'many', 5 ],
			[ 'many', 15 ],
			[ 'many', 120 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = [ '1=one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'other', 91 ],
			[ 'other', 121 ],
		];
	}

	/**
	 * @dataProvider providerGrammar
	 * @covers Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		return [
			[
				'Википедии',
				'Википедия',
				'genitive',
			],
			[
				'Викитеки',
				'Викитека',
				'genitive',
			],
			[
				'Викитеке',
				'Викитека',
				'prepositional',
			],
			[
				'Викисклада',
				'Викисклад',
				'genitive',
			],
			[
				'Викискладе',
				'Викисклад',
				'prepositional',
			],
			[
				'Викиданных',
				'Викиданные',
				'prepositional',
			],
			[
				'русского',
				'русский',
				'languagegen',
			],
			[
				'немецкого',
				'немецкий',
				'languagegen',
			],
			[
				'иврита',
				'иврит',
				'languagegen',
			],
			[
				'эсперанто',
				'эсперанто',
				'languagegen',
			],
			[
				'русском',
				'русский',
				'languageprep',
			],
			[
				'немецком',
				'немецкий',
				'languageprep',
			],
			[
				'идише',
				'идиш',
				'languageprep',
			],
			[
				'эсперанто',
				'эсперанто',
				'languageprep',
			],
			[
				'по-русски',
				'русский',
				'languageadverb',
			],
			[
				'по-немецки',
				'немецкий',
				'languageadverb',
			],
			[
				'на иврите',
				'иврит',
				'languageadverb',
			],
			[
				'на эсперанто',
				'эсперанто',
				'languageadverb',
			],
			[
				'на языке гуарани',
				'гуарани',
				'languageadverb',
			],
		];
	}
}
