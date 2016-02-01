<?php

class LanguageTgTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return array(
			array(
				array(
					'tg'      => 'г',
					'tg-latn' => 'g',
				),
				'г'
			),
			array(
				array(
					'tg'      => 'g',
					'tg-latn' => 'g',
				),
				'g'
			),
		);
	}
}
