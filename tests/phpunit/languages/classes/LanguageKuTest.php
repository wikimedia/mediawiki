<?php

class LanguageKuTest extends LanguageClassesTestCase {
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
					'ku'      => '١',
					'ku-arab' => '١',
					'ku-latn' => '1',
				),
				'١'
			),
			array(
				array(
					'ku'      => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
					'ku-arab' => 'ویکیپەدیائە نسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
				),
				'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.'
			),
			array(
				array(
					'ku'      => 'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-arab' => 'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'wîkîpedîa ensîklopedîekea zad b rengê wîkî îe.',
				),
				'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.'
			),
		);
	}
}
