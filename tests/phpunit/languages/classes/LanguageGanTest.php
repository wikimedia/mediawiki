<?php

class LanguageGanTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );	
	}

	public static function provideAutoConvertToAllVariants() {
		return array(
			// zh2Hans
			array(
				array(
					'gan' => '㑯',
					'gan-hans' => '㑔',
					'gan-hant' => '㑯',
				),
				'㑯'
			),
			// zh2Hant
			array(
				array(
					'gan' => '㐷',
					'gan-hans' => '㐷',
					'gan-hant' => '傌',
				),
				'㐷'
			),
		);
	}
}
