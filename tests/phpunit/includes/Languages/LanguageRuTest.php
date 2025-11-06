<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageRuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * Test explicit plural forms - n=FormN forms
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testExplicitPlural() {
		$forms = [ 'one', 'few', 'many', 'other', '12=dozen' ];
		$this->assertEquals( 'dozen', $this->getLang()->convertPlural( 12, $forms ) );
		$forms = [ 'one', 'few', 'many', '100=hundred', 'other', '12=dozen' ];
		$this->assertEquals( 'hundred', $this->getLang()->convertPlural( 100, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
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
	 * @covers \MediaWiki\Language\Language::convertPlural
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
	 * @covers \MediaWiki\Language\Language::convertGrammar
	 */
	public function testGrammar( $result, $word, $case ) {
		$this->assertEquals( $result, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function providerGrammar() {
		yield 'Wikipedia genitive' => [
			'Википедии',
			'Википедия',
			'genitive',
		];
		yield 'Wikisource genitive' => [
			'Викитеки',
			'Викитека',
			'genitive',
		];
		yield 'Wikipedia accusative' => [
			'Википедию',
			'Википедия',
			'accusative',
		];
		yield 'Wiktionary accusative' => [
			'Викисловарь',
			'Викисловарь',
			'accusative',
		];
		yield 'Wikiquote accusative' => [
			'Викицитатник',
			'Викицитатник',
			'accusative',
		];
		yield 'Wikibooks accusative' => [
			'Викиучебник',
			'Викиучебник',
			'accusative',
		];
		yield 'Wikisource accusative' => [
			'Викитеку',
			'Викитека',
			'accusative',
		];
		yield 'Wikinews accusative' => [
			'Викиновости',
			'Викиновости',
			'accusative',
		];
		yield 'Wikiversity accusative' => [
			'Викиверситет',
			'Викиверситет',
			'accusative',
		];
		yield 'Wikispecies accusative' => [
			'Викивиды',
			'Викивиды',
			'accusative',
		];
		yield 'Wikidata accusative' => [
			'Викиданные',
			'Викиданные',
			'accusative',
		];
		yield 'Commons accusative' => [
			'Викисклад',
			'Викисклад',
			'accusative',
		];
		yield 'Wikivoyage accusative' => [
			'Викигид',
			'Викигид',
			'accusative',
		];
		yield 'Meta accusative' => [
			'Мету',
			'Мета',
			'accusative',
		];
		yield 'Incubator accusative' => [
			'Инкубатор',
			'Инкубатор',
			'accusative',
		];
		yield 'Wikisource prepositional' => [
			'Викитеке',
			'Викитека',
			'prepositional',
		];
		yield 'Commons genitive' => [
			'Викисклада',
			'Викисклад',
			'genitive',
		];
		yield 'Wikiversity genitive' => [
			'Викиверситета',
			'Викиверситет',
			'genitive',
		];
		yield 'Commons prepositional' => [
			'Викискладе',
			'Викисклад',
			'prepositional',
		];
		yield 'Wikidata prepositional' => [
			'Викиданных',
			'Викиданные',
			'prepositional',
		];
		yield 'Wikiversity prepositional' => [
			'Викиверситете',
			'Викиверситет',
			'prepositional',
		];
		yield 'Russian languagegen' => [
			'русского',
			'русский',
			'languagegen',
		];
		yield 'German languagegen' => [
			'немецкого',
			'немецкий',
			'languagegen',
		];
		yield 'Hebrew languagegen' => [
			'иврита',
			'иврит',
			'languagegen',
		];
		yield 'Esperanto languagegen' => [
			'эсперанто',
			'эсперанто',
			'languagegen',
		];
		yield 'Russian languageprep' => [
			'русском',
			'русский',
			'languageprep',
		];
		yield 'German languageprep' => [
			'немецком',
			'немецкий',
			'languageprep',
		];
		yield 'Yiddish languageprep' => [
			'идише',
			'идиш',
			'languageprep',
		];
		yield 'Esperanto languageprep' => [
			'эсперанто',
			'эсперанто',
			'languageprep',
		];
		yield 'Russian languageadverb' => [
			'по-русски',
			'русский',
			'languageadverb',
		];
		yield 'German languageadverb' => [
			'по-немецки',
			'немецкий',
			'languageadverb',
		];
		yield 'Hebrew languageadverb' => [
			'на иврите',
			'иврит',
			'languageadverb',
		];
		yield 'Esperanto languageadverb' => [
			'на эсперанто',
			'эсперанто',
			'languageadverb',
		];
		yield 'Guarani languageadverb' => [
			'на языке гуарани',
			'гуарани',
			'languageadverb',
		];
	}
}
