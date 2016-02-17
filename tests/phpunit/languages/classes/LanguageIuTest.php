<?php

class LanguageIuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers Language::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
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
