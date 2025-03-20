<?php

/**
 * @group Language
 * @covers \KuConverter
 */
class KuConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariants
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
					'ku'      => 'Bi alfabeyên din',
					'ku-arab' => 'ب ئالفابەیێن دن',
					'ku-latn' => 'Bi alfabeyên din',
				],
				'Bi alfabeyên din'
			],
			[
				[
					'ku'      => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
					'ku-arab' => 'ویکیپەدیا ئەنسیکلۆپەدیەکە ئازاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.',
				],
				'Wîkîpediya ensîklopediyeke azad bi rengê wîkî ye.'
			],
			[
				[
					'ku'      => 'ویکیپەدیا ئەنسیکلۆپەدیەکە ئازاد ب رەنگێ ویکی یە.',
					'ku-arab' => 'ویکیپەدیا ئەنسیکلۆپەدیەکە ئازاد ب رەنگێ ویکی یە.',
					'ku-latn' => 'wîkîpedîa ensîklopedîeke azad b rengê wîkî îe.',
				],
				'ویکیپەدیا ئەنسیکلۆپەدیەکە ئازاد ب رەنگێ ویکی یە.'
			],
		];
	}
}
