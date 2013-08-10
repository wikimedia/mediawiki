<?php

/**
 * @group Language
 * @covers YueConverter
 */
class YueConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers YueConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			// yueHant2Hans
			[
				[
					'yue-hant' => '㑯',
					'yue-hans' => '㑔',
				],
				'㑯'
			],
			// yueHans2Hant: Disabled
			[
				[
					'yue-hant' => '㐷',
					'yue-hans' => '㐷',
				],
				'㐷'
			],
		];
	}
}
