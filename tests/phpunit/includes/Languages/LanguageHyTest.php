<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Armenian (Հայերեն)
 *
 * @group Language
 * @covers \LanguageHy
 */
class LanguageHyTest extends LanguageClassesTestCase {
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
			[ 'one', 0 ],
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
		yield [ 'Մաունա', 'genitive', 'Մաունայի' ];
		yield [ 'հետո', 'genitive', 'հետոյի' ];
		yield [ 'գիրք', 'genitive', 'գրքի' ];
		yield [ 'ժամանակի', 'genitive', 'ժամանակիի' ];

		yield [ 'Մաունա', 'dative', 'Մաունա' ];
	}
}
