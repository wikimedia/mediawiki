<?php

/**
 * @covers LanguageAr
 */
class LanguageArTest extends LanguageClassesTestCase {

	/**
	 * @covers Language::formatNum
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum( $num, $formatted ) {
		$this->assertEquals( $formatted, $this->getLang()->formatNum( $num ) );
	}

	public static function provideFormatNum() {
		return [
			[ '1234567', '١٬٢٣٤٬٥٦٧' ],
			[ -12.89, '-١٢٫٨٩' ],
		];
	}

	/**
	 * @covers LanguageAr::normalize
	 * @covers Language::normalize
	 * @dataProvider provideNormalize
	 */
	public function testNormalize( $input, $expected ) {
		if ( $input === $expected ) {
			throw new Exception( 'Expected output must differ.' );
		}

		$this->assertSame(
			$expected,
			$this->getLang()->normalize( $input ),
			'ar-normalised form'
		);
	}

	public static function provideNormalize() {
		return [
			[
				'ﷅ',
				'صمم',
			],
			[
				'ﻴ',
				'ي',
			],
			[
				'ﻬ',
				'ه',
			],
		];
	}

	/**
	 * Mostly to test the raw ascii feature.
	 * @dataProvider provideSprintfDate
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDate( $format, $date, $expected ) {
		$this->assertEquals( $expected, $this->getLang()->sprintfDate( $format, $date ) );
	}

	public static function provideSprintfDate() {
		return [
			[
				'xg "vs" g',
				'20120102030410',
				'يناير vs ٣'
			],
			[
				'xmY',
				'20120102030410',
				'١٤٣٣'
			],
			[
				'xnxmY',
				'20120102030410',
				'1433'
			],
			[
				'xN xmj xmn xN xmY',
				'20120102030410',
				' 7 2  ١٤٣٣'
			],
		];
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'zero', 'one', 'two', 'few', 'many', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
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
			[ 'zero', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'few', 9 ],
			[ 'few', 110 ],
			[ 'many', 11 ],
			[ 'many', 15 ],
			[ 'many', 99 ],
			[ 'many', 9999 ],
			[ 'other', 100 ],
			[ 'other', 102 ],
			[ 'other', 1000 ],
			[ 'other', 1.7 ],
		];
	}
}
