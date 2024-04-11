<?php

/**
 * @group Language
 * @covers ShiConverter
 */
class ZghConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers ShiConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result,
			$this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'zgh'      => 'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ',
					'zgh-latn' => 'abggʷdḍefkkʷ hḥɛxqijlmn urṛɣsṣctṭw yzẓ',
				],
				'ⴰⴱⴳⴳⵯⴷⴹⴻⴼⴽⴽⵯ ⵀⵃⵄⵅⵇⵉⵊⵍⵎⵏ ⵓⵔⵕⵖⵙⵚⵛⵜⵟⵡ ⵢⵣⵥ'
			],
		];
	}
}
