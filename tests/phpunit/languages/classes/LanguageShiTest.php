<?php

class LanguageShiTest extends LanguageClassesTestCase {
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
					'shi'      => 'AƔ',
					'shi-tfng' => 'ⴰⵖ',
					'shi-latn' => 'AƔ',
				),
				'AƔ'
			),
			array(
				array(
					'shi'      => 'ⴰⵖ',
					'shi-tfng' => 'ⴰⵖ',
					'shi-latn' => 'aɣ',
				),
				'ⴰⵖ'
			),
		);
	}
}
