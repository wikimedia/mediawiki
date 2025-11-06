<?php
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageApcTest extends LanguageClassesTestCase {

	/**
	 * @covers \MediaWiki\Language\Language::formatNum
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum( $num, $formatted ) {
		$this->assertEquals( $formatted, $this->getLang()->formatNum( $num ) );
	}

	public static function provideFormatNum() {
		// Check that the Western numbers don't change in transformation
		// https://phabricator.wikimedia.org/T382781
		return [
			[ '1234567890', '1,234,567,890' ],
			[ -12.89, '−12.89' ],
			[ '1289.456', '1,289.456' ]
		];
	}

	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other' ];
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
			[ 'few', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'few', 9 ],
			[ 'few', 10 ],
			[ 'other', 11 ],
			[ 'other', 12 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'other', 101 ],
			[ 'other', 102 ],
			[ 'few', 103 ],
			[ 'few', 104 ],
			[ 'few', 109 ],
			[ 'few', 110 ],
			[ 'other', 111 ],
			[ 'other', 112 ],
			[ 'other', 9999 ],
			[ 'other', 1000 ],
			[ 'few', 1003 ],
			[ 'other', 1.7 ],
		];
	}
}
