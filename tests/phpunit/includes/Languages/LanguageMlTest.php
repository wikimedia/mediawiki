<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers \LanguageMl
 */
class LanguageMlTest extends LanguageClassesTestCase {

	/**
	 * @dataProvider provideFormatNum
	 * @covers \MediaWiki\Language\Language::formatNum
	 */
	public function testFormatNum( $value, $result ) {
		// For T31495
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}

	public static function provideFormatNum() {
		return [
			[ '1234567', '12,34,567' ],
			[ '12345', '12,345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1234', '1,234' ],
			[ '12345.56', '12,345.56' ],
			[ '12345679812345678', '12,34,56,79,81,23,45,678' ],
			[ '.12345', '.12345' ],
			[ '-1200000', '−12,00,000' ],
			[ '-98', '−98' ],
			[ -98, '−98' ],
			[ -12345678, '−1,23,45,678' ],
			[ '', '' ],
			[ null, '' ],
		];
	}

	/**
	 * @covers \LanguageMl::normalize
	 * @covers \MediaWiki\Language\Language::normalize
	 * @dataProvider provideNormalize
	 */
	public function testNormalize( $input, $expected ) {
		if ( $input === $expected ) {
			$this->fail( 'Expected output must differ.' );
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
