<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers \LanguageHu
 */
class LanguageHuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
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
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 200 ],
		];
	}

	/**
	 * @dataProvider provideConvertGrammar
	 */
	public function testConvertGrammar( string $word, string $case, string $expected ): void {
		$this->assertSame( $expected, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideConvertGrammar(): iterable {
		$wordCaseMappings = [
			'kocsmafal' => [
				'rol' => 'kocsmafalról',
				'ba' => 'kocsmafalba',
			],
			'Bevezető' => [
				'k' => 'Bevezetők',
			],
		];

		foreach ( $wordCaseMappings as $word => $caseMappings ) {
			foreach ( $caseMappings as $case => $expected ) {
				yield "$word $case" => [ (string)$word, $case, $expected ];
			}
		}
	}
}
