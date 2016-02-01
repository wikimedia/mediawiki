<?php

class LanguageIuTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return array(
			// ike-cans
			array(
				array(
					'ike-cans' => 'ᐴ',
					'ike-latn' => 'PUU',
					'iu' => 'PUU',
				),
				'PUU'
			),
			// ike-latn
			array(
				array(
					'ike-cans' => 'ᐴ',
					'ike-latn' => 'puu',
					'iu' => 'ᐴ',
				),
				'ᐴ'
			),
		);
	}
}
