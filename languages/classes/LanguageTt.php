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

	public $test = 0;

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
		// this letter was used in previous, 1999's, official latin alphabet
		// 'ə' => 'ә', // 'Ə' => 'Ә',
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
		'\'' => 'ь', // '’' => 'ь',
		// i have changed this, i think ь is more frequent
		// so, some regexes should be changed
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tt-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'tt-latn' => new ReplacementArray( $this->toLatin ),
			'tt' => new ReplacementArray()
		);
	}

	function convertTatarWordFromCyrillicToLatin( $text ) {
		// compounds
		if(
			// preg_match( '/илбашы|аскорма|аккош|коточкыч/ui', $text )
			// need to add аскорма, even if its both parts are hard/thick, because else
				// it goes into russian part/branch because of о at second syllable
				// i comment this out and add to other checks to split them
			// no need to add бер(рәт|ничә|дәнбер),
			// because their both parts are soft/mild/thin
			// preg_match( '/берникадәр/ui', $text )
			preg_match( '/^[^әэеөүи]+баш/ui', $text )
		){
			// $text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
			// $text = '{'. $text. '}';
			return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		}elseif( preg_match( '/^([кт]өнья[кг]|биектау|күпьеллы[кг]'
			. '|викиҗыенты[кг])/ui', $text )
		){
			$parts = preg_split( '/(?<=^....)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/^бөекбрит/ui', $text )
		){
			$parts = preg_split( '/(?<=^....)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// return $this->convertCompoundWord( $parts ); // it
				// works but let i make this "manually", to avoid going
				// through this check again (in recursion)
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->processWordWithRussianStem( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif( preg_match( '/^көн(чыгыш|батыш)|гөлбакча|ч[иы]ная[кг]|башкорт|коточкыч'
			.'|бераз|кайбер|һәрчак/ui', $text ) ){
			// $parts = preg_split( '/(?<=көн)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts = preg_split( '/(?<=^...)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/.+су(?<!басу)(?<!^ысу)/ui', $text ) ){
			$parts = preg_split( '/(?=су)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/.+сыман/ui', $text ) ){
			$parts = preg_split( '/(?=сыман)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/(?<!^кукм)(?<!^[аә]нк)(?<!^[бкч])ара$/ui', $text ) ){
			// except кукмара, анкара, бара, чара, кара
			$parts = preg_split( '/(?=ара)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/илбашы|аскорма|аккош|суүсем|әрдоган/ui', $text ) ){
			$parts = preg_split( '/(?<=^..)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/никадәр/ui', $text ) ){
			$parts = preg_split( '/(?<=^..)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif( preg_match( '/берникадәр/ui', $text ) ){
			$parts = preg_split( '/(?<=^.....)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif( preg_match( '/^(габдел.+|солтангали)/ui', $text ) ){
			$parts = preg_split( '/(?<=^......)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] = $this->convertTatarWordWithArabicRootFromCyrillicToLatin(
				$parts[0] );
			$parts[1] = $this->processWordWithArabicStem( $parts[1] );
			$text = implode( $parts );
			return $text;
		}elseif( preg_match( '/оглу/ui', $text ) ){
			$parts = preg_split( '/(?=оглу)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/.+стан(?!тин)/ui', $text ) ){
			// need to add стан even after hard syllables because else if it is after
			// consonant there are 3 consecutive consonants and it goes to russian branch
			// except константин
			$parts = preg_split( '/(?=стан)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] = $this->
				convertTatarWordFromCyrillicToLatin( $parts[0] ); // recursion
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			// return $parts[0] . parent::translate( $parts[1], 'tt-latn' );
			return $parts[0] . $parts[1];
		}elseif( preg_match( '/.+[ое]ва?([гк]а(ча)?|[дт]а(н|гы|й)?|ның?|ча|лар)/ui',
			$text )
		){
			// split words with ov/ev suffix after the suffix: вәлиев|ка
			$parts = preg_split( '/(?<=[ое]в)/ui', $text,
				null, PREG_SPLIT_NO_EMPTY); // i do
				// not include here а after ов/ев because
				// look behind regex should be fixed length
			$parts[0] = $this->
				convertTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			// return $parts[0] . parent::translate( $parts[1], 'tt-latn' );
			return $parts[0] . $parts[1];
		}elseif( preg_match( '/ташъязма|акъегет/ui', $text ) ){
			$parts = preg_split( '/(?<=ъ)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertCompoundTatarWord( $parts );
		}elseif( preg_match( '/известьташ/ui', $text ) ){
			// this works even without splitting,
			// but а in таш is tatar а, so i better split this
			$parts = preg_split( '/(?<=ь)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			return $this->convertRussianWordWithTatarEnding( $parts ); // таш is not
				// ending but this works
		}elseif(
			// arabic words which look like russian words and go into russian branch
			// before checking for signs of arabic words, so i check them here,
			// before reaching checking of signs of russian words
			// and even if i do not allow гаяре, гаебе to
			// go to russian branch, they are not catched by arabic check and
			// go into tatar branch
			preg_match(
				// әэ for тәэссорат, abnd i remove it from main check of arabic
				// аэтдин for ризаэтдин (then аэ all are catched by check of russian)
				// васыят is for васыяте, and also catches васыять.
				'/әэ|^г(а(яр|еб|йр|лим)е|о(мер|реф|шер))'
				. '|ка(бер|леб)|маэмай|вилаят|аэтдин'
				. '|васыят/ui'
				, $text )
		){
			// $text = $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
			return $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
		}elseif(
			// signs of russian words
			// preg_match( '/^[бвгджзклмнпрстфхцчшщ]{2,2}/ui', $text )
			preg_match( '/[ёцщ]/ui', $text )
			|| preg_match( '/[бвгджзклмнпрстфхчш][яюёэ]/ui', $text )
			// автор etc.
			// there is rule to not write о in syllables except first:
			// tatar word соло is written солы
			// except family names ending with ов(а)
			|| preg_match( '/.{2,}о(?!ва?$)/ui', $text )
			//
			// катер etc; should not catch сараенда
			// гаяре, гаебе would be catched here,
			// but so i catch such arabic words before
			// reaching to these checks. i have tried
			// to add here .+ at end of regex but then
			// they go to tatar branch, if i do not add them in arabic check.
			// also e at end of word is also possible in russian words: каре, пеле, желе
			// so i better check them before here
			// катер, кушкет
			|| preg_match( '/^[^әөүҗңһ]*[аоу][^әөүҗңһ]*[бвгджзклмнпрстфхцчшщ]+е/ui',
				$text )
			// 3 consecutive consonants except артта, астта, антта
			|| preg_match( '/[бвгджзклмнпрстфхцчшщ]{3,}(?<![рнс]тт)/ui', $text )
			// || preg_match( '/в[бвгджзклмнпрстфхцчшщ]|[бвгджзклмнпрстфхцчшщ]в/ui',
				// $text )
			|| preg_match( '/в[вгдзклмнртхцчш]/ui', $text )
			|| preg_match( '/ия.+[аыоу]/ui', $text )
			// гектар etc; but should not catch оешмасы, so /^.е.+а/ is not enough
			|| preg_match( '/^[бвгджзклмнпрстфхцчшщ]е.+а/ui', $text )
			// натураль etc except шөгыль, мөстәкыйль, гыйльфан
			|| preg_match( '/(?<![гк]ы)(?<![гк]ый)ль/ui', $text )
			// синерь etc. should not catch шигырь, шагыйрь, бәгырь
			// and words like карьер, барьер
			|| preg_match( '/ерь|рье/ui', $text )
			// тангаж etc
			|| preg_match( '/.{3,}ж|^в$/ui', $text )
			// words like винт, грамм, штутгард, бриг,
			// шпиг, во, волга, вьет, etc
			// ^ви also catches вилаять, but seems it is only one such arabic words
			// so i catch it before russian words.
			// красивый, трамвай
			// версия, веб
			// шк for шкетан
			|| preg_match( '/^(в[иоье]|ш[тпк]|[пгбкт]р|сс)/ui', $text )
			// акт, пакт, тракт, etc
			// i do not add here да, га, лар suffixes, because with them it is catched
			// because of 3 consecutive consonants
			// поезд
			|| preg_match( '/(акт|зд)($|ы($|м|ң|[бг]ыз))/ui', $text )
			// физика etc except шикаять, драматург etc except ургы-, тургай
			|| preg_match( '/.+(?<!ш)ика|атург/ui', $text )
			// товар, овал
			|| preg_match( '/^.?ова/ui', $text )
			// вариант, авиа, шоу, аэро, поэма, пуэр, ноябрь, ноябре
			|| preg_match( '/[аоу]э|их?а|оу|бр[ье]/ui', $text )
			// other russian words
			// кукмара is tatar word but it work ok as russian word (its у is slightly
			// different than russian у, but it is not shown in the latin)
			|| preg_match( '/^(к(а(ндидат|бина|маз?)|у(рс|кмара)'
				. '|о(мисс|нстан)))/ui', $text )
			|| preg_match( '/актив|^нигерия|импер|^ефрат|тугрик|сигнал'
				. '|^ив/ui', $text )
			|| preg_match(
				'/^г((аз|ол?)($|да(н|гы)?|га(ча)?|сыз|чы|ның?)|(о(рилла|а|би)))/ui',
				$text )
			// i cannot use here just ^ав because there are
			// tatar words авыр, каравыл, etc
			// i removed авиа and added иа
			// i do not add ав$ for состав, because составы then still need to be catched
			|| preg_match( '/ав(а(тар|рия))/ui', $text )
			|| preg_match( '/шпага|^дог($|ка(ча)?|та(н|гы)?|ның?)|юмья|кизнер/ui',
				$text ) // дог
				// is rare word but it is in the test
			// these would go into *arabic* branch, that also work ok for now, but i
			// send багира, тагил here because problems may appear
			// with г, and send химия here,
			// because problems may appear with thick и.
			// бензинсыз was detected as arabic, but
			// бензин goes to tatar branch, so add it there.
			|| preg_match( '/багира|тагил|^хими|маугли|юбиле|лига|система|республика'
				.'|магнит|органик|извест|нефть|калий/ui', $text )
			// these would go into *tatar* branch
			// i was going to add karta into russian but there is also tatar qarta
			// and other word for karta - xarita in literary language
			// karta and qarta cannot be distinguished without semantics or context
			// added уганда but there is also tatar word уганда, they cannot be
			// distinguished without semantic check. also канар, but i do not add it.
			|| preg_match( '/музыка|в(епс|ышка|алда)|пауза|(за|у)каз|состав'
				. '|канал|энерг|бензин|юлия|фея|яков|порту|гана|гвин'
				. '|бисау|лив|мадаг|уганда|елена/ui', $text )
		){
			return $this->processWordWithRussianStem( $text );
		}elseif(
			// signs of arabic words
			// и after 2 letters except юри, эшли, // боулинг(?!нг)
			// i comment this out because there much words like редакцияли
			// i have added и to next rule, in [әүи].
			// preg_match( '/.{2,}и(?<!^юри$)(?<!^эшли$)/ui', $text )
			// i cannot add here е after а becausee of tatar words
			// like сагаеп
			// тәкъдим, куәт,
			preg_match( '/.*[аыоуъ].*[әүи]/ui', $text )
			|| preg_match( '/.*[әөи].*[аыуъ]/ui', $text )
			// тәэмин, тәэсир, i do not add аэ for маэмай here, i can catch words
			// like аэробус with аэро, but i better catch them with аэ, and маэмай
			// is probably only one word like that, and catch маэмай
			// before russian words
			// || preg_match( '/әэ/ui', $text ) // i moved this to first check of arabic
			// || preg_match( '/ьә/ui', $text ) // replaced by the next replace
			// гаять etc except яшь юнь etc
			|| preg_match( '/(?<!^[яю].)ь(?!е)/ui', $text )
			// әдәбият etc
			|| preg_match( '/ият/ui', $text )
			// i cannot catch words гаяре, гаебе with signs because there are
			// such words in tatar: бая, сагаеп
			// also they tend to enter russian branch
			// so i catch them before russian words check.
			// other arabic words:
			|| preg_match( '/куф[аи]|^г(ае[пт]|ыйлем|омум)|^рия/ui',
				$text )
			// робагый, корбан, әдәби, мәдәни have gone to tatar branch
			// but i do not need to fix them
		){
			return $this->processWordWithArabicStem( $text );
		}else{
			// process words with turkic stem
			// word is not recognised as russian nor arabic,
			// so it is basic/plain/simple/turkic tatar word
			// family name with ov(a)
			$parts = preg_split( '/(?=ова?$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			if(count($parts)==2){
				// $text = $this->convertTatarWordWithRussianEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertTatarWordWithRussianEnding( $parts );
			}
			// family name with ev(a)
			if( preg_match( '/.+ева?$/ui', $text ) ){
				// if( $this->currentWordCapitalisationType == '' )
					// $this->lengthenInArray( '/(?<=[аоыуәе])е(?=ва?$)/ui',
						// $text, $this->currentWordTargetLetterCase );
				$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?$)/ui', 'йе', $text );
				$parts = preg_split( '/(?=ева?$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
				// $text = $this->convertTatarWordWithRussianEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertTatarWordWithRussianEnding( $parts );
			}
			// russian suffixes are not found
			// $text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
			return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		}
		// return parent::translate( $text, 'tt-latn' );
	}

	function processWordWithRussianStem( $text ){
		// process words with russian stem
		// search for tatar endings
		// ...лык/лек except электр, молекула, телеграмма
		$parts = preg_split( '/(?=л[ые][кг])(?!ле(к(тр|ул)|грам))/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...ия
		$parts = preg_split( '/(?=ия)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
		// ^ it is easier to pass the ia suffix into tatar part
		// this makes aeratsiä, i would like to write aeratsiya, but
		// aeratsiä is also ok
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...га/гә/ка/кә/кача/кәчә/гача/гәчә
		$parts = preg_split(
			'/((?<!и)(?<!^ёл)(?<!^(шпа|щёт|вол|выш))(?<!ссыл|музы|трич)|(?<=ски))'
				// убли for республика, публика
				// тани for ботаника
				// исти for характеристика
				// try (?<!и) for them
				// was (?<!^(ёл|ли))(?<!(физ|мат|тан|убл|ист)и|ссыл|музы)
				// but i have problem with минскига - fixed
				// трич - электричка
			.'(?=[гк][аә](ч[аә])?$)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// гы, кы, ге, ке, but not го|ген, ве|гетатив, врун|гель,
		// not ш|кетан, драматур|гы, о|кеан
		$parts = preg_split( '/(?<!аматур)(?<!о)(?=[гк][ые]($|[^нт]))(?!гель)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...ны, не
		$parts = preg_split( '/(?=н[ые]$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...сы/сын/etc
		$parts = preg_split( '/(?<=[ао])(?=(сы$|сын))/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...е
		// юбилее, валдае
		if( preg_match( '/[еа](?=(е$|ен))/ui', $text ) ){
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/е(?=(е$|ен))/ui',
					// $text, $this->currentWordTargetLetterCase );
			// $text = preg_replace( '/е(?=(е$|ен))/ui', 'ей', $text );
			// $parts = preg_split( '/(?<=ей)(?=(е$|ен))/ui',
				// $text, null, PREG_SPLIT_NO_EMPTY);
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/[еа](?=(е$|ен))/ui',
					// $text, $this->currentWordTargetLetterCase );
			$parts = preg_split( '/(?<=[еа])(?=(е$|ен))/ui',
				$text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] .= 'й';
			$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
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
		$parts = preg_split( '/(?<=[бвгджзклмнпрстфхцчшщ])(?=(ы$|ын))/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...да/дан/дагы/дә/та/тә/etc
		// except шке|тан, бри|тания
		// $parts = preg_split( '/(?=[тд][аә](н|[гк][ые])?$)/ui',
		$parts = preg_split( '/(?<!^(шке|бри))(?=[тд][аә]н?$)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...лар/etc except семинар, семинария
		$parts = preg_split( '/(?<!семи)(?=[лн][аә]р)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...чык/etc
		// каналчык
		$parts = preg_split( '/(?=ч[ые]к)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// ...ла/etc except плаксиха, власть, эндоплазма, молекула
		// активлаштырылган
		$parts = preg_split( '/(?<!^.)(?<!леку)(?=л[аә])(?!лазма)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			return $this->convertRussianWordWithTatarEnding( $parts );
		}
		// tatar suffixes are not found
		// $text = $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
		return $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
	} // processWordWithRussianStem

	function processWordWithArabicStem( $text ){
		// process words with arabic stem
		// family name with ov(a)
		// нәгыймов
		$parts = preg_split( '/(?=ова?$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertArabicWordWithRussianEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertArabicWordWithRussianEnding( $parts );
		}
		// family name with ev(a)
		// рәмиев, вәлиев, ...галиев, ...хәев
		if( preg_match( '/.+ева?/ui', $text ) ){
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[аоыуәе])е(?=ва?)/ui',
					// $text, $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?)/ui', 'йе', $text );
			$parts = preg_split( '/(?=ева?)/ui', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			// $text = $this->convertArabicWordWithRussianEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertArabicWordWithRussianEnding( $parts );
		}
		// ...ла,лау,лама,лаучы,...
		// тасвирлау but not in мөлаем
		// better not to make го|ләма
		$parts = preg_split( '/(?=л[аә]($|([уү]|м[аә])(ч[ые])?))(?!л(аем|әма|амә))'
			.'(?<!^бә)/ui',
			$text, null, PREG_SPLIT_NO_EMPTY);
		if(count($parts)==2){
			// $text = $this->convertArabicWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertArabicWordWithTatarEnding( $parts );
		}
		// for китабындагы, but it is converted ok without this
		// // ...сы/сын/etc
		// $parts = preg_split( '/(?<=[ао])(?=(сы$|сын))/ui',
			// $text, null, PREG_SPLIT_NO_EMPTY);
		// if(count($parts)==2){
			// $text = $this->convertRussianWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }
		// // .../ы/ын/etc
		// $parts = preg_split( '/(?<=[бвгджзклмнпрстфхцчшщ])(?=(ы$|ын))/ui',
			// $text, null, PREG_SPLIT_NO_EMPTY);
		// if(count($parts)==2){
			// $text = $this->convertArabicWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }elseif(count($parts)>2){
			// $parts[1] = implode( array_slice( $parts, 1 ) );
			// array_splice( $parts, 2 );
			// $text = $this->convertArabicWordWithTatarEnding( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }
		// ...е
		// икътисадые
		if( preg_match( '/ы(?=(е$|ен))/ui', $text ) ){
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/ы(?=(е$|ен))/ui',
					// $text, $this->currentWordTargetLetterCase );
			$parts = preg_split( '/(?<=ы)(?=(е$|ен))/ui',
				$text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] .= 'й';
			if( preg_match( '/[әөеүи][^аоыуиәөеүи]+ы(е$|ен)/ui', $text ) ){
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			}else{
				$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
			}
			// // иҗтимагый should be ictimaği, see
			// // https://tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
			// // so иҗтимагыенда has to be ictimağiyında
			// if( $parts[0]=='икътисадый' || $parts[0]=='иҗтимагый' ){
				// $parts[1] = 'й' . $parts[1];
			// }
			return $this->convertArabicWordWithTatarEnding( $parts );
		}
		// suffixes are not found
		// $text = $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
		return $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
	}

	function convertTatarWordWithRussianRootFromCyrillicToLatin( $text ){
		// $this->saveCaseOfCyrillicWord( $text );
		// $text = mb_strtolower( $text );
		// dirijabl or dirijabl' or dirijabel ?
		// i leave it as it is : dirijabl'
		// v
		$text = preg_replace( '/в(?!еб)/u', 'v', $text );
		// ya
		// $text = preg_replace( '/(?<=ь)я/u', 'ya', $text );
		// ye
		// премьер, поезд
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=[ьо])е/u',
				// $text, $this->currentWordTargetLetterCase );
					// change case array
		$text = preg_replace( '/(?<=[ьо])е/u', 'ye', $text );
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/^е/u', $text,
				// $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^е/u', 'ye', $text );
		// yo
		// if( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[щч])ё/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[щч])ё/u', 'o', $text );
		// yu
		// $text = preg_replace( '/(?<=ь)ю/u', 'yu', $text );
		// ау, оу
		// шоу, боулинг, маугли - w but in пауза it is u
		$text = preg_replace( '/(?<=[оа])(?<!па)у/u', 'w', $text );
		// ь before ю
		// компьютер
		$text = preg_replace( '/ь(?=ю)/u', '', $text );
		// ь before е
		// премьер
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/ь(?=ye)/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь(?=ye)/u', '', $text );
		// брь
		$text = preg_replace( '/брь/u', 'ber', $text );
		$text = parent::translate( $text, 'tt-latn' );
		// $this->returnCaseOfCyrillicWord( $text );
		if( $this->test ) $text = '['. $text. ']';
		// $text = $text. ']';
		return $text;
	}

	function convertTatarWordWithArabicRootFromCyrillicToLatin( $text ){
		// $this->saveCaseOfCyrillicWord( $text );
		// $text = mb_strtolower( $text );
		// a,ы after soft vowel/syllable
		// should i also change a to ä after ğq because of previous vowels?
		// - i do not know, i do not do this for now:
		$text = preg_replace( '/^рөкъга/u', 'röqğä', $text );
		$text = preg_replace( '/^бәла/u', 'bälä', $text );
		$text = preg_replace( '/^бәһа/u', 'bähä', $text );
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^тәрәкк)ы(?=й)/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккый/u', 'täräqqi', $text );
		// if( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=^тәрәккы)я/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккыя/u', 'täräqqiä', $text );
		$text = preg_replace( '/^нәгыйм/u', 'näğim', $text );
		// a, ya before or after soft syllable
		// no rule of alif -> a in the latin alphabet, so many words
		// should be written as spoken, some of them with synharmonism
		// $text = preg_replace( '/(?<=ид)а(?=рә)/u', 'ä', $text ); // i do
			// not change this one, because sometimes it is pronounced with a
			// and sometimes it would be ugly with ä
		// also i do not change әмм(а), мөл(а)ем, әхл(а)к
		// see some words after replacing g
		$text = preg_replace( '/(?<=сәл)а(?=м)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^ри)я/u', 'ya', $text );
		$text = preg_replace( '/(?<=^(җин|вил|ниһ))а(?=я)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^вәк)а(?=л)/u', 'ä', $text );
		// я before soft syllables
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
		// q after thick vowel/syllable
		// әхлак
		$text = preg_replace( '/(?<=^әхла)к/u', 'q', $text );
		// exception to the following къ,гъ replaces
		$text = preg_replace( '/^камил/u', 'kamil', $text );
		$text = preg_replace( '/^вәк(?=[аи]л)/u', 'wäk', $text );
		$text = preg_replace( '/^куф/u', 'kuf', $text );
		// къ,гъ
		// $text = preg_replace( '/га(?=.+[әүи])/u', 'ğä', $text );
		// тәрәккыять, куәт
		$text = preg_replace( '/к(?=к?[аыоуъ])/u', 'q', $text );
		// гакыл
		$text = preg_replace( '/г(?=[аыоъ])/u', 'ğ', $text );
		// exception to fixing vowels after къ,гъ
		// галим, гата, гади, мөгаен, габдел
		$text = preg_replace( '/(?<=ğ)а(?=(лим?|та|ди|ен|бде))/u',
			'a', $text ); // these words are pronounced that way
		$text = preg_replace( '/(?<=ğ)о(?=мум)/u', 'o', $text ); // leave this
			// as it is written in cyrillic, because i feel ğömüm strange
			// also i added гомер here but then removed
		// кабил, кавия
		$text = preg_replace( '/(?<=q)а(?=(бил|вия))/u', 'a', $text );
		// fix vowels after къ,гъ
		// гамәл
		$text = preg_replace( '/(?<=[qğ])а(?=.+[әеүиь]|е)/u', 'ä', $text );
		// коръән
		$text = preg_replace( '/(?<=[qğ])о(?=.+[әеүиь])/u', 'ö', $text );
		// шөгыле
		$text = preg_replace( '/(?<=[qğ])ы(?=[^йяе]+[әеүиь])/u', 'e', $text );
			// // i
			// // have to set this replace after previous replaces for ы
		// куәт
		$text = preg_replace( '/(?<=q)у(?=ә)/u', 'ü', $text );
		// // exception to next rule
		// // иҗтимагый should be ictimaği, see
		// // https://tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
		// this is not mature/elaborate rule: икътисадыенда should be iqtisadiendä then,
		// and it is hard to make for me, and it is not pronunced so,
		// so i comment this out for now
		// $text = preg_replace( '/(?<=^иҗтимаğ)ый/u', 'i', $text );
		// $text = preg_replace( '/(?<=^иqътисад)ый/u', 'i', $text );
		// гыйльфан
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=[qğ])ы(?=й.+[әеүиь])/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ])ый(?=.+[әеүиь])/u', 'i', $text );
		// җәмгыять, тәрәккые
		$text = preg_replace( '/(?<=[qğ])ы(?=(я.+[әеүиь]|е))/u', 'i', $text );
		// я after га, before soft syllables or ь
		// гаять
		$text = preg_replace( '/(?<=[ğ]ä)я(?=.+[әеүиь])/u', 'yä', $text );
		// я after кы, гы, before soft syllables or ь
		// if( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[qğ]i)я(?=.+[әеүиь])/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ]i)я(?=.+[әеүиь])/u', 'ä', $text );
		// я before soft syllables
		// ягъни, яки, җинаять, сәяси
		$text = preg_replace( '/^я(?=.+[әеүиь])/u', 'yä', $text );
		$text = preg_replace( '/(?<=[аәä])я(?=.+[әеүиь])/u', 'yä', $text );
		// ye
		// ye after ga, ә, а
		// гаять, хөсәения, мөлаем, мөгаен
		// latin a is because a in мөгаен is already converted
		// тәрәккые, мәдәние
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=[äәаa])е/u', $text,
				// $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[äәаa])е/u', 'ye', $text );
		// // икътисадые
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/(?<=ы)е/u', $text,
				// $this->currentWordTargetLetterCase );
		// $text = preg_replace( '/(?<=ы)е/u', 'yı', $text );
		// exceptions to the next ия replace
		$text = preg_replace( '/әдәбият/u', 'ädäbiyat', $text );
		$text = preg_replace( '/суфиян/u', 'sufiyan', $text );
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
		// if( $this->currentWordCapitalisationType == '' )
			// $this->shortenInArray( '/(?<=[иы])я/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/[иы]я/u', 'iä', $text );
		// э
		// тәэмин
		$text = preg_replace( '/э(?=.[аыоуәеөүи])/u', '\'', $text );
		// ризаэтдин
		// if( $this->currentWordCapitalisationType == '' )
			// $this->lengthenInArray( '/э(?=..[аыоуәеөүи])/u',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/э(?=..[аыоуәеөүи])/u', '\'e', $text );
		// ь
		$text = preg_replace( '/(?<=^мәс)ь(?=әлән)/u', '\'', $text );
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/ь/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		// ъ
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^qöр)ъ(?=ән)/u',
				// 'l', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=^qöр)ъ(?=ән)/u', '\'', $text );
		$text = parent::translate( $text, 'tt-latn' );
		// $this->returnCaseOfCyrillicWord( $text );
		if( $this->test ) $text = '('. $text. ')';
		return $text;
	}

	function convertSimpleTatarWordFromCyrillicToLatin( $text ){
		// $this->saveCaseOfCyrillicWord( $text );
		// $text = mb_strtolower( $text );
		if(
			preg_match( '/[аоыу]/u', $text )
			// preg_match( '/[аыу]/u', $text )
			// || preg_match( '/о(?!ва?$)/u', $text )
			// ел, як, юк,
			// е, я, ю
			|| preg_match( '/^([яю].?|е.)$/u', $text )
		){
			// къ, гъ
			$text = preg_replace( '/к(?!ь)(?!арават)/u', 'q', $text );
			$text = preg_replace( '/г/u', 'ğ', $text );
			// // yı, yev
			// yı
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[аыоу])е/u', $text,
					// $this->currentWordTargetLetterCase );
			// // ayev
			// $text = preg_replace( '/(?<=[аыоу])е(?=ва?$)/u', 'ye', $text );
			// // i added latin e to fix v after previous replace in "ov,ev"
			// // $text = preg_replace( '/(?<=e)в(?=а?$)/u', 'v', $text );
			// yı
			$text = preg_replace( '/(?<=[аыоу])е/u', 'yı', $text );
			// if( $this->currentWordCapitalisationType == '' )
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
		}else{
			// yä
			// ия before soft syllables
			// if( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)я(?=.+[әүеи])/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия(?=.+[әүеи])/u', 'iä', $text );
			// ия at end
			// if( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)я$/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия$/u', 'iä', $text );
			// я except янил
			$text = preg_replace( '/я(?!нил)/u', 'yä', $text );
			// ye
			// е is by default e, so i use "lengthen" when it becomes ye.
			// ("by default" means "in toLatin array").
			// if( $this->currentWordCapitalisationType == '' )
				// $this->lengthenInArray( '/(?<=[әө])е/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[әө])е/u', 'ye', $text );
			$text = preg_replace( '/(?<=и)е/u', 'e', $text );
			$text = preg_replace( '/^е/u', 'ye', $text );
			// yü
			$text = preg_replace( '/(?<=[әө])ю/u', 'yü', $text );
			// ю is by default yu so i use lengthen when it becomes ü
			// "by default" ie in toLatin array
			// if( $this->currentWordCapitalisationType == '' )
				// $this->shortenInArray( '/(?<=и)ю/u', $text,
					// $this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=и)ю/u', 'ü', $text );
			$text = preg_replace( '/^ю(?!хиди)/u', 'yü', $text );
			// äw
			$text = preg_replace( '/(?<=ә)ү/u', 'w', $text );
		}
		// ь
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/ь/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		$text = parent::translate( $text, 'tt-latn' );
		// $this->returnCaseOfCyrillicWord( $text );
		if( $this->test ) $text = '|'. $text. '|';
		return $text;
	}

	function convertRussianWordWithTatarEnding( $parts ){
		// $parts[0] = $this->
			// convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[0] );
		// $parts[0] = $this->
			// convertTatarWordFromCyrillicToLatin( $parts[0] ); // i comment this out,
				// because класслар, detected by 3 consonants.
				// класс is not detected as russian. this also was recursion.
		$parts[0] = $this->processWordWithRussianStem( $parts[0] ); //recursion because
				// there are графиктагы and графигында, etc
		$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertCompoundTatarWord( $parts ){
		$parts[0] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertTatarWordWithRussianEnding( $parts ){
		$parts[0] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithRussianStem( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithRussianEnding( $parts ){
		$parts[0] = $this->
			convertTatarWordWithArabicRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithRussianStem( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithTatarEnding( $parts ){
		$parts[0] = $this->
			convertTatarWordWithArabicRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertCompoundWord( $parts ){
		$parts[0] = $this->
			convertTatarWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertTatarWordFromLatinToCyrillic( $text ) {
		/*
		$text = preg_replace( '/I(?=\S*\.(ru|com|org|RU|COM|ORG))/', 'И', $text );
		// INTIRTAT.RU test.com/INTIRTIT
		// ->
		// ИНТИРТАТ.РУ тест.җом/ЫНТЫРТЫТ
		$text = preg_replace( '/iyül/u', 'июль', $text );
		// $text = preg_replace( '/yere/u', 'ерэ', $text ); // see line
			// 590 if( $letter=='е' && $j > 0 ){ ...
		$text = preg_replace( '/yabr/u', 'ябрь', $text );
		$text = preg_replace( '/fäqät/u', 'фәкать', $text );
		$text = preg_replace( '/säläm/u', 'сәлам', $text );
		$text = preg_replace( '/wäkäl/u', 'вәкал', $text );
		$text = preg_replace( '/wiläyät/u', 'вилаять', $text );
		$text = preg_replace( '/kopia/u', 'копия', $text );
		$text = preg_replace( '/tsiä/u', 'ция', $text ); // ассимиляция, инвестиция
		$text = preg_replace( '/abl(?!e)/u', 'абл', $text ); // дирижабль
		$text = preg_replace( '/dizel(?!e)/u', 'дизел', $text );
		$text = preg_replace( '/dvigatel(?!e)/u', 'двигател', $text );
		$text = preg_replace( '/frants/u', 'франц', $text );
		$text = preg_replace( '/gravitats/u', 'гравитац', $text );
		// $text = preg_replace( '/koe/u', 'коэ', $text );
		// $text = preg_replace( '/aero/u', 'аэро', $text );
		$text = preg_replace( '/([ao])e/u', '$1э', $text );
		$text = preg_replace( '/breyk/u', 'брэйк', $text );
		$text = preg_replace( '/krek/u', 'крэк', $text );
		$text = preg_replace( '/planyor/u', 'планёр', $text );
		$text = preg_replace( '/konstrukts/u', 'конструкц', $text );
		// $text = preg_replace( '/dönya/u', 'дөнья', $text );
			$text = preg_replace( '/hakan/u', 'хакан', $text ); // Hakan Fidan
		$text = preg_replace(
			'/(?<=a)k(?![bcçdfghjklmnñpqrsştvwxyz]*[ei])/u',
			'кь',
			$text ); // пакьстан, пакеты, актив
		$text = preg_replace( '/nyaq/u', 'ньяк', $text );
		$text = preg_replace( '/aqyeget/u', 'акъегет', $text );
		// $text = preg_replace( '/ателье/u', 'акъегет', $text );
		$text = preg_replace( '/material/u', 'материал', $text );
			// $text = preg_replace( '/xalıq/u', 'халык', $text );
		$text = preg_replace( '/natural\'/u', 'натураль', $text );
		$text = preg_replace( '/gorizontal\'/u', 'горизонталь', $text );
		$text = preg_replace( '/vertikal\'/u', 'вертикаль', $text );
		$text = preg_replace( '/(?<=[bcçdfghjklmnñpqrsştvwxyz])el\'/u',
			'ель', $text ); // ателье
		$text = preg_replace( '/proportsional\'/u', 'пропорциональ', $text );
		$text = preg_replace( '/aği/u', 'агый', $text );
		$text = preg_replace( '/revolyu/u', 'револю', $text );
		$text = preg_replace( '/evolyu/u', 'эволю', $text );
		$text = preg_replace( '/yudjet/u', 'юджет', $text );
		$text = preg_replace( '/yolka/u', 'ёлка', $text );
		$text = preg_replace( '/şçotka/u', 'щётка', $text );
		$text = preg_replace( '/sçot/u', 'счёт', $text );
		$text = preg_replace( '/qör\'än/u', 'коръән', $text ); // was: корэән
		$text = preg_replace( '/mäs\'älän/u', 'мәсьәлән', $text );
		$text = preg_replace( '/cinäyät/u', 'җинаят', $text );
		$text = preg_replace( '/alyuta/u', 'алюта', $text );
		$text = preg_replace( '/assimilya/u', 'ассимиля', $text );
		$text = preg_replace( '/tsiya/u', 'ция', $text );
		$text = preg_replace( '/ğtibar/u', 'гътибар', $text );
		$text = preg_replace( '/tä\'/u', 'тәэ', $text ); // тәэмин, тәэсир
		$text = preg_replace( '/ma\'/u', 'маэ', $text ); // маэмай
		$text = preg_replace( '/ğiät(?!e)/u', 'гыять', $text );
		// $text = preg_replace( '/qiät(?!e)/u', 'кыять', $text );
		$text = preg_replace( '/[ğq]iyä/u', 'ыя', $text );
		$text = preg_replace( '/\bts/u', 'ц', $text ); // цетнер
		$text = preg_replace( '/ğiä/u', 'гыя', $text );
		// $text = preg_replace( '/ği/u', 'гый', $text );
		// $text = preg_replace( '/Ği/u', 'Гый', $text );
		$text = preg_replace(
			'/(?<=[aıouei][bcçdfghjklmnñpqrsştvwxyz])\''
				. '(?=[bcçdfghjklmnñpqrsştvwxyz][aıouei])/u',
			'ь', $text ); // yum'ya, el'vira
		// $text = preg_replace(
			// '/([aıouei][bcçdfghjklmnñpqrsştvwxyz])\''
			// . '([bcçdfghjklmnñpqrsştvwxyz][aıouei])/u',
			// '$1ь$2', $text ); // yum'ya, el'vira
			// just an alternative regex instead of previous
		// $text = preg_replace( '/yum\'ya/u', 'юмья', $text );
		// $text = preg_replace( '/el\'vira/u', 'эльвира', $text );
		// ^ alternative regexes instead of previous
		$text = preg_replace( '/\be/u', 'э', $text );
		$text = preg_replace( '/\bE/u', 'Э', $text );
		// ye
		// $text = str_replace( 'ye', 'е', $text );
		// $text = str_replace( 'Ye', 'Е', $text );
		// $text = str_replace( 'YE', 'Е', $text );
		// $text = preg_replace( '/([bvgdjzyklmnprstfxcwqh])e/u', '$1е', $text );
		$text = preg_replace( '/([bvgdjzklmnprstfx])y[eE]/u', '$1ье', $text );
		$text = preg_replace( '/([oaıu])y[ıIeE]/u', '$1е', $text ); // боек,проект,аек
		$text = preg_replace( '/([äeiöü])y[eE]/u', '$1е', $text ); // бөек
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^y)[ıe]/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/\by[ıe]/u', 'е', $text );
		$text = preg_replace( '/\bY[ıe]/u', 'Е', $text );
		// ya
		$text = preg_replace( '/(?<=[ğ][äeiöü])y[äә]([bcçdfghjklmnñpqrsştvwxyz])$/u',
			'я$1ь', $text ); // гаять бәян
		$text = preg_replace( '/([äöüeiaouı])y[aä]/u', '$1я', $text ); // куярга
		$text = preg_replace( '/y[äa]ğ/u', 'ягъ', $text ); // ягъни
			$text = preg_replace( '/yärdäm/u', 'ярдәм', $text );
		// $text = preg_replace( '/\byä([bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			// 'я$1ь', $text ); // яшь
		// $text = preg_replace( '/\byü([bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			// 'я$1ь', $text ); // юнь
		$text = preg_replace( '/(?<=\by[äü][bcçdfghjklmnñpqrsştvwxyz])(?![äöüeiaouı])/u',
			'ь', $text ); // яшь, юнь
		$text = preg_replace( '/\by[äәa]/u', 'я', $text );
		$text = preg_replace( '/aw\b/u', 'ау', $text );
		$text = preg_replace( '/aw(?=[bcçdfghjklmnñpqrsştvwxyz])/u', 'ау', $text );
		$text = preg_replace(
			'/äw(?=([bcçdfghjklmnñpqrsştvwxyz]|\b))/u',
				'әү', $text ); // яшәүче, тәвәккәл
		$text = preg_replace(
			'/([äeiöü][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ья', $text ); // дөнья
			$text = preg_replace( '/varyag/u', 'варяг', $text );
			// $text = preg_replace( '/yum\'ya/u', 'юмья', $text );
			// $text = preg_replace( '/el\'vira/u', 'эльвира', $text );
		$text = preg_replace(
			'/([aıou][bcçdfghjklmnñpqrsştvwxyz])y[äәa]/u', '$1ъя', $text ); // ташъязма
		// ю
		// if( $this->currentWordCapitalisationType == '' )
			// $this->replaceIntoArray( '/(?<=^y)[uü]/u', '',
				// $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^y[uü]/u', 'ю', $text );
		$text = preg_replace( '/(?<=[äöüeiaouı])y[uü]/u', 'ю', $text ); // каюм
		// $text = preg_replace( '/\bY[uü]/u', 'Ю', $text );
		$text = preg_replace(
			'/ğ[äә]([bcçdfghjklmnñpqrsştvwxz])(?![äәei])/u',
			'га$1ь',
			$text ); // кәгазь, мөрәҗәгатен, гамәл, мәгариф
		$text = preg_replace( '/ğ[äә]/u', 'га', $text );
		$text = preg_replace( '/ğö/u', 'го', $text ); // гомер
		$text = preg_replace( '/ği/u', 'гый', $text ); // гомер
			$text = preg_replace( '/tuğla/u', 'тугла', $text );
		$text = preg_replace( '/uğ(?=[bcçdfghjklmnñpqrsştvwxyz])/u',
			'угъ', $text ); // тугъры
		// $text = preg_replace( '/Ğ[äÄәÄ]/u', 'Га', $text );
		$text = preg_replace( '/q[äә]/u', 'ка', $text );
		$text = preg_replace( '/qü/u', 'ку', $text );
		// $text = preg_replace( '/Q[äÄәÄ]/u', 'Ка', $text );
			$text = preg_replace( '/ğe([bcçdfghjklmnñpqrsştvwxyz])(?!e)/u',
				'гы$1ь', $text ); // шөгыль
		// $text = preg_replace( '/(?<=[äeiöü])ğ(?![äeöü])/u',
				// 'гъ', $text ); // нәстәгъликъ, шөгыль
		$text = preg_replace( '/ğe/u', 'гы', $text ); // җәмгысе
		$text = preg_replace( '/(?<=[äeiöü])ğ/u', 'гъ', $text ); // нәстәгъликъ
			$text = preg_replace( '/qa/u', 'ка', $text ); // wäqalät-вәкаләт
			$text = preg_replace( '/qqiät(?![ei])/u', 'ккыять', $text ); // тәрәккыять
			$text = preg_replace(
				'/(?<=[äeiöü])q(?=qi)/u', 'к', $text ); //тәрәккыяви
		$text = preg_replace(
			'/(?<=[äeiöü])q/u', 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		$text = preg_replace( '/qiä/u', 'кыя', $text ); // кыяфәт
		// $text = preg_replace(
			// '/(?<=[äeiöü])q(?!([bcçdfghjklmnñpqrsştvwxyz]+(a|iyä)))/u',
			// 'къ', $text ); // икътисадый, wäqalät-вәкаләт, тәрәккыять
		$text = preg_replace( '/i[äә]/u', 'ия', $text );
		$text = preg_replace( '/iü/u', 'ию', $text );
		// ya
		$text = preg_replace( '/y[aä]/u', 'я', $text );
		*/
		// XIII etc
		// made -{XIII}- in test for now
		// w
		// w spelling at end of word or before consonant
		// i moved this replace before ya->я because with я i do not know softness
		$text = preg_replace( '/(?<=[aıou])w(?=$|[^aıouäeöüi])/u', 'у', $text );
		$text = preg_replace( '/(?<=[äeöü])w(?=$|[^aıouäeöüi])/u', 'ү', $text );
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
		$text = preg_replace( '/(?<=simil)ya/u', 'я', $text );
		$text = preg_replace( '/(?<=var)ya/u', 'я', $text );
		$text = preg_replace( '/(?<=kul)ya/u', 'я', $text );
		// ya after apostrophe in russian words
		$text = preg_replace( '/(?<=yum\')ya/u', 'я', $text );
		// yo
		// ya as first letter in russian words
		$text = preg_replace( '/^yo(?=lka)/u', 'ё', $text );
		// yo after consonants in russian words as softening of consonant + mini y + o
		$text = preg_replace( '/(?<=plan)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=şof)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=samol)yo/u', 'ё', $text );
		// yı -> е
		// yı as first letter
		$text = preg_replace( '/^yı/u', 'е', $text );
		// yı as first letter of second part of compound word
		$text = preg_replace( '/(?<=^küp)yı(?=l)/u', 'ье', $text );
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
		// shown with soft vowel: шөгыль - шөгыле,
		// even in яшәүче , which is probably made from same root)
		// яшь, ямь
		// except яши, яшел, яшәү
		$text = preg_replace( '/(?<=^я[şm])(?![äie])/u', 'ь', $text );
		// yä after vowels
		$text = preg_replace( '/(?<=[äöü])yä/u', 'я', $text );
		// ye -> е
		// ye as first letter
		$text = preg_replace( '/^ye/u', 'е', $text );
		// ye as first letter of second part of compound word
		$text = preg_replace( '/(?<=^aq)ye/u', 'ъе', $text );
		// ye after vowels in russian words
		$text = preg_replace( '/(?<=pro)ye/u', 'е', $text );
		$text = preg_replace( '/(?<=po)ye/u', 'е', $text );
		// ye after vowels
		$text = preg_replace( '/(?<=[aouäö])ye/u', 'е', $text );
		// ye after consonants in russian words
		$text = preg_replace( '/(?<=(prem|atel))ye/u', 'ье', $text );
		$text = preg_replace( '/(?<=v)ye/u', 'ье', $text );
		$text = preg_replace( '/(?<=kar)ye/u', 'ье', $text );
		// yü -> ю
		// yü as first letter
		$text = preg_replace( '/^yü/u', 'ю', $text );
		// show softness if it is not shown
		$text = preg_replace( '/(?<=юn$)/u', 'ь', $text );
		// yü after vowels
		$text = preg_replace( '/(?<=[äö])yü/u', 'ю', $text );
		// e
		// e spelling at beginnig of word
		$text = preg_replace( '/^e/u', 'э', $text );
		// e after vowels in russian words
		$text = preg_replace( '/(?<=[aou])e/u', 'э', $text );
		// e after apostrophe
		$text = preg_replace( '/(?<=\')e/u', '', $text );
		$text = preg_replace( '/(?<=riza\')e/u', '', $text );
		// e as "ae"
		$text = preg_replace( '/(?<=br)e(?=yk)/u', 'э', $text );
		$text = preg_replace( '/(?<=kr)e(?=k)/u', 'э', $text );
		// '
		// ' is converted as ь by default
		// ' as hamza
		$text = preg_replace( '/(?<=(tä|ma))\'/u', 'э', $text );
		$text = preg_replace( '/(?<=qör)\'/u', 'ъ', $text );
		$text = preg_replace( '/(?<=riza)\'/u', 'э', $text );
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
		// şç
		// şç as щ, in russian words
		$text = preg_replace( '/şç(?=otka)/u', 'щ', $text );
		$text = preg_replace( '/(?<=li)şç(?=e)/u', 'щ', $text );
		// o
		// o -> ё after soft russian consonants
		$text = preg_replace( '/(?<=^sç)o/u', 'ё', $text );
		$text = preg_replace( '/(?<=^щ)o/u', 'ё', $text );
		// ber -> брь in month names
		$text = preg_replace( '/(?<=[яa])ber(?!e)/u', 'брь', $text );
		$text = preg_replace( '/(?<=[яa])bere/u', 'бре', $text );
		//
		// arabic words
		//
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
		// show softness if it is not shown
		$text = preg_replace( '/(?<=wil)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=cin)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=nih)ä(яt)(?![ei])/u', 'а$1ь', $text );
		// ö
		// ö -> o after ğ,q in arabic words, when softness is shown
		// further with soft wovel
		// гомер, гомәр, гореф, коръән
		$text = preg_replace( '/(?<=[ğq])ö/u', 'о', $text );
		// ü
		// ü -> у in arabic words
		$text = preg_replace( '/(?<=q)ü(?=ä)/u', 'у', $text );
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
		// i -> ый after ğ,q in arabic words when softness is not shown
		// further with soft wovel
		// мөстәкыйль, гыйльфан
		$text = preg_replace(
			'/(?<=[qğ])i([^aıouäeöüiяе])(?![^aıouäeöüiяе]?[eä])/u',
			'ый$1ь', $text );
		// i -> ый after ğ,q in arabic words when softness is shown
		// further with soft wovel
		// гыйлем
		// also when softness is not shown futher
		// тәрәккый, фәгыйләт
		// $text = preg_replace( '/(?<=[ğq])i(?=lem)/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])i(?![eя])/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])i(?=[eя])/u', 'ы', $text );
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
		// $text = '('. $text. ')';
		return $text;
	}

	function latinToUpper( $text ) {
		$text = str_replace( 'i', 'İ', $text );
		$text = mb_strtoupper( $text );
		// $text=str_replace(array('I','ı'),array('İ','I'),$text ); // i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}

	function latinToLower( $text ) {
		$text = str_replace( 'I', 'ı', $text );
		$text = mb_strtolower( $text );
		// $text=str_replace(array('i','İ'),array('ı','i'),$text );
		return $text;
	}

	/*function replaceIntoArray( $pattern, $replacement, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] = $replacement;
			}
		}
	}*/

	/*function shortenInArray( $pattern, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		// $logfileqdb=fopen('x.txt','a');
		// fwrite($logfileqdb,
			// $subject.':'.$patternCount.':'.var_export($matches,true)
			// .':'.var_export($target,true)
			// ." ;;;\n"
			// );
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] = substr(
					$target[$matches[0][$i][1]], 0, 1 );
				// ul->u, ll->l
			}
		}
		// fwrite($logfileqdb,
			// ':'.var_export($target,true)
			// ." ;;;\n"
			// );
	}*/

	/*function lengthenInArray( $pattern, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] .= 'l';
			}
		}
	}*/

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			// separating -{XINHUA}-s is not needed,
			// they are already replaced with 00 bytes.
			$nw = '[^a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']';
			$w = '[a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']';
			$words = preg_split( "/(?<=$w)(?=$nw)|(?<=$nw)(?=$w)/u", $text );
			// $words = preg_split( '/(?<=[aryn])(?= )|(?<= )(?=[tmq])/u', $text );
			// $words = preg_split( '/\b/u', $text );
			// $words = preg_split( '/(\b(?!\'))|((?<=\')(?=\W))/u', $text );
			// $words = preg_split(
				// '/(?=[^0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'])'
				//.'(?<=[0-9a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'])/u',
				// $text );
			// $words = preg_split( '/\b(?!\')|(?<=[\w\'])/u', $text );
			// $words = preg_split( '/(?<!\w)(?=\w)|(?<=[\w\'])(?![\w^\'])/u', $text );
			// $words = preg_split( '/((?<=\W)(?=\w))|((?<=[\w\'])(?!\w))/u', $text );
			// $words = preg_split( '/(?<=[\w\'])(?!\w)/u', $text );
			$wordsCount = count( $words );
			// $this->currentWordTargetLetterCase = array();
			for ( $i = 0; $i < $wordsCount; $i++ ) {
				/*if ( 0 === preg_match( '/[a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']/u',
					$words[$i] )
				) {
					continue; // no latin
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ][a-zäəöɵüışçñğ\']+$/u',
					$words[$i] )
				){
					$this->currentWordCapitalisationType = 'FC'; // first capital
				} elseif ( preg_match( '/^[a-zäəöɵüışçñğ\']+$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'AL'; // all low
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ\']$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'OC'; // only 1
					// letter and it is upper/capital
				// } elseif (
					// i comment this out because style of
					// writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					// preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] )
					// && 0===preg_match( '/^ЕРЭ$/u', $words[$i] )
				// ){
					// $this->currentWordCapitalisationType = 'AC'; // all upper/capital
				} else {
					$tmp=$words[$i];
					// $tmp=preg_replace('/Ye/u','Y', $tmp);
					// $this->currentWordCapitalisationType = '';
					// $this->currentWordTargetLetterCase = array();
					// $this->replaceIntoArray(
						// '/[a-zäəöɵüışçñğ\']/u', 'l', $tmp,
							// $this->currentWordTargetLetterCase );
					// $this->replaceIntoArray(
						// '/[A-ZÄƏÖƟÜİŞÇÑĞ]/u', 'u', $tmp,
							// $this->currentWordTargetLetterCase );
					// $this -> replaceIntoArray(
						// '/[еёяюцщ]/u', 'll',
							// $words[$i], $this->currentWordTargetLetterCase);
					// $this -> replaceIntoArray(
						// '/[ЕЁЯЮЦЩ]/u', 'ul',
							// $words[$i], $this->currentWordTargetLetterCase);
					// $this -> replaceIntoArray(
						// '/[ьЬъЪ]/u', '',
							// $words[$i], $this->currentWordTargetLetterCase);
					ksort( $this->currentWordTargetLetterCase );
				}*/
				// $words[$i] = $this->latinToLower( $words[$i] );
				// $words[$i] = $this->convertTatarWordFromLatinToCyrillic( $words[$i] );
				$words[$i] = preg_split( '/(?=[A-ZÄÖÜİŞÇÑĞ])/u', $words[$i] );
				$wordsICount = count( $words[$i] );
				if( $wordsICount == 1 || $words[$i][0] != '' ){
					// if uppercase letter is not found,
					// should be 1 element in array
					// if word starts with upper case letter,
					// there is first empty string in the preg_split result
					// empty string need not to be converted
					// if no empty string, it should start with lower case
					// lower case string need not to be lowercased
					$words[$i][0] =
						$this->convertTatarWordFromLatinToCyrillic( $words[$i][0] );
				}
				for( $j = 1; $j < $wordsICount; $j++ ){
					// if $wordsICount == 1 this inside part does not run
					// $words[$i][$j] = mb_strtolower( $words[$i][$j] );
					// $words[$i][$j] =
						// $this->convertTatarWordFromCyrillicToLatin(
							// $words[$i][$j] );
					// $words[$i][$j] = $this->latinToUpper(
						// mb_substr( $words[$i][$j], 0, 1 )
					// )
					// . mb_substr( $words[$i][$j], 1 )
					// ;
					$words[$i][$j] =
						$this->convCapitalisedWordLatCyrl( $words[$i][$j] );
				} // j
				$words[$i] = implode( $words[$i] );
				/*$this->currentWordTargetLetterCase = implode(
					$this->currentWordTargetLetterCase );
				if ( $this->currentWordCapitalisationType == 'FC' ) {
					$words[$i] =
						mb_strtoupper( mb_substr( $words[$i], 0, 1 ) )
						. mb_substr( $words[$i], 1 );
				} elseif ( $this->currentWordCapitalisationType == 'OC' ) {
					$words[$i] = mb_strtoupper( $words[$i] );
					// if ( mb_strlen( $words[$i] ) == 1 ){
						// $words[$i] = mb_strtoupper( $words[$i] );
					// }else{
						// $words[$i] =
							// mb_strtoupper( mb_substr( $words[$i], 0, 1 ) )
							// . mb_substr( $words[$i], 1 );
					// }
				// } elseif( $this->currentWordCapitalisationType == 'AC' ){
					// $words[$i] = $this -> latinToUpper( $words[$i] );
				} elseif ( $this->currentWordCapitalisationType == '' ) {
					$targetLength = mb_strlen( $this->currentWordTargetLetterCase );
					$targetWord = '';
					for ( $j = 0; $j < $targetLength; $j++ ) {
						$letter = mb_substr( $words[$i], $j, 1 );
						if ( mb_substr(
								$this->currentWordTargetLetterCase, $j, 1
							) == 'u'
						) {
							if( $letter=='е' && $j > 0 ){
								$letter='э'; // ЕРЭ
							}
							$targetWord .= mb_strtoupper( $letter );
						} else {
							$targetWord .= $letter;
						}
					}
					$words[$i] = $targetWord;
				}*/
				// $words[$i]=$this->currentWordCapitalisationType.$words[$i];
				// $this->currentWordTargetLetterCase = array();
			}// i
			$text = implode( $words );
		} elseif ( $toVariant == 'tt-latn' ) {
			// separating -{XINHUA}-s is not needed, they
			// are already replaced with 00 bytes.
			// $words = preg_split( '/\b/u', $text );
			$combiningAcute = "́";
			$w = 'А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ'.$combiningAcute;
			$words = preg_split(
				"/(?<![$w])(?=[$w])".
				"|(?<=[$w])(?![$w])/u",
				$text );
			$wordsCount = count( $words );
			$this->currentWordTargetLetterCase = array();
			for ( $i = 0; $i < $wordsCount; $i++ ) { // this should be
					// optimised with using i+=2
				if ( 0 === preg_match( '/[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ]/u', $words[$i] ) ){
					continue; // no cyrillic
				}
				// need to handle combining acute. maybe to replace it with ′
				// removing it for now. also removing from test. was Нами́бия
				$words[$i] = preg_replace( "/$combiningAcute/u", '', $words[$i] );
				if( preg_match( '/^КамАЗ[а-яёәөүҗңһ]*$/u', $words[$i] ) ){
					// fix shortening of word kama
					// it is russian word or like russian
					$words[$i] = 'KamA'
						. $this->convCapitalisedWordCyrlLat(
							mb_substr( $words[$i], 4 )
							// last letter of abbreviation should
							// go together with suffixes
							);
				}elseif( preg_match( '/^АКШ[а-яёәөүҗңһ]*$/u', $words[$i] ) ){
					// fix single к which is by default converted as k
					// but in some abbreviations it should be converted as q
					$words[$i] = 'AQ'
						. $this->convCapitalisedWordCyrlLat(
							mb_substr( $words[$i], 2 )
							);
				}else{
					$words[$i] = preg_split( '/(?=[А-ЯЁӘӨҮҖҢҺ])/u', $words[$i] );
					$wordsICount = count( $words[$i] );
					if( $wordsICount == 1 || $words[$i][0] != '' ){
						// if uppercase letter is not found,
						// should be 1 element in array
						// if word starts with upper case letter,
						// there is first empty string in the preg_split result
						// empty string need not to be converted
						// if no empty string, it should start with lower case
						// lower case string need not to be lowercased
						$words[$i][0] =
							$this->convertTatarWordFromCyrillicToLatin( $words[$i][0] );
					}
					for( $j = 1; $j < $wordsICount; $j++ ){
						// if $wordsICount == 1 this inside part does not run
						// $words[$i][$j] = mb_strtolower( $words[$i][$j] );
						// $words[$i][$j] =
							// $this->convertTatarWordFromCyrillicToLatin(
								// $words[$i][$j] );
						// $words[$i][$j] = $this->latinToUpper(
							// mb_substr( $words[$i][$j], 0, 1 )
						// )
						// . mb_substr( $words[$i][$j], 1 )
						// ;
						$words[$i][$j] =
							$this->convCapitalisedWordCyrlLat( $words[$i][$j] );
					} // j
					$words[$i] = implode( $words[$i] );
				}
			}// i
			$text = implode( $words );
		} //elseif
		// $text=str_replace('\'','0', $text );
		// $text=htmlspecialchars($text );
		// $text='***'.$text.'***';
		// file_put_contents('x.txt', $text );
		// $logfileqdb=fopen('x.txt','a');
		// fwrite($logfileqdb,$text );
		return $text;
	}

	function convCapitalisedWordCyrlLat( $text ) {
		$text = mb_strtolower( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		$text = $this->convertTatarWordFromCyrillicToLatin( $text );
		$text = $this->latinToUpper( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		return $text;
	}

	function convCapitalisedWordLatCyrl( $text ) {
		$text = $this->latinToLower( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		$text = $this->convertTatarWordFromLatinToCyrillic( $text );
		$text = mb_strtoupper( mb_substr( $text, 0, 1 ) )
			. mb_substr( $text, 1 );
		return $text;
	}

	/*function saveCaseOfCyrillicWord( &$text ) {
		if ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ][а-яёәөүҗңһ]+$/u', $text ) ){
			$this->currentWordCapitalisationType = 'FC'; // first capital
		} elseif ( preg_match( '/^[а-яёәөүҗңһ]+$/u', $text ) ){
			$this->currentWordCapitalisationType = 'AL'; // all low
		} elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]$/u', $text ) ){
			$this->currentWordCapitalisationType = 'OC'; // only 1 letter
				// and it is upper/capital
		} else {
			$this->currentWordCapitalisationType = '';
			// $this->currentWordTargetLetterCase = array();
			$this->replaceIntoArray(
				'/[а-хчшыьэәөүҗңһ]/u', 'l', $text,
				$this->currentWordTargetLetterCase );
			$this->replaceIntoArray(
				'/[А-ХЧШЫЬЭӘӨҮҖҢҺ]/u', 'u', $text,
				$this->currentWordTargetLetterCase
			);
			$this->replaceIntoArray(
				'/[ёяюцщ]/u', 'll', $text, $this->currentWordTargetLetterCase );
			$this->replaceIntoArray(
				'/[ЁЯЮЦЩ]/u', 'ul', $text, $this->currentWordTargetLetterCase );
			$this->replaceIntoArray(
				'/[ъЪ]/u', '', $text, $this->currentWordTargetLetterCase );
			ksort( $this->currentWordTargetLetterCase );
			// $this->currentWordTargetLetterCase = implode(
				// $this->currentWordTargetLetterCase );
		}
	}*/

	/*function returnCaseOfCyrillicWord( &$text ) {
		$this->currentWordTargetLetterCase = implode(
			$this->currentWordTargetLetterCase );
		// $words[$i] = implode( $this->currentWordTargetLetterCase ); // uncomment
			// to see target cases
		if ( $this->currentWordCapitalisationType == 'FC' ) {
			$text =
				$this->latinToUpper( mb_substr( $text, 0, 1 ) )
				. mb_substr( $text, 1 );
		} elseif ( $this->currentWordCapitalisationType == 'OC' ) {
			if ( mb_strlen( $text ) == 1 ) {
				$text = $this->latinToUpper( $text );
			} else {
				$text =
					$this->latinToUpper( mb_substr( $text, 0, 1 ) )
					. mb_substr( $text, 1 );
			}
		// }elseif( $this->currentWordCapitalisationType == 'AC' ){
			// $text = $this -> latinToUpper( $text );
		} elseif ( $this->currentWordCapitalisationType == '' ){
			$targetLength = mb_strlen( $this->currentWordTargetLetterCase )+3;
			$targetWord = '';
			for ( $j = 0; $j < $targetLength; $j++ ) {
				$letter = mb_substr( $text, $j, 1 );
				if ( mb_substr( $this->currentWordTargetLetterCase, $j, 1 ) == 'u' ){
					$targetWord .= $this->latinToUpper( $letter );
				} else {
					$targetWord .= $letter;
				}
			}
			$text = $targetWord;
		}
		// $text=$this->currentWordCapitalisationType.$text;
		$this->currentWordTargetLetterCase = array();
	}*/
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
