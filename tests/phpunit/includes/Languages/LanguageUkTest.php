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
class LanguageUkTest extends LanguageClassesTestCase {
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
			'Вікіпедії',
			'Вікіпедія',
			'genitive',
		];
		yield 'Wikispecies genitive' => [
			'Віківидів',
			'Віківиди',
			'genitive',
		];
		yield 'Wikiquote genitive' => [
			'Вікіцитат',
			'Вікіцитати',
			'genitive',
		];
		yield 'Wikibooks genitive' => [
			'Вікіпідручника',
			'Вікіпідручник',
			'genitive',
		];
		yield 'Wikipedia accusative' => [
			'Вікіпедію',
			'Вікіпедія',
			'accusative',
		];
		yield 'MediaWiki locative' => [
			'у MediaWiki',
			'MediaWiki',
			'locative',
		];
	}
}
