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
					'crh'      => 'künü куню sürgünligi сюргюнлиги özü озю etti этти',
					'crh-cyrl' => 'куню куню сюргюнлиги сюргюнлиги озю озю этти этти',
					'crh-latn' => 'künü künü sürgünligi sürgünligi özü özü etti etti',
				],
				'künü куню sürgünligi сюргюнлиги özü озю etti этти'
			],
			[ // recent problem words, part 2
				[
					'crh'      => 'esas эсас dört дёрт keldi кельди',
					'crh-cyrl' => 'эсас эсас дёрт дёрт кельди кельди',
					'crh-latn' => 'esas esas dört dört keldi keldi',
				],
				'esas эсас dört дёрт keldi кельди'
			],
			[ // multi part words
				[
					'crh'      => 'эки юз eki yüz',
					'crh-cyrl' => 'эки юз эки юз',
					'crh-latn' => 'eki yüz eki yüz',
				],
				'эки юз eki yüz'
			],
			[ // ALL CAPS, made up acronyms (not 100% sure these are correct)
				[
					'crh'      => 'ÑAB QIC ĞUK COT НЪАБ КЪЫДж ГЪУК ДЖОТ CA ДЖА',
					'crh-cyrl' => 'НЪАБ КЪЫДж ГЪУК ДЖОТ НЪАБ КЪЫДж ГЪУК ДЖОТ ДЖА ДЖА',
					'crh-latn' => 'ÑAB QIC ĞUK COT ÑAB QIC ĞUK COT CA CA',
				],
				'ÑAB QIC ĞUK COT НЪАБ КЪЫДж ГЪУК ДЖОТ CA ДЖА'
			],
		];
	}
}
