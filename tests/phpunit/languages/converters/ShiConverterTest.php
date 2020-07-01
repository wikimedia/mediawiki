<?php

/**
 * @group Language
 * @covers ShiConverter
 */
class ShiConverterTest extends MediaWikiIntegrationTestCase {

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
					'shi'      => 'AƔ',
					'shi-tfng' => 'ⴰⵖ',
					'shi-latn' => 'AƔ',
				],
				'AƔ'
			],
			[
				[
					'shi'      => 'ⴰⵖ',
					'shi-tfng' => 'ⴰⵖ',
					'shi-latn' => 'aɣ',
				],
				'ⴰⵖ'
			],
		];
	}
}
