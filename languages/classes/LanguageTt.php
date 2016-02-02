<?php
/**
 * Tatar specific code.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Dinar Qurbanov
 * @ingroup Language
 * this file is created by copying LanguageUzTest.php
 * some history of this file is in https://gerrit.wikimedia.org/r/#/c/164049/
 *
 */

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {

	private $test = 0;

	public $toLatin = array(
		// capital letters are handled outside
		// ( outside of place this array was planned to use,
		// and is used , ie outside of parent::translate)
		'а' => 'a', // 'А' => 'A',
		'ә' => 'ä', // 'Ә' => 'Ä',
		'б' => 'b', // 'Б' => 'B',
		'в' => 'w', // 'В' => 'W',
		'г' => 'g', // 'Г' => 'G',
		'д' => 'd', // 'Д' => 'D',
		'е' => 'e', // 'Е' => 'E',
		'ё' => 'yo', // 'Ё' => 'Yo',
		'ж' => 'j', // 'Ж' => 'J',
		'җ' => 'c', // 'Җ' => 'C',
		'з' => 'z', // 'З' => 'Z',
		'и' => 'i', // 'И' => 'İ',
		'й' => 'y', // 'Й' => 'Y',
		'к' => 'k', // 'К' => 'K',
		'л' => 'l', // 'Л' => 'L',
		'м' => 'm', // 'М' => 'M',
		'н' => 'n', // 'Н' => 'N',
		'ң' => 'ñ', // 'Ң' => 'Ñ',
		'о' => 'o', // 'О' => 'O',
		'ө' => 'ö', // 'Ө' => 'Ö',
		'п' => 'p', // 'П' => 'P',
		'р' => 'r', // 'Р' => 'R',
		'с' => 's', // 'С' => 'S',
		'т' => 't', // 'Т' => 'T',
		'у' => 'u', // 'У' => 'U',
		'ү' => 'ü', // 'Ү' => 'Ü',
		'ф' => 'f', // 'Ф' => 'F',
		'х' => 'x', // 'Х' => 'X',
		'һ' => 'h', // 'Һ' => 'H',
		'ц' => 'ts', // 'Ц' => 'Ts',
		'ч' => 'ç', // 'Ч' => 'Ç',
		'ш' => 'ş', // 'Ш' => 'Ş',
		'щ' => 'şç', // 'Щ' => 'Şç',
		'ъ' => '', // 'Ъ' => '',
		'ы' => 'ı', // 'Ы' => 'I',
		// 'ь' => '', // 'Ь' => '',
		'ь' => '\'', // 'Ь' => '',
		'э' => 'e', // 'Э' => 'E',
		'ю' => 'yu', // 'Ю' => 'Yu',
		'я' => 'ya', // 'Я' => 'Ya',
	);

	public $toCyrillic = array(
		'a' => 'а', // 'A' => 'А',
		'ä' => 'ә', // 'Ä' => 'Ә',
		'b' => 'б', // 'B' => 'Б',
		'c' => 'җ', // 'C' => 'Җ',
		'ç' => 'ч', // 'Ç' => 'Ч',
		'd' => 'д', // 'D' => 'Д',
		'e' => 'е', // 'E' => 'Е',
		'f' => 'ф', // 'F' => 'Ф',
		'g' => 'г', // 'G' => 'Г',
		'ğ' => 'г', // 'Ğ' => 'Г',
		'h' => 'һ', // 'H' => 'Һ',
		'i' => 'и', // 'İ' => 'И',
		'ı' => 'ы', // 'I' => 'Ы',
		'j' => 'ж', // 'J' => 'Ж',
		'k' => 'к', // 'K' => 'К',
		'l' => 'л', // 'L' => 'Л',
		'm' => 'м', // 'M' => 'М',
		'n' => 'н', // 'N' => 'Н',
		'ñ' => 'ң', // 'Ñ' => 'Ң',
		'o' => 'о', // 'O' => 'О',
		'ɵ' => 'ө', // 'Ɵ' => 'Ө',
		'ö' => 'ө', // 'Ö' => 'Ө',
		'p' => 'п', // 'P' => 'П',
		'q' => 'к', // 'Q' => 'К',
		'r' => 'р', // 'R' => 'Р',
		's' => 'с', // 'S' => 'С',
		'ş' => 'ш', // 'Ş' => 'Ш',
		't' => 'т', // 'T' => 'Т',
		'u' => 'у', // 'U' => 'У',
		'ü' => 'ү', // 'Ü' => 'Ү',
		'v' => 'в', // 'V' => 'В',
		'w' => 'в', // 'W' => 'В',
		'x' => 'х', // 'X' => 'Х',
		'y' => 'й', // 'Y' => 'Й',
		'z' => 'з', // 'Z' => 'З',
		// '\'' => 'э', // '’' => 'э',
		// i have changed this, i think ь is more frequent
		'\'' => 'ь', // '’' => 'ь',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tt-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'tt-latn' => new ReplacementArray( $this->toLatin ),
			'tt' => new ReplacementArray()
		);
	}

	function convertLowercasedTatarWordCyrlToLat( $text ) {
		// $text is not only lowercased, it is also deabbreviated,
		// so that abrreviations are splitted into single capital letters
		// and words with fisrt capital letter.
		// but previously this function handled also abbreviations
		// and code for some of them is still here.
		// compounds
		if (
			// preg_match( '/илбашы|аскорма|аккош|коточкыч/u', $text )
			// need to add аскорма, even if its both parts are hard/thick, because else
				// it goes into russian part/branch because of о at second syllable
				// i comment this out and add to other checks to split them
			// no need to add бер(рәт|ничә|дәнбер),
			// because their both parts are soft/mild/thin
			// preg_match( '/берникадәр/u', $text )
			// preg_match( '/^[^әэеөүи]+баш/u', $text )
			false==true
		) {
			// code that can be used for debug
			// $text = '{'. $text. '}';
			// return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		}elseif ( preg_match( '/стәрлетама/u', $text ) ) {
			$parts = preg_split( '/(?<=^......)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/^(җигәнтама|йөрәктау|тимер(булат|чыбык)|кайсыбер)/u', $text )
		) {
			$parts = preg_split( '/(?<=^.....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/^([кт]өнья[кг]|биектау|күпьеллы[кг]'
			. '|карабодай|якшыгол|ташъязма|шәльяу)/u', $text )
		) {
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/^супер.+/u', $text ) ){
			$parts = preg_split( '/(?<=^.....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] =
				$this->convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/^(вики|гига|авто).+/u', $text ) ){
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] =
				$this->convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/^(көн(чыгыш|батыш)|гөлбакча|ч[иы]ная[кг]|башкор[тд]'
			. '|коточкыч'
			. '|бер(аз|кат|кай(чан)?|туган|вакыт)'
			. '|кайбер|һәрчак|унъел|кол(мәт|сәет)|күктау|акъегет|билбау'
			. '|күзал)/u', $text ) ) {
			// сәет is arabic word but probably it works well as tatar word
			$parts = preg_split( '/(?<=^...)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/.+су(?<!^басу)(?<!^ысу)(?<!^консу)/u', $text ) ) {
			$parts = preg_split( '/(?=су)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/.+сыман/u', $text ) ) {
			$parts = preg_split( '/(?=сыман)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/.+баш/u', $text ) ) {
			$parts = preg_split( '/(?=баш)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/.+намә/u', $text ) ) {
			$parts = preg_split( '/(?=намә)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertLowercasedTatarWordCyrlToLat( $parts[0] );
			$parts[1] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/(?<!^кукм)(?<!^[аә]нк)(?<!^[бкч])ара$/u', $text ) ) {
			// except кукмара, анкара, бара, чара, кара
			$parts = preg_split( '/(?=ара)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/аскорма|аккош|суүсем|әрдоган|азкөч'
			. '|юлтимер|аккирәй|айгөл/u', $text ) ) {
			$parts = preg_split( '/(?<=^..)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/^бөекбрит/u', $text ) ) {
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			// return $this->convertCompoundWordCyrlToLat( $parts ); // it
				// works but let i make this "manually", to avoid going
				// through this check again (in recursion)
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->processWordWithRussianStemCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/никадәр/u', $text ) ) {
			$parts = preg_split( '/(?<=^..)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/берникадәр/u', $text ) ) {
			$parts = preg_split( '/(?<=^.....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/^(габдел.+|солтангали|гыйлемхан)/u', $text ) ) {
			$parts = preg_split( '/(?<=^......)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif ( preg_match( '/оглу/u', $text ) ) {
			$parts = preg_split( '/(?=оглу)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			// $text = $this->convertCompoundTatarWordCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}elseif ( preg_match( '/.+стан(?!т(ин|а))/u', $text ) ) {
			// need to add стан even after hard syllables because else if it is after
			// consonant there are 3 consecutive consonants and it goes to russian branch
			// except константин
			$parts = preg_split( '/(?=стан)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->
				convertLowercasedTatarWordCyrlToLat( $parts[0] ); // recursion
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			// return $parts[0] . parent::translate( $parts[1], 'tt-latn' );
			return $parts[0] . $parts[1];
		}elseif ( preg_match( '/известьташ/u', $text ) ) {
			// this works even without splitting,
			// but а in таш is tatar а, so i better split this
			$parts = preg_split( '/(?<=ь)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertRussianWordWithTatarEndingCyrlToLat(
				$parts ); // таш is not ending but this works
		// end of compounds
		}elseif ( preg_match( '/(?<=.)(?<!сосн|град)(?<!самин|датир)(?<!петропавл)'
			. '(?<!покр)(?<!^новоникола)'
			// russian village names сосновка, саминовка,
			// константиноградовка, покровка, etc, датировка
			// where ка is not tatar ending
			. '[ое]в'
			. '(?=а?[гк]а(ча)?|[дт]а(н|гы|й)?|ның?|ч[ае]|лар)/u',
			$text )
		) {
			// catching only words with suffixes after ov, ev suffix
			// words with ov, ev at end should not / must not be catched here,
			// because then most of them would go into infinite recursion.
			// then
			// split words with ov/ev suffix after the suffix: вәлиев|ка.
			// ( this code does not split ov, ev from stem ).
			$parts = preg_split( '/(?<=[ое]в)/u', $text,
				null, PREG_SPLIT_NO_EMPTY ); // i do
				// not include here а after ов/ев because
				// look behind regex should be fixed length
			$parts[0] = $this->
				convertLowercasedTatarWordCyrlToLat( $parts[0] );
					// family name may be wih russian or arabic or turkic stem
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			return $parts[0] . $parts[1];
		}elseif (
			// arabic words which look like russian words and go into russian branch
			// before checking for signs of arabic words, so i check them here,
			// before reaching checking of signs of russian words
			// and even if i do not allow гаяре, гаебе to
			// go to russian branch, they are not catched by arabic check and
			// go into tatar branch
			// фирдәүс was not catched by russian branch, but i add it here
			preg_match(
				// әэ for тәэссорат, and i remove it from main check of arabic
				// аэтдин for ризаэтдин (then аэ all are catched by check of russian)
				// васыят is for васыяте, and also catches васыять.
				// ать for табигать
				// бигате for табигате
				// гыйффәт has come here after i added фф as sign of russian word
				'/әэ'
				. '|^г(а((ярь|фиятулл)|(еб|яр|й[рн])е|((лим|дел)(е|ле|сез)?)|епле)'
					. '|о(мер|реф|шер)|ыйффәт)'
				. '|ка(бер|леб|дер)|маэмай|вилаят|аэтдин|ильяс'
				. '|васыят|ать|фирдәүс|бигате|фиркасе|ядкар|шагыйре|ризыклары'
				. '|^гамь|^гаме(?!т)/u',
				$text )
		) {
			return $this->processWordWithArabicStemCyrlToLat( $text );
		}elseif (
			// signs of russian words
			preg_match( '/^[бвгджзклмнпрстфхцчшщ]{2,}/u', $text )
			|| preg_match( '/[ёцщ]/u', $text )
			|| preg_match( '/[бвгджзклмнпрстфхчш][яюёэ]/u', $text )
			// автор etc.
			// there is rule to not write о in syllables except first:
			// tatar word соло is written солы
			// except family names ending with ов(а)
			|| preg_match( '/.{2,}о(?!ва?$)/u', $text )
			// катер etc; should not catch сараенда
			// гаяре, гаебе would be catched here,
			// but so i catch such arabic words before
			// reaching to these checks. i have tried
			// to add here .+ at end of regex but then
			// they go to tatar branch, if i do not add them in arabic check.
			// also e at end of word is also possible in russian words: каре, пеле, желе
			// so i better check them before here
			// катер, кушкет
			// this regex much of words like ядкарьлек, i am catching them before
			// reaching this russian check for now
			|| preg_match( '/^[^әөүҗңһ]*[аоу][^әөүҗңһ]*[бвгджзклмнпрстфхцчшщ]+е/u',
				$text )
			// стенкасы etc
			|| preg_match( '/^[^әөүҗңһ]*[бвгджзклмнпрстфхцчшщ]+е[^әөүҗңһ]*[аоу]/u',
				$text )
			// 3 consecutive consonants except артта, астта, антта,
			// and except югалтса, югалтмаган, югалткан, утыртканнар
			|| preg_match(
				'/[бвгджзклмнпрстфхцчшщ]{3,}(?<![рнс]тт)(?<![лр]т[смк])/u',
				$text )
			// || preg_match( '/в[бвгджзклмнпрстфхцчшщ]|[бвгджзклмнпрстфхцчшщ]в/u',
				// $text )
			|| preg_match( '/в[вгдзклмнртхцчш]/u', $text )
			// thick ия except ихтияҗ which is rare or incorrect spelling of ихтыяҗ
			// but i do not add it, (?<!^ихт)
			|| preg_match( '/(?=ия.+[аыоу])(?!иятулл)/u', $text )
			// гектар etc; but should not catch оешмасы, so /^.е.+а/ is not enough
			|| preg_match( '/^[бвгджзклмнпрстфхцчшщ]е.+а/u', $text )
			// натураль etc except шөгыль, мөстәкыйль, гыйльфан, мәшгуль, мәкаль
			|| preg_match( '/(?<![гк][ыу])(?<!([гк]ый|әка))ль/u', $text )
			// синерь etc. should not catch шигырь, шагыйрь, бәгырь
			// and words like карьер, барьер
			|| preg_match( '/ерь|рье|еви|изм/u', $text )
			// тангаж etc
			|| preg_match( '/.{3,}ж|^в$/u', $text )
			// words like винт, грамм, штутгард, бриг,
			// шпиг, во, волга, вьет, etc
			// ^ви also catches вилаять, but seems it is only one such arabic words
			// so i catch it before russian words.
			// красивый, трамвай
			// версия, веб
			// шк for шкетан
			|| preg_match( '/^(в[иоье]|ш[тпк]|[пгбкт]р|сс|гд)/u', $text )
			// акт, пакт, тракт, etc
			// i do not add here да, га, лар suffixes, because with them it is catched
			// because of 3 consecutive consonants
			// поезд
			|| preg_match( '/(акт|зд)($|ы($|м|ң|н|[бг]ыз))/u', $text )
			// физика etc except шикаять, драматург etc except ургы-, тургай
			|| preg_match( '/.+(?<!ш)ика|атург/u', $text )
			// товар, овал, "ов"
			|| preg_match( '/^.?ов/u', $text )
			// вариант, авиа, шоу, аэро, поэма, пуэр, ноябрь, ноябре
			|| preg_match( '/[аоу]э|их?а|оу|бр[ье]|ио/u', $text )
			// other russian words
			// кукмара is tatar word but it work ok as russian word (its у is slightly
			// different than russian у, but it is not shown in the latin)
			// i do not add кафтан because there is tatar word qaftan
			|| preg_match( '/^(к('
				. 'а(н(дидат|а(л|да|ш)|түк)|бина|м(аз|зул|би)'
					. '|р(м(алка|ик)|амзин|т(ина|уз))'
					. '|у(чук|ф)|за(нка|да|ки)|лий|питал|спий|чал)'
				// do not catch карлар
				. '|арл($|ын|да(н|гы)?|га(ча)?|сыз|ның)'
				// do not catch камалыш
				. '|ама($|сын|да(н|гы)?|га(ча)?|сыз|ның)'
				. '|у(р(с|асан|тка)|к(мара|уруза)|туз|зьм|льт|бан|пала)'
				. '|о(м(исс|анда|мун|б(инат|айн))'
					. '|н(с(тан|ул)|ка)'
					. '|д($|ын?|та(н|гы)?|ка(ча)?|сыз|чы|ның|иров|лы)'
					. '|жан|фта|с(ынка|та)|р(аб|пус)|лум|ка|ала)'
				. '|изнер'
				. '))/u', $text )
			// i try ив$ for объектив etc
			// фф for эффект, etc
			|| preg_match( '/актив|^нигерия|импер|^ефрат|тугрик|сигнал'
				. '|^ив|ив$|фф/u', $text )
			|| preg_match(
				'/^г((аз|ол?)($|да(н|гы)?|га(ча)?$|сыз|чы|ның?|ын?|лар)'
				// i do not add ga here because there is tatar suffix
				// ğa which can be used separately: 6 ğa.
				. '|а(ук|на|м(аш|бия)|зимов|р(ант|сия)|лмудуг)'
				. '|(о(р(илла|ки|ьк)|а|би|лланд))'
				. '|вин|ум(анитар|ми)|еве)/u',
				$text )
			// i cannot use here just ^ав because there are
			// tatar words авыр, каравыл, etc
			// i removed авиа and added иа
			// i do not add ав$ for состав, because составы then still need to be catched
			|| preg_match( '/ав(а(тар|рия))/u', $text )
			|| preg_match( '/шпага|^дог($|ка(ча)?|та(н|гы)?|ның?)|юмья/u',
				$text ) // дог
				// is rare word but it is in the test
			// мануфактура, диктатура etc
			|| preg_match( '/.+тура/u', $text )
			// tatar and arabic words except words starting with к г
			// these would go into *arabic* branch, that also work ok for now, but i
			// send багира, тагил here because problems may appear
			// with г, and send химия here,
			// because problems may appear with thick и.
			// бензинсыз was detected as arabic, but
			// бензин goes to tatar branch, so add it there.
			// самин for village name саминовка
			|| preg_match(
				'/^(б(агира|о(л(гария|ив(ия|ар))|тинка))|тагил|х(орватия|ими|арьк)|маугли'
					. '|юбиле|л(и(га|тва)|атвия)|система|республика'
					. '|м(а(гнит|ксим|ргарита)|олдавия)|органик|извест|нефть'
					. '|и(юн|жау|нка)|дев|самин|эфир|алфавит|васили|савин'
					. '|нижгар|пермь|адмирал|дискусс|эласт|сингапур|физи)'
				. '/u', $text )
			// these would go into *tatar* branch
			// i was going to add karta into russian but there is also tatar qarta
			// and other word for karta - xarita in literary language
			// karta and qarta cannot be distinguished without semantics or context
			// added уганда but there is also tatar word уганда, they cannot be
			// distinguished without semantic check. also канар, but i do not add it.
			// also family name кафка - kafka, i leave it as tatar or arabic qafqa
			// also there is tatar word авар
			|| preg_match(
				'/^(м(узыка|елвин|а(даг|ршан|йка))'
					. '|в(епс|ышка|алда|уз|акуум)'
					. '|п(а(уза|рагвай)|одкаст)|(за|у)каз'
					. '|с(о(с(тав|нов)|к(рат)?|г)|а(рат|ван|па)|ьерра|ервер)'
					. '|б(ензин|исау|ушу|а(вар|рнаул|нк|йт)|урка)'
					. '|энерг|юлия|фея|яков|порту'
					. '|лив(?!ә)|уганда|елена|тиран'
					. '|о(скар|трад|кт)'
					. '|д(екрет|углас)|июл|н(аум|ерв)|талала'
					. '|режисс|фуфай'
					. '|ла(всан|йкра|нка)|пояс|йошкар|ноя|датир|чабаксар?'
					. '|а(ксаков|грар|угуст)'
					. '|т(анк|о[кг])|юридик|янка|сервис)'
					. '|хавьер|уаскаран|марк'
					// ола should be added here but i leave it
					// because it works as tatar word
				. '|бург|пед/u'
				, $text )
				// все was for ТВсе, removed
		) {
			return $this->processWordWithRussianStemCyrlToLat( $text );
		}elseif (
			// signs of arabic words
			// и after 2 letters except юри, эшли, // боулинг(?!нг)
			// i comment this out because there much words like редакцияли
			// i have added и to next rule, in [әүи].
			// preg_match( '/.{2,}и(?<!^юри$)(?<!^эшли$)/u', $text )
			// i cannot add here е after а becausee of tatar words
			// like сагаеп
			// тәкъдим, куәт,
			preg_match( '/.*[аыоуъ].*[әүи]/u', $text )
			|| preg_match( '/.*[әөи].*[аыуъ]/u', $text )
			// тәэмин, тәэсир, i do not add аэ for маэмай here, i can catch words
			// like аэробус with аэро, but i better catch them with аэ, and маэмай
			// is probably only one word like that, and catch маэмай
			// before russian words
			// || preg_match( '/әэ/u', $text ) // i moved this to first check of arabic
			// || preg_match( '/ьә/u', $text ) // replaced by the next replace
			// гаять etc except яшь юнь etc
			|| preg_match( '/(?<!^[яю].)ь(?!е)/u', $text )
			// әдәбият etc
			|| preg_match( '/ият/u', $text )
			// i cannot catch words гаяре, гаебе with signs because there are
			// such words in tatar: бая, сагаеп
			// also they tend to enter russian branch
			// so i catch them before russian words check.
			// other arabic words:
			|| preg_match( '/куф[аи]|^г(ае[пт]|ыйлем|омум)|^рия/u',
				$text )
			// робагый, корбан, әдәби, мәдәни have gone to tatar branch
			// but i do not need to fix them
			// мөэмин
			|| preg_match( '/өэ/u', $text )
			|| preg_match( '/улла/u', $text )
		) {
			return $this->processWordWithArabicStemCyrlToLat( $text );
		}else {
			return $this->processWordWithTurkicStemCyrlToLat( $text );
		}
	} // convertLowercasedTatarWordCyrlToLat

	function processWordWithRussianStemCyrlToLat( $text ) {
		// process words with russian stem
		// words that are russian in cyrillic but tatarised in the latin
		// июнь, июль
		if ( preg_match( '/^ию[нл]|апрель/u', $text ) ) {
			return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		}
		// октябрь
		$parts = preg_split( '/(?<=т)(?=ябр[ье])(?!ябрьское)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			$parts[1] = preg_replace( '/ябрь?/u', 'äber', $parts[1] );
			$parts[0] = $this->processWordWithRussianStemCyrlToLat( $parts[0] );
			$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			return $parts[0] . $parts[1];
		}
		$parts = preg_split( '/(?<=а)(?=бр[ье])/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			$parts[1] = preg_replace( '/брь?/u', 'ber', $parts[1] );
			$parts[0] = $this->processWordWithRussianStemCyrlToLat( $parts[0] );
			$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			return $parts[0] . $parts[1];
		}
		// $parts = preg_split( '/(?<=^но)(?=ябр[ье])/u',
		$parts = preg_split( '/(?<=^но)(?=я)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			$parts[1] = preg_replace( '/ябрь?/u', 'yäber', $parts[1] );
			// for "Ноя"
			$parts[1] = preg_replace( '/я/u', 'yä', $parts[1] );
			$parts[0] = $this->processWordWithRussianStemCyrlToLat( $parts[0] );
			$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			return $parts[0] . $parts[1];
		}
		// turkish word, it comes to russian branch, but that is ok for now
		$text = preg_replace( '/эрдоган/u', 'ärdoğan', $text );
		// search for tatar endings
		// ...лык/лек
		// except электр, молекула, телеграмма, александр, алекс, алексеевск,
		// интеллектуаль, коллектив
		$parts = preg_split( '/(?=л[ые][кг])(?!ле(к(т(р|у|и)|ул|с|ом)|г(рам|ац)))/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...ия
		$parts = preg_split( '/(?=ия)(?!иятие)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		// ^ it is easier to pass the ia suffix into tatar part
		// this makes aeratsiä, i would like to write aeratsiya, but
		// aeratsiä is also ok
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...га/гә/ка/кә/кача/кәчә/гача/гәчә
		// i do not add exceptions to the following village names with russian suffix ka
		// because
		// there are also family names with tatar suffix qa and they cannot be
		// distinguished without semantics or context
		// Александровка Ивановка Петровка Ульяновка Андреевка Павловка
		// Михайловка Косяковка Боголюбовка Казадаевка
		// Васильевка Веденовка Владимировка Григорьевка Дергачёвка
		// Кузьминовка Александровка Максимовка Марьевка
		// Микрюковка Михайловка Наумовка Николаевка Озерковка
		// Петровка Преображеновка Рязановка Соколовка Стрелковка
		// Талалаевка Танеевка Отрадовка
		$parts = preg_split(
			'/((?<!и|^ш)(?<!^(ёл|де|ян|ко|ин))'
			. '(?<!^(шпа|щёт|вол|выш|май|бур|кеп|кон|оме|бан|лан))(?<!нош)'
			. '(?<!ссыл|музы|трич|стен|тчат|агер|рмал|кров|деле|олив|омег|курт'
			. '|ст[её]р|грей|уфай|осын|убан|ураж|отин|рчат|наго|иров|инов|укот|мани'
			. '|фоль|клад|иров|анио)'
				// убли for республика, публика
				// тани for ботаника
				// исти for характеристика
				// try (?<!и) for them
				// was (?<!^(ёл|ли))(?<!(физ|мат|тан|убл|ист)и|ссыл|музы)
				// but i have problem with минскига - fixed
				// трич - электричка
				// тчат - клетчатка
				// агер - лагерка
				// рмал - кармалка
				// нош - золотоношка
				// кров - покровка
				// де - декабрь
				// стер - гимнастерка
				// added иров for датировка and it still did not work - if you
				// have such case, add it also to ов|.. division,
				// near compounds division
				// укот - чукотка
				// анио - маниока
				// ин - сапа инка
			. '(?<!казан|любов)'
			. '(?<!соснов|шешмин|градов|амазон|титика)'
			. '(?<!саминов)'
			. '(?<!кондоркан)'
			. '(?<!петропавлов)'
			. '(?<!новониколаев)'
			. '|(?<=ски))'
			. '(?=[гк][аә](ч[аә])?$)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// гы, кы, ге, ке, but not го|ген, ве|гетатив, врун|гель,
		// not ш|кетан, драматур|гы, о|кеан, ма|кедония
		$parts = preg_split(
			'/(?<!аматур)(?<!о)(?<!^ма)(?<!^хок)(?=[гк][ые]($|[^нтр]))(?!гель)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...ны, не
		$parts = preg_split( '/(?=н[ые]$)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...сы/сын/etc
		$parts = preg_split( '/(?<!^ко)(?<=[ао])(?=(сы$|сын))/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...е
		// юбилее, валдае
		if ( preg_match( '/[еа](?=(е$|ен))/u', $text ) ) {
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/е(?=(е$|ен))/u',
					// $text, $this->currentWordTargetLetterCase );
			// $text = preg_replace( '/е(?=(е$|ен))/u', 'ей', $text );
			// $parts = preg_split( '/(?<=ей)(?=(е$|ен))/u',
				// $text, null, PREG_SPLIT_NO_EMPTY );
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/[еа](?=(е$|ен))/u',
					// $text, $this->currentWordTargetLetterCase );
			$parts = preg_split( '/(?<=[еа])(?=(е$|ен))/u',
				$text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] .= 'й';
			$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// .../ы/ын/etc
		// i had to move this after "...е" because "юбилеендагы" had become
		// юбилеендаг|ы but this is probably not enough
		// indeed now i have графиктаг|ы so i should move this after "da",
		// but then графигында will work wrongly: графигын|да, графын|да.
		// then i will recursively split first part again. i made that but
		// i have графиктаг|ы, it should be график|та|гы, so, i cannot split
		// ы after г, or should move this after "да", if move after da, i still will
		// have problem with кышкы etc. then, i will separate гы separately.
		$parts = preg_split( '/(?<!^борат)(?<!^кос)'
			. '(?<=[бвгджзклмнпрстфхцчшщ])(?=(ы($|н)))/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// октябрендә
		$parts = preg_split( '/(?<=[яа]бр)(?=(е($|н)))/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			$parts[0] .= 'ь';
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...да/дан/дагы/дә/та/тә/etc
		// except шке|тан, бри|тания, маргарита, константа
		// $parts = preg_split( '/(?=[тд][аә](н|[гк][ые])?$)/u',
		$parts = preg_split( '/(?<!^(шке|бри|коф|ари))'
			. '(?<!^конс)(?<!^констан)(?=[тд][аә]н?$)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...лар/etc except семинар, семинария, коммунар, кушнаренко
		$parts = preg_split( '/(?<!семи|омму)(?=[лн][аә]р)(?!наренко)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...чык/etc
		// каналчык
		$parts = preg_split( '/(?=ч[ые]к)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// ...ла/etc except плаксиха, власть, эндоплазма, молекула, югославия,
		// and except балакирев, ватслав (cannot check with лав because of лавы ending)
		// активлаштырылган, дуглас, гуммиластик
		$parts = preg_split( '/(?<!^.)(?<!леку|ярос|нико|югос|купа|авос|умми)'
			. '(?<!вац|обк|шка|дуг|коа)(?<!^(ве|га))'
			. '(?=л[аә])'
			. '(?!ла(зма|кир))/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// tatar suffixes are not found
		// $text = $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
		return $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
	} // processWordWithRussianStemCyrlToLat

	function processWordWithArabicStemCyrlToLat( $text ) {
		// process words with arabic stem
		// family name with ov(a)
		// нәгыймов
		$parts = preg_split( '/(?=ова?$)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertArabicWordWithRussianEndingCyrlToLat( $parts );
		}
		// family name with ev(a)
		// рәмиев, вәлиев, ...галиев, ...хәев
		if ( preg_match( '/.+ева?/u', $text ) ) {
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[аоыуәе])е(?=ва?)/u',
					// $text, $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?)/u', 'йе', $text );
			$parts = preg_split( '/(?=ева?)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			return $this->convertArabicWordWithRussianEndingCyrlToLat( $parts );
		}
		// ...ла,лау,лама,лаучы,...
		// тасвирлау but not in мөлаем
		// better not to make го|ләма
		$parts = preg_split( '/(?=л[аә]($|([уү]|м[аә]|яч[аә]к)(ч[ые])?))(?!л(аем|әма|амә))'
			. '(?<!^бә)(?<!ул)(?<!^мәка)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
		}
		// for китабындагы, but it is converted ok without this
		// // ...сы/сын/etc
		// $parts = preg_split( '/(?<=[ао])(?=(сы$|сын))/u',
			// $text, null, PREG_SPLIT_NO_EMPTY );
		// if ( count( $parts ) == 2 ) {
			// $text = $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }
		// // .../ы/ын/etc
		// $parts = preg_split( '/(?<=[бвгджзклмнпрстфхцчшщ])(?=(ы$|ын))/u',
			// $text, null, PREG_SPLIT_NO_EMPTY );
		// if ( count( $parts ) == 2 ) {
			// $text = $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }elseif ( count( $parts ) > 2 ) {
			// $parts[1] = implode( array_slice( $parts, 1 ) );
			// array_splice( $parts, 2 );
			// $text = $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }
		// ...е
		// икътисадые
		if ( preg_match( '/ы(?=(е$|ен))/u', $text ) ) {
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/ы(?=(е$|ен))/u',
					// $text, $this->currentWordTargetLetterCase );
			$parts = preg_split( '/(?<=ы)(?=(е$|ен))/u',
				$text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] .= 'й';
			if ( preg_match( '/[әөеүи][^аоыуиәөеүи]+ы(е$|ен)/u', $text ) ) {
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			}else {
				$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
			}
			// // иҗтимагый should be ictimaği, see
			// // https://tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
			// // so иҗтимагыенда has to be ictimağiyında
			// if ( $parts[0]=='икътисадый' || $parts[0]=='иҗтимагый' ) {
				// $parts[1] = 'й' . $parts[1];
			// }
			return $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
		}
		// suffixes are not found
		return $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
	} // processWordWithArabicStemCyrlToLat

	function processWordWithTurkicStemCyrlToLat( $text ) {
		// process words with turkic stem
		// word is not recognised as russian nor arabic,
		// so it is basic/plain/simple/turkic tatar word
		// family name with ov(a)
		$parts = preg_split( '/(?=ова?$)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			// $text = $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
		}
		// family name with ev(a)
		if ( preg_match( '/.+ева?$/u', $text ) ) {
			// i do not delete these commented out code
			// because these places may be helpful to make replace
			// of character for stress
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[аоыуәе])е(?=ва?$)/u',
					// $text, $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?$)/u', 'йе', $text );
			$parts = preg_split( '/(?=ева?$)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			// $text = $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
		}
		// russian suffixes are not found
		// $text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
	}

	function convertTatarWordWithRussianRootFromCyrillicToLatin( $text ) {
		// dirijabl or dirijabl' or dirijabel ?
		// i leave it as it is : dirijabl'
		// v
		$text = preg_replace( '/в(?!(еб|ики))/u', 'v', $text );
		// я
		// девичья
		$text = preg_replace( '/(?<=ч)ья/u', 'ya', $text );
		// е
		// премьер, поезд, менделеевск, бушуев, казадаев
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=[ьо])е/u',
				// $text, $this->currentWordTargetLetterCase );
					// change case array
		$text = preg_replace( '/(?<=[аоуеьъ])е/u', 'ye', $text );
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/^е/u', $text,
				// $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^е/u', 'ye', $text );
		$text = preg_replace( '/(?<=режисс)е/u', 'yo', $text );
		$text = preg_replace( '/(?<=фл)е(?=ра)/u', 'yo', $text );
		$text = preg_replace( '/(?<=ст)е(?=рка)/u', 'yo', $text );
		$text = preg_replace( '/(?<=арт)е(?=м)/u', 'yo', $text );
		$text = preg_replace( '/(?<=зыр)е(?=к)/u', 'yo', $text );
		$text = preg_replace( '/(?<=кул)е(?=з)/u', 'yo', $text );
		$text = preg_replace( '/(?<=лонт)е(?=р)/u', 'yo', $text );
		// ё
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[щч])ё/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[щч])ё/u', 'o', $text );
		// ю
		// $text = preg_replace( '/(?<=ь)ю/u', 'yu', $text );
		// ау, оу
		// шоу, боулинг, маугли - w but in пауза, наумов, барнаул it is u
		$text = preg_replace( '/(?<=[оа])(?<!па|на)у/u', 'w', $text );
		// ь before ю
		// компьютер
		$text = preg_replace( '/ь(?=ю)/u', '', $text );
		// ь before е
		// премьер
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/ь(?=ye)/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь(?=ye)/u', '', $text );
		// // брь
		// moved this to processWordWithRussianStemCyrlToLat
		// $text = preg_replace( '/брь/u', 'ber', $text );
		$text = parent::translate( $text, 'tt-latn' );
		// $this->returnCaseOfCyrillicWord( $text );
		if ( $this->test ) $text = '['. $text. ']';
		// $text = $text . ']';
		return $text;
	} // convertTatarWordWithRussianRootFromCyrillicToLatin

	function convertTatarWordWithArabicRootFromCyrillicToLatin( $text ) {
		// a,ы,ый,ыя after thin vowel/syllable
		// should i also change a to ä after ğq because of previous vowels?
		// - i do not know, i do not do this for now:
		$text = preg_replace( '/^рөкъга/u', 'röqğä', $text );
		$text = preg_replace( '/^бәла/u', 'bälä', $text );
		$text = preg_replace( '/^бәһа/u', 'bähä', $text );
		$text = preg_replace_callback( '/^һәла([кг])/u',
			function( $m ) {
					return 'hälä' . ( $m[1] == 'к' ? 'k' : 'g' );
			},
			$text );
		$text = preg_replace_callback( '/^әхла([кг])/u',
			function( $m ) {
					return 'äxlä' . ( $m[1] == 'к' ? 'q' : 'ğ' );
			},
			$text );
		$text = preg_replace( '/^мәка/u', 'mäqä', $text );
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^тәрәкк)ы(?=й)/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккый/u', 'täräqqi', $text );
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=^тәрәккы)я/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккыя/u', 'täräqqiä', $text );
		$text = preg_replace( '/^нәгыйм/u', 'näğim', $text );
		// a
		// a, ya before or after thin syllable
		// no rule of alif -> a in the latin alphabet, so many words
		// should be written as spoken, some of them with synharmonism
		// $text = preg_replace( '/(?<=ид)а(?=рә)/u', 'ä', $text ); // i do
			// not change this one, because sometimes it is pronounced with a
			// and sometimes it would be ugly with ä
		// also i do not change әмм(а), мөл(а)ем, әхл(а)к
		// see some words after replacing g
		$text = preg_replace( '/(?<=сәл)а(?=м)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^(җин|вил|ниһ))а(?=я)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^вәк)а(?=л)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^гыйб)а(?=[рд])/u', 'ä', $text );
		$text = preg_replace( '/(?<=х)а(?=нә)/u', 'ä', $text );
		$text = preg_replace( '/(?<=н)а(?=мә)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^фирк)а/u', 'ä', $text );
		// $text = preg_replace( '/(?<=т)а(?=биг)/u', 'ä', $text );
		// i left this ^ with thick a for now, as in cyrillic
		$text = preg_replace( '/(?<=^х)а(?=ләт)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^рив)а(?=ят)/u', 'ä', $text );
		// "ия" by default converted as iä, see below
		$text = preg_replace( '/(?<=^ри)я/u', 'ya', $text );
		$text = preg_replace( '/(?<=афи)я(?=т)/u', 'ya', $text );
		// я before thin syllables
		// should i also change choose я as yä because of next vowels, in
		// words without ка/кы etc? - i write only exceptions,
		// because i am not sure that a changes to ä (no rule of alif -> a,
		// but some words may be pronounced with a) - with adding of сәяси
		// i decide to add a rule for them all, and commented these out:
		// $text = preg_replace( '/^ягъни/u', 'yäğni', $text );
		// $text = preg_replace( '/^яки/u', 'yäki', $text );
		// $text = preg_replace( '/^җинаят/u', 'cinäyät', $text );
		// i moved this replaces to after къ,гъ replaces, and commented these out:
		// $text = preg_replace( '/^я(?=.+[әеүиь])/u', 'yä', $text );
		// $text = preg_replace( '/(?<=а)я(?=.+[әеүиь])/u', 'yä', $text );
		// q
		// q after thick vowel/syllable
		// әхлак, рәфыйк
		$text = preg_replace( '/(?<=^әхла)к/u', 'q', $text );
		$text = preg_replace( '/(?<=^рәфый)к/u', 'q', $text );
		$text = preg_replace( '/(?<=^ризы)к/u', 'q', $text );
		$text = preg_replace( '/(?<=^ситдый)к/u', 'q', $text );
		// һәлак is replaced fully, see above
		// к,г
		// exception to the following къ,гъ replaces
		$text = preg_replace( '/^камил/u', 'kamil', $text );
		$text = preg_replace( '/^вәк(?=[аиä]л)/u', 'wäk', $text );
		$text = preg_replace( '/^куф/u', 'kuf', $text );
		$text = preg_replace( '/ядкарь/u', 'yädkär', $text );
		// къ,гъ
		// $text = preg_replace( '/га(?=.+[әүи])/u', 'ğä', $text );
		// тәрәккыять, куәт
		$text = preg_replace( '/к(?=к?[аыоуъä])/u', 'q', $text );
		// гакыл, мәшгуль
		$text = preg_replace( '/г(?=[аыоуъ])/u', 'ğ', $text );
		// а,о,ы,у,ый,ая,ыя after к,г
		// exception to fixing vowels after къ,гъ
		// галим, гата, гади, габдел
		// $text = preg_replace( '/(?<=ğ)а(?=(лим?|та|ди|ен|бде))/u',
			// та maybe is not needed anymore
		$text = preg_replace( '/(?<=^ğ)а(?=лим?|ди|бде|фи|ни|зим|риф)/u',
			'a', $text ); // these words are pronounced that way
		// мөгаен
		$text = preg_replace( '/(?<=мөğ)а(?=ен)/u', 'a', $text );
		// $text = preg_replace( '/(?<=ğ)о(?=мум)/u', 'o', $text ); // leave this
			// as it is written in cyrillic, because i feel ğömüm strange
			// also i added гомер here but then removed
			// this maybe is not needed anymore
		// кабил, кавия, кади (village name)
		$text = preg_replace( '/(?<=q)а(?=бил|вия|ди|ри)/u', 'a', $text );
		// а,о,ы,у,ый,ая,ыя after к,г
		// fix vowels after къ,гъ
		// гамәл
		// $text = preg_replace( '/(?<=[qğ])а(?=.+[әеүиь]|е)/u', 'ä', $text );
		$text = preg_replace( '/(?<=[qğ])а(?=[^аыоуәэеөүи]+[әеүиь]|е)/u',
			'ä', $text );
		// коръән
		$text = preg_replace( '/(?<=[qğ])о(?=[^аыоуәэеөүи]{1,2}[әеүиь])/u', 'ö',
			$text );
		// шөгыле
		$text = preg_replace( '/(?<=[qğ])ы(?=[^йяе]+[әеүиь])/u', 'e', $text );
		// куәт
		$text = preg_replace( '/(?<=q)у(?=ә)/u', 'ü', $text );
		// мәшгуль
		$text = preg_replace( '/(?<=ğ)у(?=.[ье])/u', 'ü', $text );
		// // exception to next rule
		// // иҗтимагый should be ictimaği, see
		// // https://tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
		// this is not mature/elaborate rule: икътисадыенда should be iqtisadiendä then,
		// and it is hard to make for me, and it is not pronunced so,
		// so i comment this out for now
		// $text = preg_replace( '/(?<=^иҗтимаğ)ый/u', 'i', $text );
		// $text = preg_replace( '/(?<=^иqътисад)ый/u', 'i', $text );
		// гыйльфан
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=[qğ])ы(?=й.+[әеүиь])/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ])ый(?=.+[әеүиь])/u', 'i', $text );
		// җәмгыять, тәрәккые
		$text = preg_replace( '/(?<=[qğ])ы(?=(я.+[әеүиь]|е))/u', 'i', $text );
		// я after га, before thin syllables or ь
		// гаять
		$text = preg_replace( '/(?<=[ğ]ä)я(?=.+[әеүиь])/u', 'yä', $text );
		// я after кы, гы, before thin syllables or ь
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[qğ]i)я(?=.+[әеүиь])/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ]i)я(?=.+[әеүиь])/u', 'ä', $text );
		// w
		// мәүлүдова, әүхәдиева but except фирдәүс
		$text = preg_replace( '/(?<=фирдә)ү/u', 'ü', $text );
		$text = preg_replace( '/(?<=ә)ү/u', 'w', $text );
		// я
		// exception to next replaces
		$text = preg_replace( '/(?<=ба)я(?=зит)/u', 'ya', $text );
		// я before thin syllables
		// see comments above
		// ягъни, яки, җинаять, сәяси
		$text = preg_replace( '/^я(?=.+[әеүиь])/u', 'yä', $text );
		$text = preg_replace( '/(?<=[аәä])я(?=.+[әеүиь])/u', 'yä', $text );
		// е
		// ye
		// ye after ga, ә, а
		// гаять, хөсәения, мөлаем, мөгаен
		// latin a is because a in мөгаен is already converted
		// тәрәккые, мәдәние
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=[äәаa])е/u', $text,
				// $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[äәаa])е/u', 'ye', $text );
		// // икътисадые
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=ы)е/u', $text,
				// $this->currentWordTargetLetterCase );
		// $text = preg_replace( '/(?<=ы)е/u', 'yı', $text );
		// exceptions to the next ия replace
		$text = preg_replace( '/әдәбият/u', 'ädäbiyat', $text );
		$text = preg_replace( '/суфиян/u', 'sufiyan', $text );
		// ый
		// ый after thin syllable
		// рәфыйк
		$text = preg_replace( '/(?<=ә.)ый/u', 'i', $text );
		// ыя
		// васыять assuming wasiät (may be wasıyät, if
		// thickness/hardness of s is wanted to show)
		// ия
		// әдәбият is also replaced by this and i leave it so, because it is very often
		// pronounced that way, though it is әдәбийат in the cyrillic by its arabic word,
		// (but there is no such rule in the latin). i change
		// my mind, it was teached to say
		// әдәбийат in schools, and it is considered correct
		// by many people and it is more
		// often used with hard/thick endings
		// alif->a rule could not be used here because я is used both
		// for йә and йа, and alif->a rule was primarily for writing, not for
		// pronounciation, for example, сәлам is pronounced сәләм, and used with
		// thin endings: сәламебез, but somebodies started to show "alif"ness
		// of a in әдәбият with hard endings, and it has become a also in
		// pronounciation, in recent years, nearly after 1995.
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[иы])я/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/[иы]я/u', 'iä', $text );
		// э
		// if words like эластик, элү come here by mistake
		$text = preg_replace( '/^э/u', 'e', $text );
		// тәэмин
		$text = preg_replace( '/э(?=.[аыоуәеөүи])/u', '\'', $text );
		// ризаэтдин
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/э(?=..[аыоуәеөүи])/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/э(?=..[аыоуәеөүи])/u', '\'e', $text );
		// ь
		$text = preg_replace( '/(?<=^мәс)ь(?=әлән)/u', '\'', $text );
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/ь/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		// ъ
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^qöр)ъ(?=ән)/u',
				// 'l', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=^qöр)ъ(?=ән)/u', '\'', $text );
		$text = parent::translate( $text, 'tt-latn' );
		// $this->returnCaseOfCyrillicWord( $text );
		if ( $this->test ) $text = '(' . $text . ')';
		return $text;
	} // convertTatarWordWithArabicRootFromCyrillicToLatin

	function convertSimpleTatarWordFromCyrillicToLatin( $text ) {
		if (
			preg_match( '/[аоыу]/u', $text )
			// preg_match( '/[аыу]/u', $text )
			// || preg_match( '/о(?!ва?$)/u', $text )
			// ел, як, юк,
			// е, я, ю
			|| preg_match( '/^([яю].?|е.)$/u', $text )
		) {
			// къ, гъ
			$text = preg_replace( '/к(?!ь)(?!арават)/u', 'q', $text );
			$text = preg_replace( '/г/u', 'ğ', $text );
			// // yı, yev
			// yı
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[аыоу])е/u', $text,
					// $this->currentWordTargetLetterCase );
			// // ayev
			// $text = preg_replace( '/(?<=[аыоу])е(?=ва?$)/u', 'ye', $text );
			// // i added latin e to fix v after previous replace in "ov,ev"
			// // $text = preg_replace( '/(?<=e)в(?=а?$)/u', 'v', $text );
			// yı
			$text = preg_replace( '/(?<=[аыоуъь])е/u', 'yı', $text );
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/^е/u',
					// $text, $this->currentWordTargetLetterCase );
			$text = preg_replace( '/^е/u', 'yı', $text );
			// aw
			// ау, аяу etc except гагауз
			$text = preg_replace( '/(?<=[ая])у(?<!^ğаğау)/u', 'w', $text );
			// // ov,ev
			// // latin e is added because it might be set in "ayev"
			// $text = preg_replace( '/(?<=[аеe])в(?=а?$)/u', 'v', $text );
			// moved family name ending check to outer function
			// maybe exceptional ый -> i
			$text = preg_replace( '/(?<=ğ)ый(?=нвар)/u', 'i', $text );
		}else {
			// yä
			// ия before thin syllables
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)я(?=.+[әүеи])/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия(?=.+[әүеи])/u', 'iä', $text );
			// ия at end
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)я$/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия/u', 'iä', $text );
			// я except янил
			$text = preg_replace( '/я(?!нил)/u', 'yä', $text );
			// ye
			// е is by default e, so i use "lengthen" when it becomes ye.
			// ("by default" means "in toLatin array").
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[әө])е/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[әө])е/u', 'ye', $text );
			$text = preg_replace( '/(?<=и)е/u', 'e', $text );
			// exception: енче
			$text = preg_replace( '/^енче$/u', 'ençe', $text );
			$text = preg_replace( '/^е/u', 'ye', $text );
			// yü
			// exception for ию
			// y should not be removed in these words, as written in
			// tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
			// and btw these words has gone to processWordWithRussianStemCyrlToLat
			// and are sent to here as exception
			$text = preg_replace( '/(?<=^и)ю([нл])ь?/u', 'yü$1', $text );
			// yü
			$text = preg_replace( '/(?<=[әө])ю/u', 'yü', $text );
			// ю is by default yu so i use lengthen when it becomes ü
			// "by default" ie in toLatin array
			// if ( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)ю/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=и)ю/u', 'ü', $text );
			$text = preg_replace( '/^ю(?!хиди)/u', 'yü', $text );
			// äw
			$text = preg_replace( '/(?<=[әä])ү/u', 'w', $text );
			// в in ливәшәү
			$text = preg_replace( '/(?<=ли)в(?=әшәw)/u', 'v', $text );
		}
		// ь
		// if ( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/ь/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		$text = parent::translate( $text, 'tt-latn' );
		if ( $this->test ) $text = '|' . $text . '|';
		return $text;
	} // convertSimpleTatarWordFromCyrillicToLatin

	function convertRussianWordWithTatarEndingCyrlToLat( $parts ) {
		$parts[0] = $this->processWordWithRussianStemCyrlToLat(
			$parts[0] ); // recursion because
			// there are графиктагы and графигында, etc
		$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertCompoundTatarWordCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithTurkicStemCyrlToLat( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertTatarWordWithRussianEndingCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithRussianStemCyrlToLat( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithRussianEndingCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertTatarWordWithArabicRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithRussianStemCyrlToLat( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithTatarEndingCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertTatarWordWithArabicRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertCompoundWordCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertLowercasedTatarWordCyrlToLat( $parts[0] );
		$parts[1] = $this->
			convertLowercasedTatarWordCyrlToLat( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertLowercasedTatarWordLatToCyrl( $text ) {
		// exceptional words
		// month names
		// https://tt.wikipedia.org/
		// wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_10.jpg
		// ä
		// ä -> я in month names
		$text = preg_replace( '/(?<=nt|kt)ä(?=ber)/u', 'я', $text );
		// ber -> брь in month names
		$text = preg_replace( '/(?<=ntя|ktя|oyä|eka)bere/u', 'бре', $text );
		$text = preg_replace( '/(?<=ntя|ktя|oyä|eka)ber/u', 'брь', $text );
		// aprel
		$text = preg_replace( '/(?<=aprel)(?!e)/u', 'ь', $text );
		// XIII etc
		// made -{XIII}- in test for now
		// w
		// w spelling at end of word or before consonant
		// i moved this replace before ya->я because
		// with я i do not know softness (thinness)
		$text = preg_replace( '/(?<=[aıou])w(?=$|[^aıouäeöüi])/u', 'у', $text );
		$text = preg_replace( '/(?<=[äeöü])w(?=$|[^aıouäeöüi])/u', 'ү', $text );
		// w in some words
		// шонгауэр
		$text = preg_replace( '/(?<=nga)w(?=er)/u', 'у', $text );
		// fix strangeness/shortness of the source latin orthography
		// iä -> ия
		$text = preg_replace( '/(?<=i)ä/u', 'я', $text );
		// iü -> ию
		$text = preg_replace( '/(?<=i)ü/u', 'ю', $text );
		// add specific cyrillic letters
		// ya -> я
		// ya as first letter
		$text = preg_replace( '/^ya/u', 'я', $text );
		// ya as first letter of second part of compound word
		$text = preg_replace( '/(?<=^kön)ya(?=q)/u', 'ья', $text );
		$text = preg_replace( '/(?<=^taş)ya(?=z)/u', 'ъя', $text );
		// ya after vowels
		$text = preg_replace( '/(?<=[aıouie])ya/u', 'я', $text );
		// ya after consonants in russian words as softening of consonant + mini y + u
		// молекуляр, варяг, октябрьское, ассимиляция, рязань
		$text = preg_replace( '/(?<=simil)ya/u', 'я', $text );
		$text = preg_replace( '/(?<=var|kul|okt)ya/u', 'я', $text );
		$text = preg_replace( '/(?<=^r)ya/u', 'я', $text );
		// ya after apostrophe in russian words
		$text = preg_replace( '/(?<=yum\')ya/u', 'я', $text );
		// yo
		// ya as first letter in russian words
		$text = preg_replace( '/^yo(?=lka)/u', 'ё', $text );
		// yo after consonants in russian words as softening of consonant + mini y + o
		$text = preg_replace( '/(?<=plan)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=şof)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=samol)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=dirij)yo/u', 'ё', $text );
		// yı -> е
		// yı as first letter
		$text = preg_replace( '/^yı/u', 'е', $text );
		// yı as first letter of second part of compound word
		$text = preg_replace( '/(?<=^küp)yı(?=l)/u', 'ье', $text );
		$text = preg_replace( '/(?<=^un)yı(?=l)/u', 'ъе', $text );
		$text = preg_replace( '/(?<=^meñ)yı(?=l)/u', 'ъе', $text );
		$text = preg_replace( '/(?<=^utız)yı(?=l)/u', 'ъе', $text );
		// yı after vowels
		$text = preg_replace( '/(?<=[aıoue])yı/u', 'е', $text );
		// yu -> ю
		// yu as first letter
		$text = preg_replace( '/^yu/u', 'ю', $text );
		// yu after vowels
		$text = preg_replace( '/(?<=[aıou])yu/u', 'ю', $text );
		// yu after consonants in russian words as softening of consonant + mini y + u
		$text = preg_replace( '/(?<=^[bl])yu/u', 'ю', $text );
		$text = preg_replace( '/(?<=v[ao]l)yu/u', 'ю', $text );
		$text = preg_replace( '/(?<=^[sa]l)yu/u', 'ю', $text );
		// yu after consonants in russian words
		$text = preg_replace( '/(?<=komp)yu/u', 'ью', $text );
		// yä -> я
		// yä as first letter
		$text = preg_replace( '/^yä/u', 'я', $text );
		// show softness if it is not shown
		// яшьләре - though softness is shown with letter ә,
		// it is written with ь.
		// (some words are not written with ь when softness is
		// shown with thin vowel: шөгыль - шөгыле,
		// even in яшәүче , which is probably made from same root)
		// яшь, ямь
		// except яши, яшел, яшәү
		$text = preg_replace( '/(?<=^я[şm])(?![äie])/u', 'ь', $text );
		// yä after vowels
		// oyä for noyäber
		$text = preg_replace( '/(?<=[äöüo])yä/u', 'я', $text );
		// ye -> е
		// ye as first letter
		$text = preg_replace( '/^ye/u', 'е', $text );
		// ye as first letter of second part of compound word
		// акъегет
		$text = preg_replace( '/(?<=^aq)ye/u', 'ъе', $text );
		// унъеллык
		$text = preg_replace( '/(?<=^un)ye/u', 'ъе', $text );
		// ye after vowels in russian words
		// $text = preg_replace( '/(?<=pro)ye/u', 'е', $text );
		// $text = preg_replace( '/(?<=po)ye/u', 'е', $text );
		// ye after vowels
		// mendeleyevsk
		$text = preg_replace( '/(?<=[aoueäö])ye/u', 'е', $text );
		// ye after consonants in russian words
		// премьер, ателье
		$text = preg_replace( '/(?<=(prem|atel))ye/u', 'ье', $text );
		// вьет, сьерра
		$text = preg_replace( '/(?<=v|s)ye/u', 'ье', $text );
		// карьер
		$text = preg_replace( '/(?<=kar)ye/u', 'ье', $text );
		// объектив
		$text = preg_replace( '/(?<=ob)ye/u', 'ъе', $text );
		// yü -> ю
		// yü as first letter
		$text = preg_replace( '/^yü/u', 'ю', $text );
		// show softness if it is not shown
		$text = preg_replace( '/(?<=юn$)/u', 'ь', $text );
		// yü after vowels
		// exception to next replace
		// июнь, июль
		$text = preg_replace( '/(?<=^i)yü([nl])(?!e)/u', 'ю$1ь', $text );
		// өю, җәю, ию, июнь
		$text = preg_replace( '/(?<=[äöi])yü/u', 'ю', $text );
		// e
		// e spelling at beginnig of word
		// exception: e in ençe
		$text = preg_replace( '/^ençe$/u', 'енче', $text );
		// e spelling at beginnig of word
		$text = preg_replace( '/^e/u', 'э', $text );
		// e after vowels in russian words
		$text = preg_replace( '/(?<=[aou])e/u', 'э', $text );
		// e as "ae"
		$text = preg_replace( '/(?<=^br)e(?=yk)/u', 'э', $text );
		$text = preg_replace( '/(?<=^kr)e(?=k)/u', 'э', $text );
		$text = preg_replace( '/(?<=^m)e(?=r)/u', 'э', $text );
		// i cannot add here men->мэн because there is tatar word мен
		// пэр except перманганат, перчатка, пермь
		$text = preg_replace( '/(?<=^p)e(?=r)(?!r(m|çat))/u', 'э', $text );
		// шонгауэр
		$text = preg_replace( '/(?<=gaу)e(?=r)/u', 'э', $text );
		// e after apostrophe
		$text = preg_replace( '/(?<=riza)\'e/u', 'э', $text );
		$text = preg_replace( '/(?<=tä)\'e/u', 'э', $text );
		// i
		// maybe an exceptional word
		$text = preg_replace( '/(?<=ğ)i(?=nwar)/u', 'ый', $text );
		// ts
		// ts as ц, in russian words
		$text = preg_replace( '/ts(?=iя)/u', 'ц', $text );
		$text = preg_replace( '/ts(?=io)/u', 'ц', $text );
		$text = preg_replace( '/(?<=(fran|muni|kvar))ts/u', 'ц', $text );
		$text = preg_replace( '/ts(?=etner)/u', 'ц', $text );
		$text = preg_replace( '/(?<=pro)ts/u', 'ц', $text );
		$text = preg_replace( '/ts(?=ito)/u', 'ц', $text );
		$text = preg_replace( '/ts(?=entr)/u', 'ц', $text );
		$text = preg_replace( '/(?<=a)ts(?=etil)/u', 'ц', $text );
		$text = preg_replace( '/(?<=va)ts(?=lav)/u', 'ц', $text );
		// şç
		// şç as щ, in russian words
		$text = preg_replace( '/şç(?=otka)/u', 'щ', $text );
		$text = preg_replace( '/(?<=li)şç(?=e)/u', 'щ', $text );
		// o
		// o -> ё after soft russian consonants
		$text = preg_replace( '/(?<=^sç)o/u', 'ё', $text );
		$text = preg_replace( '/(?<=^щ)o/u', 'ё', $text );
		// arabic
		//        words
		// arabic words // i made empty comments but phpcs check blamed on that
		// ya after consonants, except after apostrophe
		// exception to the next replace
		// гыйсъян
		$text = preg_replace( '/(?<=ğıys)ya/u', 'ъя', $text );
		// dönya
		$text = preg_replace( '/(?<=[^aıouäeöüi\'])ya/u', 'ья', $text );
		// ä
		// ä -> а after ğ, q, in arabic words
		// гадәләт, кадәр
		$text = preg_replace( '/(?<=[ğq])ä/u', 'а', $text );
		// add ь after that
		$text = preg_replace( '/(?<=[ğq]а[^яеy])(?![äeöüi])/u', 'ь', $text );
		$text = preg_replace( '/(?<=[ğq]а[я].)(?![äeöüi])/u', 'ь', $text );
		// ä -> а by alif -> a rule in arabic words
		$text = preg_replace( '/(?<=säl)ä(?=m)/u', 'а', $text );
		$text = preg_replace( '/(?<=wäk)ä(?=l)/u', 'а', $text );
		$text = preg_replace( '/(?<=bäh)ä/u', 'а', $text );
		$text = preg_replace( '/(?<=bäl)ä(?!ğаt)(?!r)/u', 'а', $text );
		$text = preg_replace( '/(?<=ğib)ä(?=[rd]ät)/u', 'а', $text );
		$text = preg_replace( '/(?<=häl)ä(?=[kg])/u', 'а', $text );
		$text = preg_replace( '/(?<=äxl)ä(?=[qğ])/u', 'а', $text );
		$text = preg_replace( '/(?<=x)ä(?=nä)/u', 'а', $text );
		$text = preg_replace( '/(?<=n)ä(?=mä)/u', 'а', $text );
		$text = preg_replace( '/(?<=yädk)ä(?=r)/u', 'а', $text );
		$text = preg_replace( '/(?<=x)ä(?=lät)/u', 'а', $text );
		// show softness if it is not shown
		$text = preg_replace( '/(?<=wil)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=wil)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=cin)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=cin)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=nih)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=nih)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=яdk)ä(r)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=яdk)ä(r)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=riw)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=riw)ä(яt)(?![ei])/u', 'а$1ь', $text );
		// '
		// ' is converted as ь by default
		// ' as hamza
		$text = preg_replace( '/(?<=(tä|ma|mö))\'/u', 'э', $text );
		$text = preg_replace( '/(?<=qör)\'/u', 'ъ', $text );
		// $text = preg_replace( '/(?<=^d)\'$/u', '\'', $text ); // this does
		// not work because ' is later converted to ь
		// ö
		// ö -> o after ğ,q in arabic words, when softness is shown
		// further with soft wovel
		// гомер, гомәр, гореф, коръән
		$text = preg_replace( '/(?<=[ğq])ö/u', 'о', $text );
		// ü
		// ü -> у in arabic words
		$text = preg_replace( '/(?<=q)ü(?=ä)/u', 'у', $text );
		$text = preg_replace( '/(?<=ğ)üle/u', 'уле', $text );
		$text = preg_replace( '/(?<=ğ)ül/u', 'уль', $text );
		// e
		// e -> ы.ь after ğ in arabic words
		// шөгыль
		$text = preg_replace( '/(?<=ğ)e(.)(?!e)/u', 'ы$1ь', $text );
		// when softness is shown further by e
		// шөгыле, җәмгысе
		$text = preg_replace( '/(?<=ğ)e(?=.e)/u', 'ы', $text );
		// i
		// i -> ы..ь after ğ,q in arabic words when softness is not shown
		// further by other ways
		// тәрәккыять, җәмгыять
		$text = preg_replace( '/(?<=[qğ])i(яt)(?![ei])/u', 'ы$1ь', $text );
		// when softness is shown further by soft wovel
		// тәрәккыяте, җәмгыяте, тәрәккыяти
		$text = preg_replace( '/(?<=[qğ])i(?=яt[ei])/u', 'ы', $text );
		// тәрәккыяви
		$text = preg_replace( '/(?<=q)i(?=яwi)/u', 'ы', $text );
		// кыяфәт
		$text = preg_replace( '/(?<=q)i(?=яf)/u', 'ы', $text );
		// i -> ы..ь in arabic words when softness is not shown
		// васыять, васыятькә
		$text = preg_replace( '/(?<=was)i(яt)(?![ei])/u', 'ы$1ь', $text );
		// васыяте, васыяти, васыяви
		$text = preg_replace( '/(?<=was)i(?=я[tw][ei])/u', 'ы', $text );
		// exception to the next replace
		// нәгыйм
		$text = preg_replace( '/(?<=näğ)i(?=m)/u', 'ый', $text );
		$text = preg_replace( '/(?<=wäğ)i(?=z)/u', 'ый', $text );
		// i -> ый after ğ,q in arabic words when softness is not shown
		// further with soft wovel
		// мөстәкыйль, гыйльфан
		$text = preg_replace(
			// '/(?<=[qğ])i([^aıouäeöüiяе])(?![^aıouäeöüiяе]?[eä])/u',
			'/(?<=[qğ])i([^aıouäeöüiяе])(?=$|[^aıouäeöüiяе]?[aıou])/u',
			'ый$1ь', $text );
		// i -> ый after ğ,q in arabic words when softness is shown
		// further with soft wovel
		// гыйлем
		// also when softness is not shown futher
		// тәрәккый, фәгыйләт
		// $text = preg_replace( '/(?<=[ğq])i(?=lem)/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])i(?![eя])/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])i(?=[eя])/u', 'ы', $text );
		// i -> ый before q in arabic words
		$text = preg_replace( '/(?<=räf)i(?=q)/u', 'ый', $text );
		// q
		// q -> къ after soft vowels
		// before consonants and at end of word in arabic words
		// $text = preg_replace( '/q(?=[^aıouäeöüi])/u', 'къ', $text );
			// this makes also ...лыкълар...
		$text = preg_replace( '/(?<=i)q(?=tis)/u', 'къ', $text );
		$text = preg_replace( '/(?<=täşwi)q(?=[^aıouäeöüi]|$)/u', 'къ', $text );
		$text = preg_replace( '/(?<=rö)q(?=ğа)/u', 'къ', $text );
		$text = preg_replace( '/(?<=li)q(?=[^aıouäeöüi]|$)/u', 'къ', $text );
		$text = preg_replace( '/(?<=tä)q(?=dim)/u', 'къ', $text );
		// // тарикъ
		// remove this, also from test, it is not used
		// though there are words like that: балигъ, бәлигъ
		// $text = preg_replace( '/(?<=tari)q(?!ı)/u', 'къ', $text );
		// ğ
		// ğ -> гъ before consonants or at end of word
		// after soft syllables or after я in arabic words
		// before consonants after soft syllable
		// $text = preg_replace( '/(?<=i)ğ(?=tib)/u', 'гъ', $text );
		// $text = preg_replace( '/(?<=mä)ğ(?=lüm)/u', 'гъ', $text );
		// $text = preg_replace( '/(?<=tä)ğ(?=li)/u', 'гъ', $text );
		// $text = preg_replace( '/(?<=i)ğ(?=lan)/u', 'гъ', $text );
		// $text = preg_replace( '/(?<=mä)ğ(?=nä)/u', 'гъ', $text );
		// $text = preg_replace( '/(?<=mä)ğ(?=dän)/u', 'гъ', $text );
		// i have replaced the above 6 replaces with this one
		$text = preg_replace( '/(?<=[äi])ğ(?![aıouäeöüiаы])/u', 'гъ', $text );
		// before consonants after я
		$text = preg_replace( '/(?<=я)ğ(?=ni)/u', 'гъ', $text );
		$text = preg_replace( '/(?<=я)ğ(?=f[aä]r)/u', 'гъ', $text );
		// at end of word after soft syllable
		$text = preg_replace( '/(?<=bäli)ğ(?!e)/u', 'гъ', $text );
		// k
		// k -> кь before consonants and at end of word in arabic words
		$text = preg_replace( '/(?<=pa)k(?=[^aıouäeöüi]|$)/u', 'кь', $text );
		$text = parent::translate( $text, 'tt-cyrl' );
		// apostrophe, this should be here, after parent::translate
		$text = preg_replace( '/(?<=^д)ь$/u', '\'', $text );
		// $text = '(' . $text . ')';
		return $text;
	} // convertLowercasedTatarWordLatToCyrl

	function latinToUpper( $text ) {
		$text = str_replace( 'i', 'İ', $text );
		$text = mb_strtoupper( $text );
		// $text = str_replace( array( 'I', 'ı' ), array( 'İ', 'I' ), $text ); // i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}

	function latinToLower( $text ) {
		$text = str_replace( 'I', 'ı', $text );
		$text = mb_strtolower( $text );
		// $text = str_replace( array( 'i', 'İ' ), array( 'ı', 'i' ), $text );
		return $text;
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			// separating -{XINHUA}-s is not needed,
			// they are already replaced with 00 bytes.
			$text = preg_replace( '/YUY/u', 'ЮУЙ', $text );
			// $text = preg_replace( "/d'İvuar/u", 'д@Ивуар', $text ); // moved
			// this into convertLowercasedTatarWordLatToCyrl after parent::translate 
			$w = 'a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'';
			$words = preg_split( "/([$w]+)/u", $text,
				null, PREG_SPLIT_DELIM_CAPTURE );
			$wordsCount = count( $words );
			for ( $i = 1; $i < $wordsCount; $i += 2 ) {
				$words[$i] = preg_split( '/(?=[A-ZÄÖÜİŞÇÑĞ])/u', $words[$i] );
				$wordsICount = count( $words[$i] );
				if ( $wordsICount == 1 || $words[$i][0] != '' ) {
					// if uppercase letter is not found,
					// should be 1 element in array
					// if word starts with upper case letter,
					// there is first empty string in the preg_split result
					// empty string need not to be converted
					// if no empty string, it should start with lower case
					// lower case string need not to be lowercased
					$words[$i][0] =
						$this->convertLowercasedTatarWordLatToCyrl( $words[$i][0] );
				}
				for ( $j = 1; $j < $wordsICount; $j++ ) {
					// if $wordsICount == 1 this inside part does not run
					$words[$i][$j] =
						$this->convCapitalisedWordLatCyrl( $words[$i][$j] );
				}
				$words[$i] = implode( $words[$i] );
			}// i
			$text = implode( $words );
			// $text = preg_replace( '/д@Ивуар/u', "д'Ивуар", $text ); // moved
			// this into convertLowercasedTatarWordLatToCyrl after parent::translate 
		} elseif ( $toVariant == 'tt-latn' ) {
			// separating -{XINHUA}-s is not needed, they
			// are already replaced with 00 bytes.
			// removing "­", that may be in copy-pasted cites
			// https://en.wikipedia.org/wiki/Soft_hyphen
			$text = preg_replace( "/­/u", '', $text );
			// q in shortenings
			$text = preg_replace( "/б\.э\.к\./u", 'b.e.q.', $text );
			$text = preg_replace( "/б\. э\. к\./u", 'b. e. q.', $text );
			$text = preg_replace( "/ЮУЙ/u", 'YUY', $text );
			$text = preg_replace( "/Г. Камал/u", 'Ğ. Kamal', $text );
			$text = preg_replace( "/Кот-д'/u", "Kot-d'", $text );
			// splitting into words
			$combiningAcute = "́";
			$w = 'А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ' . $combiningAcute;
			$words = preg_split( "/([$w]+)/u", $text,
				null, PREG_SPLIT_DELIM_CAPTURE );
			$wordsCount = count( $words );
			for ( $i = 1; $i < $wordsCount; $i += 2 ) {
				if ( 0 === preg_match( '/[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ]/u', $words[$i] ) ) {
					continue; // no cyrillic
				}
				// need to handle combining acute. maybe to replace it with ′
				// removing it for now.
				$words[$i] = preg_replace( "/$combiningAcute/u", '', $words[$i] );
				if ( preg_match( '/^КамАЗ[а-яёәөүҗңһ]*$/u', $words[$i] ) ) {
					// fix shortening of word kama
					// it is russian word or like russian
					$words[$i] = 'KamA'
						. $this->convCapitalisedWordCyrlLat(
							mb_substr( $words[$i], 4 )
							// last letter of abbreviation should
							// go together with suffixes
							);
				}elseif ( preg_match( '/^АКШ[а-яёәөүҗңһ]*$/u', $words[$i] ) ) {
					// fix single к which is by default converted as k
					// but in some abbreviations it should be converted as q
					$words[$i] = 'AQ'
						. $this->convCapitalisedWordCyrlLat(
							mb_substr( $words[$i], 2 )
							);
				}else {
					$words[$i] = preg_split( '/(?=[А-ЯЁӘӨҮҖҢҺ])/u', $words[$i] );
					$wordsICount = count( $words[$i] );
					if ( $wordsICount == 1 || $words[$i][0] != '' ) {
						// if uppercase letter is not found,
						// should be 1 element in array
						// if word starts with upper case letter,
						// there is first empty string in the preg_split result
						// empty string need not to be converted
						// if no empty string, it should start with lower case
						// lower case string need not to be lowercased
						$words[$i][0] =
							$this->convertLowercasedTatarWordCyrlToLat( $words[$i][0] );
					}
					for ( $j = 1; $j < $wordsICount; $j++ ) {
						$words[$i][$j] =
							$this->convCapitalisedWordCyrlLat( $words[$i][$j] );
					}
					$words[$i] = implode( $words[$i] );
				}
			}// i
			$text = implode( $words );
		} // elseif
		// code that can be used for debug
		// $text=str_replace( '\'', '0', $text );
		// $text=htmlspecialchars( $text );
		// $text='***' . $text . "***\n";
		// file_put_contents( 'x.txt', $text );
		// $logfileqdb=fopen( 'x.txt', 'a' );
		// fwrite( $logfileqdb, $text );
		return $text;
	} // translate

	function convCapitalisedWordCyrlLat( $text ) {
		$text = mb_strtolower( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		$text = $this->convertLowercasedTatarWordCyrlToLat( $text );
		$text = $this->latinToUpper( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		return $text;
	}

	function convCapitalisedWordLatCyrl( $text ) {
		$text = $this->latinToLower( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		$text = $this->convertLowercasedTatarWordLatToCyrl( $text );
		$text = mb_strtoupper( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		return $text;
	}

}

/**
 * Tatar
 *
 * @ingroup Language
 */
class LanguageTt extends Language {
	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'tt', 'tt-latn', 'tt-cyrl' );
		$variantfallbacks = array(
			'tt' => 'tt-latn',
			'tt-cyrl' => 'tt',
			'tt-latn' => 'tt',
		);

		$this->mConverter = new TtConverter( $this, 'tt', $variants, $variantfallbacks );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}
}
