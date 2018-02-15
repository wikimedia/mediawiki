<?php

/**
 * @covers LanguageShi
 * @covers ShiConverter
 */
class LanguageShiTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers Language::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
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
