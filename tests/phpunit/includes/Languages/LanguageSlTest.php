<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers \LanguageSl
 */
class LanguageSlTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerPlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providerPlural
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providerPlural() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'other', 5 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'one', 101 ],
			[ 'two', 102 ],
			[ 'few', 103 ],
			[ 'one', 201 ],
		];
	}

	/**
	 * @dataProvider provideConvertGrammar
	 */
	public function testConvertGrammar( string $word, string $case, string $expected ): void {
		$this->assertSame( $expected, $this->getLang()->convertGrammar( $word, $case ) );
	}

	public static function provideConvertGrammar(): iterable {
		yield [ 'word', 'mestnik', 'o word' ];
		yield [ 'word', 'orodnik', 'z word' ];
		yield [ 'word', 'imenovalnik', 'word' ];
	}
}
