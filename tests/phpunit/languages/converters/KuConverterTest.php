<?php

/**
 * @group Language
 * @covers KuConverter
 */
class KuConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers KuConverter::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[
				[
					'ku'      => '١',
					'ku-arab' => '١',
					'ku-latn' => '1',
				],
				'١'
			],
			[
				[
					'ku'      => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
					'ku-arab' => 'ویکیپەدیائە نسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
				],
				'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.'
			],
			[
				[
					'ku'      => 'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-arab' => 'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'wîkîpedîa ensîklopedîekea zad b rengê wîkî îe.',
				],
				'ویکیپەدیا ەنسیکلۆپەدیەکەئا زاد ب رەنگێ ویکی یە.'
			],
		];
	}
}
