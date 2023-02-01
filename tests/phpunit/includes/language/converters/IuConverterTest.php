<?php

/**
 * @group Language
 * @covers IuConverter
 */
class IuConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers IuConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			// ike-cans
			[
				[
					'ike-cans' => 'ᐴ',
					'ike-latn' => 'PUU',
					'iu' => 'PUU',
				],
				'PUU'
			],
			// ike-latn
			[
				[
					'ike-cans' => 'ᐴ',
					'ike-latn' => 'puu',
					'iu' => 'ᐴ',
				],
				'ᐴ'
			],
		];
	}
}
