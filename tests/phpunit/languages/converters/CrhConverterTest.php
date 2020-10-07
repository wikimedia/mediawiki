<?php

/**
 * @group Language
 * @covers CrhConverter
 */
class CrhConverterTest extends MediaWikiIntegrationTestCase {

	use LanguageConverterTestTrait;

	/**
	 * @dataProvider provideAutoConvertToAllVariantsByWord
	 * @covers CrhConverter::autoConvertToAllVariants
	 *
	 * Test individual words and test minimal contextual transforms
	 * by creating test strings "<cyrillic> <latin>" and
	 * "<latin> <cyrillic>" and then converting to all variants.
	 */
	public function testAutoConvertToAllVariantsByWord( $cyrl, $lat ) {
		$value = $lat;
		$result = [
			'crh'      => $value,
			'crh-cyrl' => $cyrl,
			'crh-latn' => $lat,
			];
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );

		$value = $cyrl;
		$result = [
			'crh'      => $value,
			'crh-cyrl' => $cyrl,
			'crh-latn' => $lat,
			];
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );

		$value = $cyrl . ' ' . $lat;
		$result = [
			'crh'      => $value,
			'crh-cyrl' => $cyrl . ' ' . $cyrl,
			'crh-latn' => $lat . ' ' . $lat,
			];
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );

		$value = $lat . ' ' . $cyrl;
		$result = [
			'crh'      => $value,
			'crh-cyrl' => $cyrl . ' ' . $cyrl,
			'crh-latn' => $lat . ' ' . $lat,
			];
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariantsByWord() {
		return [
			// general words, covering more of the alphabet
			[ 'рузгярнынъ', 'ruzgârnıñ' ], [ 'Париж', 'Parij' ], [ 'чёкюч', 'çöküç' ],
			[ 'элифбени', 'elifbeni' ], [ 'полициясы', 'politsiyası' ], [ 'хусусында', 'hususında' ],
			[ 'акъшамларны', 'aqşamlarnı' ], [ 'опькеленюв', 'öpkelenüv' ],
			[ 'кулюмсиреди', 'külümsiredi' ], [ 'айтмайджагъым', 'aytmaycağım' ],
			[ 'козьяшсыз', 'közyaşsız' ],

			// exception words
			[ 'инструменталь', 'instrumental' ], [ 'гургуль', 'gürgül' ], [ 'тюшюнмемек', 'tüşünmemek' ],

			// specific problem words
			[ 'куню', 'künü' ], [ 'сюргюнлиги', 'sürgünligi' ], [ 'озю', 'özü' ], [ 'этти', 'etti' ],
			[ 'эсас', 'esas' ], [ 'дёрт', 'dört' ], [ 'кельди', 'keldi' ], [ 'км²', 'km²' ],
			[ 'юзь', 'yüz' ], [ 'АКъШ', 'AQŞ' ], [ 'ШСДжБнен', 'ŞSCBnen' ], [ 'июль', 'iyül' ],
			[ 'ишгъаль', 'işğal' ], [ 'ишгъальджилерине', 'işğalcilerine' ], [ 'район', 'rayon' ],
			[ 'районынынъ', 'rayonınıñ' ], [ 'Ногъай', 'Noğay' ], [ 'Юрьтю', 'Yürtü' ],
			[ 'ватандан', 'vatandan' ], [ 'ком-кок', 'köm-kök' ], [ 'АКЪКЪЫ', 'AQQI' ],
			[ 'ДАГЪГЪА', 'DAĞĞA' ], [ '13-юнджи', '13-ünci' ], [ 'ДЖУРЬМЕК', 'CÜRMEK' ],
			[ 'джумлеси', 'cümlesi' ], [ 'ильи', 'ilyi' ], [ 'Ильи', 'İlyi' ], [ 'бруцел', 'brutsel' ],
			[ 'коцюб', 'kotsüb' ], [ 'плацен', 'platsen' ], [ 'эпицентр', 'epitsentr' ],

			// -tsin- words
			[ 'кетсин', 'ketsin' ], [ 'кирлетсин', 'kirletsin' ], [ 'этсин', 'etsin' ],
			[ 'етсин', 'yetsin' ], [ 'этсинлерми', 'etsinlermi' ], [ 'принцини', 'printsini' ],
			[ 'медицина', 'meditsina' ], [ 'Щетсин', 'Şçetsin' ], [ 'Щекоцины', 'Şçekotsinı' ],

			// regex pattern words
			[ 'коюнден', 'köyünden' ], [ 'аньге', 'ange' ],

			// multi part words
			[ 'эки юз', 'eki yüz' ],

			// affix patterns
			[ 'койнинъ', 'köyniñ' ], [ 'Авджыкойде', 'Avcıköyde' ], [ 'экваториаль', 'ekvatorial' ],
			[ 'Джанкой', 'Canköy' ], [ 'усть', 'üst' ], [ 'роль', 'rol' ], [ 'буюк', 'büyük' ],
			[ 'джонк', 'cönk' ],

			// Roman numerals vs Initials, part 1 - Roman numeral initials without spaces
			[ 'А.Б.Дж.Д.М. Къадырова XII', 'A.B.C.D.M. Qadırova XII' ],
			// Roman numerals vs Initials, part 2 - Roman numeral initials with spaces
			[ 'Г. Х. Ы. В. X. Л. Меметов III',  'G. H. I. V. X. L. Memetov III' ],

			// ALL CAPS, made up acronyms
			[ 'НЪАБ', 'ÑAB' ], [ 'КЪЫДЖ', 'QIC' ], [ 'ГЪУК', 'ĞUK' ], [ 'ДЖОТ', 'COT' ], [ 'ДЖА', 'CA' ],
		];
	}

	/**
	 * @dataProvider provideAutoConvertToAllVariantsByString
	 * @covers CrhConverter::autoConvertToAllVariants
	 *
	 * Run tests that require some context (like Roman numerals) or with
	 * many-to-one mappings, or other asymmetric results (like smart quotes)
	 */
	public function testAutoConvertToAllVariantsByString( $result, $value ) {
		$this->assertEquals( $result, $this->getLanguageConverter()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariantsByString() {
		return [
			[ // Roman numerals and quotes, esp. single-letter Roman numerals at the end of a string
				[
					'crh'      => 'VI,VII IX “dört” «дёрт» XI XII I V X L C D M',
					'crh-cyrl' => 'VI,VII IX «дёрт» «дёрт» XI XII I V X L C D M',
					'crh-latn' => 'VI,VII IX “dört” "dört" XI XII I V X L C D M',
				],
				'VI,VII IX “dört” «дёрт» XI XII I V X L C D M'
			],
			[ // Many-to-one mappings: many Cyrillic to one Latin
				[
					'crh'      => 'шофер шофёр şoför корбекул корьбекул корьбекуль körbekül',
					'crh-cyrl' => 'шофер шофёр шофёр корбекул корьбекул корьбекуль корьбекуль',
					'crh-latn' => 'şoför şoför şoför körbekül körbekül körbekül körbekül',
				],
				'шофер шофёр şoför корбекул корьбекул корьбекуль körbekül'
			],
			[ // Many-to-one mappings: many Latin to one Cyrillic
				[
					'crh'      => 'fevqülade fevqulade февкъульаде beyude beyüde бейуде',
					'crh-cyrl' => 'февкъульаде февкъульаде февкъульаде бейуде бейуде бейуде',
					'crh-latn' => 'fevqülade fevqulade fevqulade beyude beyüde beyüde',
				],
				'fevqülade fevqulade февкъульаде beyude beyüde бейуде'
			],
		];
	}
}
