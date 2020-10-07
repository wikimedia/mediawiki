<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/**
 * @group Language
 * @covers LanguageMl
 */
class LanguageMlTest extends LanguageClassesTestCase {

	/**
	 * @dataProvider provideFormatNum
	 * @covers Language::formatNum
	 */
	public function testFormatNum( $result, $value ) {
		// For T31495
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}

	public static function provideFormatNum() {
		return [
			[ '12,34,567', '1234567' ],
			[ '12,345', '12345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1,234', '1234' ],
			[ '12,345.56', '12345.56' ],
			[ '12,34,56,79,81,23,45,678', '12345679812345678' ],
			[ '.12345', '.12345' ],
			[ '-12,00,000', '-1200000' ],
			[ '-98', '-98' ],
			[ '-98', -98 ],
			[ '-1,23,45,678', -12345678 ],
			[ '', '' ],
			[ '', null ],
		];
	}

	/**
	 * @covers LanguageMl::normalize
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
			'ml-normalised form'
		);
	}

	public static function provideNormalize() {
		return [
			[
				'ല്‍',
				'ൽ',
			],
			[
				'ര്‍',
				'ർ',
			],
			[
				'ള്‍',
				'ൾ',
			],
		];
	}
}
