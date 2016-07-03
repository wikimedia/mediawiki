<?php

class LanguageTgTest extends LanguageClassesTestCase {
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
					'tg'      => 'г',
					'tg-latn' => 'g',
				],
				'г'
			],
			[
				[
					'tg'      => 'g',
					'tg-latn' => 'g',
				],
				'g'
			],
		];
	}
}
