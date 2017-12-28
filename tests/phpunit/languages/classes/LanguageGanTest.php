<?php

/**
 * @covers LanguageGan
 * @covers GanConverter
 */
class LanguageGanTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers Language::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			// zh2Hans
			[
				[
					'gan' => '㑯',
					'gan-hans' => '㑔',
					'gan-hant' => '㑯',
				],
				'㑯'
			],
			// zh2Hant
			[
				[
					'gan' => '㐷',
					'gan-hans' => '㐷',
					'gan-hant' => '傌',
				],
				'㐷'
			],
		];
	}
}
