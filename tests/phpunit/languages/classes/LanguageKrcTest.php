<?php
/**
 * PHPUnit tests for the Karachay language.
 * The language can be represented using two scripts:
 *  - Latin (krc-latn)
 *  - Cyrillic (krc-cyrl)
 *
 * @author Iltever
 * @file
 *
 */

/** Tests for MediaWiki languages/LanguageKrc.php */
class LanguageKrcTest extends LanguageClassesTestCase {

	private static $examples = array(
		"адам"	=> "adam",
		"алим"	=> "alim",
		"артмакъ"	=> "artmaq",
		"баш"	=> "baş",
		"бала"	=> "bala",
		"Быллым"	=> "Bıllım",
		"Важный эл"	=> "Vajnıy el",
		"власть"	=> "vlast",
		"Владимир"	=> "Vladimir",
		"гырджын"	=> "gırcın",
		"гыбы"	=> "gıbı",
		"гогуш"	=> "goguş",
		"алгъа"	=> "alğa",
		"къабыргъа"	=> "qabırğa",
		"къагъыт"	=> "qağıt",
		"гъарб"	=> "ğarb",
		"дуркъу"	=> "durqu",
		"дугъум"	=> "duğum",
		"Дууут эл"	=> "Duwut el",
		"джан"	=> "can",
		"джай"	=> "cay",
		"Джангы Джёгетей эл"	=> "Cañı Cögetey el",
		"эл"	=> "el",
		"эртде"	=> "ertde",
		"экеулен"	=> "ekewlen",
		"Европа"	=> "Yevropa",
		"объект"	=> "obyekt",
		"премьера"	=> "premyera",
		"предприятие"	=> "predpriyatiye",
		"ёзен"	=> "özen",
		"Кёнделен"	=> "Köndelen",
		"ёмюр"	=> "ömür",
		"ёнгкюч"	=> "öñküç",
		"приём"	=> "priyom",
		"заём"	=> "zayom",
		"журнал"	=> "jurnal",
		"къамыжакъ"	=> "qamıjaq",
		"ажым"	=> "ajım",
		"заман"	=> "zaman",
		"зауукъ"	=> "zawuq",
		"Зеленчюк"	=> "Zelençük",
		"иги"	=> "igi",
		"иш"	=> "iş",
		"ингир"	=> "iñir",
		"Ижалары"	=> "İjaları",
		"ай"	=> "ay",
		"тогъай"	=> "toğay",
		"йод"	=> "yod",
		"йена"	=> "yena",
		"Яникой"	=> "Yanikoy",
		"яхта"	=> "yahta",
		"Европа"	=> "Yevropa",
		"епископ"	=> "yepiskop",
		"кет"	=> "ket",
		"кел"	=> "kel",
		"кертме"	=> "kertme",
		"кырдык"	=> "kırdık",
		"Къарачай"	=> "Qaraçay",
		"къарыу"	=> "qarıw",
		"къыш"	=> "qış",
		"къуш"	=> "quş",
		"лагъым"	=> "lağım",
		"Лайпанлары"	=> "Laypanları",
		"локъум"	=> "loqum",
		"Малкъар"	=> "Malqar",
		"мен"	=> "men",
		"Минги тау"	=> "Miñi taw",
		"намыс"	=> "namıs",
		"Нарсана"	=> "Narsana",
		"Нальчик"	=> "Nalçik",
		"манга"	=> "maña",
		"меннге"	=> "menñe",
		"джангы"	=> "cañı",
		"Булунгу"	=> "Buluñu",
		"ол"	=> "ol",
		"орта"	=> "orta",
		"онеки"	=> "oneki",
		"онглу"	=> "oñlu",
		"пелиуан"	=> "peliwan",
		"палах"	=> "palah",
		"папатия"	=> "papatiya",
		"Россия"	=> "Rossiya",
		"урлукъ"	=> "urluq",
		"Расул"	=> "Rasul",
		"сабий"	=> "sabiy",
		"саугъа"	=> "sawğa",
		"суу"	=> "suw",
		"тау"	=> "taw",
		"тюкен"	=> "tüken",
		"Теберди"	=> "Teberdi",
		"тау"	=> "taw",
		"саугъа"	=> "sawğa",
		"суу"	=> "suw",
		"уллу"	=> "ullu",
		"ууакъ"	=> "uwaq",
		"Учкулан"	=> "Uçkulan",
		"уууч"	=> "uwuç",
		"келиу"	=> "keliw",
		"барыу"	=> "barıw",
		"уллайыу"	=> "ullayıw",
		"бурулуу"	=> "buruluw",
		"сууууу"	=> "suwuwu",
		"уу"	=> "uw",
		"джюлюу"	=> "cülüw",
		"къууут"	=> "quwut",
		"дауур"	=> "dawur",
		"таў"	=> "taw",
		"саўгъа"	=> "sawğa",
		"суў"	=> "suw",
		"фабрика"	=> "fabrika",
		"файда"	=> "fayda",
		"сыфат"	=> "sıfat",
		"хариф"	=> "harif",
		"хуна"	=> "huna",
		"хатер"	=> "hater",
		"Хурзук"	=> "Hurzuk",
		"цензура"	=> "tsenzura",
		"цемент"	=> "tsement",
		"ацетилен"	=> "atsetilen",
		"администрация"	=> "administratsiya",
		"чарх"	=> "çarh",
		"чабыр"	=> "çabır",
		"Чегем аууз"	=> "Çegem awuz",
		"шиндик"	=> "şindik",
		"Шыкъы"	=> "Şıqı",
		"шынкъарт"	=> "şınqart",
		"щётка"	=> "şçötka",
		"щёлочь"	=> "şçöloç",
		"Щецин"	=> "Şçetsin",
		"линъюй"	=> "linyuy",
		"объект"	=> "obyekt",
		"инъекция"	=> "inyektsiya",
		"отъявлен"	=> "otyavlen",
		"адъюнкт"	=> "adyunkt",
		"ыйыкъ"	=> "ıyıq",
		"ышыкъ"	=> "ışıq",
		"Бызынгы"	=> "Bızıñı",
		"Ставрополь"	=> "Stavropol",
		"область"	=> "oblast",
		"больница"	=> "bolnitsa",
		"пьеса"	=> "pyesa",
		"премьер"	=> "premyer",
		"Иньеста"	=> "İnyesta",
		"Кьюриосити"	=> "Kyuriositi",
		"кьянти"	=> "kyanti",
		"Ньингма"	=> "Nyiñma",
		"юлгю"	=> "ülgü",
		"юй"	=> "üy",
		"келиую"	=> "keliwü",
		"ёлюую"	=> "ölüwü",
		"юлюш"	=> "ülüş",
		"Ючкёкен"	=> "Üçköken",
		"приют"	=> "priyut",
		"адъютор"	=> "adyutor",
		"юань"	=> "yuan",
		"Юта"	=> "Yuta",
		"юбилей"	=> "yubiley",
		"юбка"	=> "yubka",
		"ювелир"	=> "yuvelir",
		"Югославия"	=> "Yugoslaviya",
		"юлиан"	=> "yulian",
		"юмор"	=> "yumor",
		"юрис"	=> "yuris",
		"юстиция"	=> "yustitsiya",
		"Яникой"	=> "Yanikoy",
		"яхта"	=> "yahta",
		"къая"	=> "qaya",
		"операция"	=> "operatsiya",
		"мекям"	=> "mekâm",
		"кямар"	=> "kâmar",
		"лячин"	=> "lâçin",
		"гяхиник"	=> "gâhinik",
	);

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToLatin() {
		// A simple conversion of Latin to Latin
		$this->assertEquals( 'abdef',
			$this->convertToLatin( 'abdef' )
		);
		// A conversion of Cyrillic to Latin
		foreach ( self::$examples as $cyrillic => $latin ) {
			$this->assertEquals( $latin,
				$this->convertToLatin( $cyrillic )
			);
		}
	}

	/** Wrapper for converter::convertTo() method*/
	protected function convertTo( $text, $variant ) {
		return $this->getLang()->mConverter->convertTo( $text, $variant );
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'krc-cyrl' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'krc-latn' );
	}
}
