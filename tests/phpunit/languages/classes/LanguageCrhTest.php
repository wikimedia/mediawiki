<?php

/**
 * @covers LanguageCrh
 * @covers CrhConverter
 */
class LanguageCrhTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideAutoConvertToAllVariants
	 * @covers Language::autoConvertToAllVariants
	 */
	public function testAutoConvertToAllVariants( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->autoConvertToAllVariants( $value ) );
	}

	public static function provideAutoConvertToAllVariants() {
		return [
			[ // general words, covering more of the alphabet
				[
					'crh'      => 'рузгярнынъ ruzgârnıñ Париж Parij',
					'crh-cyrl' => 'рузгярнынъ рузгярнынъ Париж Париж',
					'crh-latn' => 'ruzgârnıñ ruzgârnıñ Parij Parij',
				],
				'рузгярнынъ ruzgârnıñ Париж Parij'
			],
			[ // general words, covering more of the alphabet
				[
					'crh'      => 'чёкюч çöküç элифбени elifbeni полициясы politsiyası',
					'crh-cyrl' => 'чёкюч чёкюч элифбени элифбени полициясы полициясы',
					'crh-latn' => 'çöküç çöküç elifbeni elifbeni politsiyası politsiyası',
				],
				'чёкюч çöküç элифбени elifbeni полициясы politsiyası'
			],
			[ // general words, covering more of the alphabet
				[
					'crh'      => 'хусусында hususında акъшамларны aqşamlarnı опькеленюв öpkelenüv',
					'crh-cyrl' => 'хусусында хусусында акъшамларны акъшамларны опькеленюв опькеленюв',
					'crh-latn' => 'hususında hususında aqşamlarnı aqşamlarnı öpkelenüv öpkelenüv',
				],
				'хусусында hususında акъшамларны aqşamlarnı опькеленюв öpkelenüv'
			],
			[ // general words, covering more of the alphabet
				[
					'crh'      => 'кулюмсиреди külümsiredi айтмайджагъым aytmaycağım козьяшсыз közyaşsız',
					'crh-cyrl' => 'кулюмсиреди кулюмсиреди айтмайджагъым айтмайджагъым козьяшсыз козьяшсыз',
					'crh-latn' => 'külümsiredi külümsiredi aytmaycağım aytmaycağım közyaşsız közyaşsız',
				],
				'кулюмсиреди külümsiredi айтмайджагъым aytmaycağım козьяшсыз közyaşsız'
			],
			[ // exception words
				[
					'crh'      => 'инструменталь instrumental гургуль gürgül тюшюнмемек tüşünmemek',
					'crh-cyrl' => 'инструменталь инструменталь гургуль гургуль тюшюнмемек тюшюнмемек',
					'crh-latn' => 'instrumental instrumental gürgül gürgül tüşünmemek tüşünmemek',
				],
				'инструменталь instrumental гургуль gürgül тюшюнмемек tüşünmemek'
			],
			[ // recent problem words, part 1
				[
					'crh'      => 'künü куню sürgünligi сюргюнлиги özü озю etti этти esas эсас dört дёрт',
					'crh-cyrl' => 'куню куню сюргюнлиги сюргюнлиги озю озю этти этти эсас эсас дёрт дёрт',
					'crh-latn' => 'künü künü sürgünligi sürgünligi özü özü etti etti esas esas dört dört',
				],
				'künü куню sürgünligi сюргюнлиги özü озю etti этти esas эсас dört дёрт'
			],
			[ // recent problem words, part 2
				[
					'crh'      => 'keldi кельди km² км² yüz юзь AQŞ АКъШ ŞSCBnen ШСДжБнен iyül июль',
					'crh-cyrl' => 'кельди кельди км² км² юзь юзь АКъШ АКъШ ШСДжБнен ШСДжБнен июль июль',
					'crh-latn' => 'keldi keldi km² km² yüz yüz AQŞ AQŞ ŞSCBnen ŞSCBnen iyül iyül',
				],
				'keldi кельди km² км² yüz юзь AQŞ АКъШ ŞSCBnen ШСДжБнен iyül июль'
			],
			[ // recent problem words, part 3
				[
					'crh'      => 'işğal ишгъаль işğalcilerine ишгъальджилерине rayon район üst усть',
					'crh-cyrl' => 'ишгъаль ишгъаль ишгъальджилерине ишгъальджилерине район район усть усть',
					'crh-latn' => 'işğal işğal işğalcilerine işğalcilerine rayon rayon üst üst',
				],
				'işğal ишгъаль işğalcilerine ишгъальджилерине rayon район üst усть'
			],
			[ // recent problem words, part 4
				[
					'crh'      => 'rayonınıñ районынынъ Noğay Ногъай Yürtü Юрьтю vatandan ватандан',
					'crh-cyrl' => 'районынынъ районынынъ Ногъай Ногъай Юрьтю Юрьтю ватандан ватандан',
					'crh-latn' => 'rayonınıñ rayonınıñ Noğay Noğay Yürtü Yürtü vatandan vatandan',
				],
				'rayonınıñ районынынъ Noğay Ногъай Yürtü Юрьтю vatandan ватандан'
			],
			[ // recent problem words, part 5
				[
					'crh'      => 'ком-кок köm-kök rol роль AQQI АКЪКЪЫ DAĞĞA ДАГЪГЪА 13-ünci 13-юнджи',
					'crh-cyrl' => 'ком-кок ком-кок роль роль АКЪКЪЫ АКЪКЪЫ ДАГЪГЪА ДАГЪГЪА 13-юнджи 13-юнджи',
					'crh-latn' => 'köm-kök köm-kök rol rol AQQI AQQI DAĞĞA DAĞĞA 13-ünci 13-ünci',
				],
				'ком-кок köm-kök rol роль AQQI АКЪКЪЫ DAĞĞA ДАГЪГЪА 13-ünci 13-юнджи'
			],
			[ // recent problem words, part 6
				[
					'crh'      => 'ДЖУРЬМЕК CÜRMEK кетсин ketsin джумлеси cümlesi ильи ilyi Ильи İlyi',
					'crh-cyrl' => 'ДЖУРЬМЕК ДЖУРЬМЕК кетсин кетсин джумлеси джумлеси ильи ильи Ильи Ильи',
					'crh-latn' => 'CÜRMEK CÜRMEK ketsin ketsin cümlesi cümlesi ilyi ilyi İlyi İlyi',
				],
				'ДЖУРЬМЕК CÜRMEK кетсин ketsin джумлеси cümlesi ильи ilyi Ильи İlyi'
			],
			[ // regex pattern words
				[
					'crh'      => 'köyünden коюнден ange аньге',
					'crh-cyrl' => 'коюнден коюнден аньге аньге',
					'crh-latn' => 'köyünden köyünden ange ange',
				],
				'köyünden коюнден ange аньге'
			],
			[ // multi part words
				[
					'crh'      => 'эки юз eki yüz',
					'crh-cyrl' => 'эки юз эки юз',
					'crh-latn' => 'eki yüz eki yüz',
				],
				'эки юз eki yüz'
			],
			[ // affix patterns
				[
					'crh'      => 'köyniñ койнинъ Avcıköyde Авджыкойде ekvatorial экваториаль Canköy Джанкой',
					'crh-cyrl' => 'койнинъ койнинъ Авджыкойде Авджыкойде экваториаль экваториаль Джанкой Джанкой',
					'crh-latn' => 'köyniñ köyniñ Avcıköyde Avcıköyde ekvatorial ekvatorial Canköy Canköy',
				],
				'köyniñ койнинъ Avcıköyde Авджыкойде ekvatorial экваториаль Canköy Джанкой'
			],
			[ // Roman numerals and quotes, esp. single-letter Roman numerals at the end of a string
				[
					'crh'      => 'VI,VII IX “dört” «дёрт» XI XII I V X L C D M',
					'crh-cyrl' => 'VI,VII IX «дёрт» «дёрт» XI XII I V X L C D M',
					'crh-latn' => 'VI,VII IX “dört” "dört" XI XII I V X L C D M',
				],
				'VI,VII IX “dört” «дёрт» XI XII I V X L C D M'
			],
			[ // Roman numerals vs Initials, part 1 - Roman numeral initials without spaces
				[
					'crh'      => 'A.B.C.D.M. Qadırova XII, А.Б.Дж.Д.М. Къадырова XII',
					'crh-cyrl' => 'А.Б.Дж.Д.М. Къадырова XII, А.Б.Дж.Д.М. Къадырова XII',
					'crh-latn' => 'A.B.C.D.M. Qadırova XII, A.B.C.D.M. Qadırova XII',
				],
				'A.B.C.D.M. Qadırova XII, А.Б.Дж.Д.М. Къадырова XII'
			],
			[ // Roman numerals vs Initials, part 2 - Roman numeral initials with spaces
				[
					'crh'      => 'G. H. I. V. X. L. Memetov III, Г. Х. Ы. В. X. Л. Меметов III',
					'crh-cyrl' => 'Г. Х. Ы. В. X. Л. Меметов III, Г. Х. Ы. В. X. Л. Меметов III',
					'crh-latn' => 'G. H. I. V. X. L. Memetov III, G. H. I. V. X. L. Memetov III',
				],
				'G. H. I. V. X. L. Memetov III, Г. Х. Ы. В. X. Л. Меметов III'
			],
			[ // ALL CAPS, made up acronyms
				[
					'crh'      => 'ÑAB QIC ĞUK COT НЪАБ КЪЫДЖ ГЪУК ДЖОТ CA ДЖА',
					'crh-cyrl' => 'НЪАБ КЪЫДЖ ГЪУК ДЖОТ НЪАБ КЪЫДЖ ГЪУК ДЖОТ ДЖА ДЖА',
					'crh-latn' => 'ÑAB QIC ĞUK COT ÑAB QIC ĞUK COT CA CA',
				],
				'ÑAB QIC ĞUK COT НЪАБ КЪЫДЖ ГЪУК ДЖОТ CA ДЖА'
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
