<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Irish (Gaeilge)
 *
 * @group Language
 * @covers \LanguageGa
 */
class LanguageGaTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'other' ];
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
			[ 'two', 2 ],
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
		yield [ 'an Domhnach', 'ainmlae', 'Dé Domhnaigh' ];
		yield [ 'an Luan', 'ainmlae', 'Dé Luain' ];
		yield [ 'an Mháirt', 'ainmlae', 'Dé Mháirt' ];
		yield [ 'an Chéadaoin', 'ainmlae', 'Dé Chéadaoin' ];
		yield [ 'an Déardaoin', 'ainmlae', 'Déardaoin' ];
		yield [ 'an Aoine', 'ainmlae', 'Dé hAoine' ];
		yield [ 'an Satharn', 'ainmlae', 'Dé Sathairn' ];

		yield [ 'an Domhnach', 'other', 'an Domhnach' ];
	}
}
