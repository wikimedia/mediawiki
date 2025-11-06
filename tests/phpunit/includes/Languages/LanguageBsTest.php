<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * Tests for Bosnian (bosanski)
 *
 * @group Language
 * @covers \LanguageBs
 */
class LanguageBsTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'other' ];
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
			[ 'few', 2 ],
			[ 'few', 4 ],
			[ 'other', 5 ],
			[ 'other', 11 ],
			[ 'other', 20 ],
			[ 'one', 21 ],
			[ 'few', 24 ],
			[ 'other', 25 ],
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
		yield [ 'word', 'instrumental', 's word' ];
		yield [ 'word', 'lokativ', 'o word' ];
		yield [ 'word', 'nominativ', 'word' ];
	}
}
