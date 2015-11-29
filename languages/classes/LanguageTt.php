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
			preg_match( '/суүсем|әрдоган/ui', $text )
			|| preg_match( '/илбашы|аскорма|аккош|коточкыч/ui', $text )
			// need to add аскорма, even if its both parts are hard/thick, because else
				// it goes into russian part/branch because of о at second syllable
			// no need to add бер(рәт|ничә|дәнбер), because their both parts are soft/mild/thin
			|| preg_match( '/бераз|кайбер|һәрчак/ui', $text )
			|| preg_match( '/[^әэеөүи]+(сыман|баш)/ui', $text )
		){
			// $text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
			// $text = '{'. $text. '}';
			return $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		}elseif(preg_match( '/^[кт]өньяк|биектау/ui', $text )){
			$parts = preg_split( '/(?<=([кт]өнь|биек))/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif(preg_match( '/^көн(чыгыш|батыш)|гөлбакча|ч[иы]наяк|башкорт/ui', $text )){
			// $parts = preg_split( '/(?<=көн)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts = preg_split( '/(?<=^...)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif(preg_match( '/ара$/ui', $text )){
			$parts = preg_split( '/(?=ара)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif(preg_match( '/оглу/ui', $text )){
			$parts = preg_split( '/(?=оглу)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
		}elseif(preg_match( '/.+стан/ui', $text )){
			// need to add стан even after hard syllables because else if it is after
			// consonant there are 3 consecutive consonants and it goes to russian branch
			$parts = preg_split( '/(?=стан)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			$parts[0] = $this->
				convertTatarWordFromCyrillicToLatin( $parts[0] ); // recursion
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			// return $parts[0] . parent::translate( $parts[1], 'tt-latn' );
			return $parts[0] . $parts[1];
		}elseif( preg_match( '/.+[ое]ва?([гк]а(ча)?|[дт]а(н|гы|й)?|ның?|ча|лар)/ui', $text ) ){
			$parts = preg_split( '/(?<=[ое]в)/ui', $text, null, PREG_SPLIT_NO_EMPTY); // i do
				//not include here а after ов/ев because look behind regex should be fixed length
			$parts[0] = $this->
				convertTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			// return $parts[0] . parent::translate( $parts[1], 'tt-latn' );
			return $parts[0] . $parts[1];
		}elseif(preg_match( '/ташъязма|акъегет/ui', $text )){
			$parts = preg_split( '/(?<=ъ)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			// $text = $this->convertCompoundTatarWord( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWord( $parts );
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
				'/әэ|^г(а(яр|еб|йр)е|о(мер|реф))|ка(бер|леб)|маэмай|вилаят|аэтдин/ui'
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
			// гаяре, гаебе would be catched here, but so i catch such arabic words before
			// reaching to these checks. i have tried to add here .+ at end of regex but then
			// they go to tatar branch, if i do not add them in arabic check.
			// also e at end of word is also possible in russian words: каре, пеле, желе
			// so i better check them before here
			// катер, кушкет
			|| preg_match( '/^[^әөүҗңһ]*[аоу][^әөүҗңһ]*[бвгджзклмнпрстфхцчшщ]+е/ui', $text )
			// 3 consecutive consonants except артта, астта, антта
			|| preg_match( '/[бвгджзклмнпрстфхцчшщ]{3,}(?<![рнс]тт)/ui', $text )
			// || preg_match( '/в[бвгджзклмнпрстфхцчшщ]|[бвгджзклмнпрстфхцчшщ]в/ui', $text )
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
			|| preg_match( '/.{3,}ж/ui', $text )
			// words like винт, грамм, штутгард, бриг,
			// шпиг, во, волга, вьет, etc
			// ^ви also catches вилаять, but seems it is only one such arabic words
			// so i catch it before russian words.
			// красивый, трамвай
			// версия, веб
			|| preg_match( '/^(в[иоье]|ш[тп]|[пгбкт]р|сс)/ui', $text )
			// акт, пакт, тракт, etc
			// i do not add here да, га, лар suffixes, because with them it is catched
			// because of 3 consecutive consonants
			// поезд
			|| preg_match( '/(акт|зд)($|ы($|м|ң|[бг]ыз))/ui', $text )
			// физика etc except шикаять
			|| preg_match( '/.+(?<!ш)ика/ui', $text )
			// товар, овал
			|| preg_match( '/^.?ова/ui', $text )
			// вариант, авиа, шоу, аэро, поэма, пуэр, ноябрь, ноябре
			|| preg_match( '/[аоу]э|их?а|оу|бр[ье]/ui', $text )
			// other russian words
			// кукмара is tatar word but it work ok as russian word (its у is slightly
			// different than russian у, but it is not shown in the latin)
			|| preg_match( '/^к(а(ндидат|бина|маз?)|у(рс|кмара))/ui', $text )
			|| preg_match( '/^нигерия|актив|импер|^ефрат|тугрик|сигнал/ui', $text )
			|| preg_match(
				'/^г(аз|о(а|л|би|$|да(н|гы)?|га(ча)?|сыз|чы|ның?|рилла))/ui',
				$text )
			// i cannot use here just ^ав because there are tatar words авыр etc
			// i removed авиа and added иа
			|| preg_match( '/ав(а(тар|рия))/ui', $text )
			|| preg_match( '/шпага|^дог($|ка(ча)?|та(н|гы)?|ның?)|юмья|кизнер/ui', $text ) // дог
				// is rare word but it is in the test
			// these would go into arabic branch, that also work ok for now, but i
			// send багира, тагил here because problems may appear with г, and send химия here,
			// because problems may appear with thick и
			|| preg_match( '/багира|тагил|^хими|маугли|юбиле/ui', $text )
			// these would go into tatar branch
			// i was going to add karta into russian but there is also tatar qarta
			// and other word for karta - xarita in literary language
			// karta and qarta cannot be distinguished without semantics or context
			|| preg_match( '/музыка|в(епс|ышка|алда)|пауза|(за|у)каз/ui', $text )
		){
			// process words with russian stem
			// ...лык/лек except электр
			$parts = preg_split( '/(?=л[ые][кг])(?!лектр)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
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
				'/(?<!^(шпа|щёт|вол))(?<!^ёл)(?<!(физ|мат)и|ссыл|музы)'
				.'(?=[гк][аә](ч[аә])?$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			if(count($parts)==2){
				// $text = $this->convertRussianWordWithTatarEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
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
				if( $this->currentWordCapitalisationType == '' )
					$this->lengthenInArray( '/[еа](?=(е$|ен))/ui',
						$text, $this->currentWordTargetLetterCase );
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
			$parts = preg_split( '/(?<=[бвгджзклмнпрстфхцчшщ])(?=(ы$|ын))/ui',
				$text, null, PREG_SPLIT_NO_EMPTY);
			if(count($parts)==2){
				// $text = $this->convertRussianWordWithTatarEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertRussianWordWithTatarEnding( $parts );
			}
			// ...да/дан/дагы/дә/та/тә/etc
			$parts = preg_split( '/(?=[тд][аә](н|[гк][ые])?$)/ui',
				$text, null, PREG_SPLIT_NO_EMPTY);
			if(count($parts)==2){
				// $text = $this->convertRussianWordWithTatarEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertRussianWordWithTatarEnding( $parts );
			}
			// ...лар/etc
			$parts = preg_split( '/(?=[лн][аә]р)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
			if(count($parts)==2){
				// $text = $this->convertRussianWordWithTatarEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertRussianWordWithTatarEnding( $parts );
			}
			// tatar suffixes are not found
			// $text = $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
			return $this->convertTatarWordWithRussianRootFromCyrillicToLatin( $text );
		}elseif(
			// signs of arabic words
			// и after 2 letters except юри, боулинг
			preg_match( '/.{2,}и(?<!^юри$)(?!нг)/ui', $text )
			// i cannot add here е after а becausee of tatar words
			// like сагаеп
			|| preg_match( '/.*[аыо].*[әү]/ui', $text )
			|| preg_match( '/.*[әөи].*[аыу]/ui', $text )
			// тәэмин, тәэсир, i do not add аэ for маэмай here, i can catch words
			// like аэробус with аэро, but i better catch them with аэ, and маэмай
			// is probably only one word like that, and catch маэмай
			// before russian words
			// || preg_match( '/әэ/ui', $text ) // i moved this to first check of arabic
			// || preg_match( '/ьә/ui', $text ) // replaced by the next replace
			// гаять etc except яшь юнь etc
			|| preg_match( '/(?<!^[яю].)ь(?!е)/ui', $text )
			// i cannot catch words гаяре, гаебе with signs because there are
			// such words in tatar: бая, сагаеп
			// also they tend to enter russian branch
			// so i catch them before russian words check.
			// other arabic words:
			|| preg_match( '/куф[аи]|^г(ае[пт]|ыйлем|омум)|^рия/ui', $text )
			// робагый, корбан have gone to tatar branch but i do not need to fix them
		){
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
			// рәмиев, вәлиев
			if( preg_match( '/.+ева?$/ui', $text ) ){
				// if( $this->currentWordCapitalisationType == '' )
					// $this->lengthenInArray( '/(?<=[аоыуәе])е(?=ва?$)/ui',
						// $text, $this->currentWordTargetLetterCase );
				$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?$)/ui', 'йе', $text );
				$parts = preg_split( '/(?=ева?$)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
				// $text = $this->convertArabicWordWithRussianEnding( $parts );
				// return parent::translate( $text, 'tt-latn' );
				return $this->convertArabicWordWithRussianEnding( $parts );
			}
			// ...ла,лау,лама,лаучы,...
			// тасвирлау but not in мөлаем
			$parts = preg_split( '/(?=л[аә]($|([уү]|м[аә])(ч[ые])?))(?!лаем)/ui', $text, null, PREG_SPLIT_NO_EMPTY);
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
			// suffixes are not found
			// $text = $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
			return $this->convertTatarWordWithArabicRootFromCyrillicToLatin( $text );
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

	function convertTatarWordWithRussianRootFromCyrillicToLatin( $text ){
		$this->saveCaseOfCyrillicWord( $text );
		$text = mb_strtolower( $text );
		// dirijabl or dirijabl\' or dirijabel ?
		// i leave it as it is : dirijabl\'
		// v
		$text = preg_replace( '/в(?!еб)/u', 'v', $text );
		// ya
		// $text = preg_replace( '/(?<=ь)я/u', 'ya', $text );
		// ye
		// премьер, поезд
		if( $this->currentWordCapitalisationType == '' )
			$this->lengthenInArray( '/(?<=[ьо])е/u', $text, $this->currentWordTargetLetterCase );
				// change case array
		$text = preg_replace( '/(?<=[ьо])е/u', 'ye', $text );
		if( $this->currentWordCapitalisationType == '' )
			$this->lengthenInArray( '/^е/u', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^е/u', 'ye', $text );
		// yo
		if( $this->currentWordCapitalisationType == '' )
			$this->shortenInArray( '/(?<=[щч])ё/u', $text, $this->currentWordTargetLetterCase );
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
		if( $this->currentWordCapitalisationType == '' )
			$this->lengthenInArray( '/ь(?=ye)/u', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь(?=ye)/u', '', $text );
		// брь
		$text = preg_replace( '/брь/u', 'ber', $text );
		$text = parent::translate( $text, 'tt-latn' );
		$this->returnCaseOfCyrillicWord( $text );
		$text = '['. $text. ']';
		// $text = $text. ']';
		return $text;
	}

	function convertTatarWordWithArabicRootFromCyrillicToLatin( $text ){
		$this->saveCaseOfCyrillicWord( $text );
		$text = mb_strtolower( $text );
		// a,ы after soft vowel/syllable
		// should i also change a to ä after ğq because of previous vowels?
		// - i do not know, i do not do this for now:
		$text = preg_replace( '/^рөкъга/u', 'röqğä', $text );
		$text = preg_replace( '/^бәла/u', 'bälä', $text );
		$text = preg_replace( '/^бәһа/u', 'bähä', $text );
		if( $this->currentWordCapitalisationType == '' )
			$this->replaceIntoArray( '/(?<=^тәрәкк)ы(?=й)/u', '',
				$text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккый/u', 'täräqqi', $text );
		if( $this->currentWordCapitalisationType == '' )
			$this->shortenInArray( '/(?<=^тәрәккы)я/u',
				$text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/^тәрәккыя/u', 'täräqqiä', $text );
		$text = preg_replace( '/^нәгыйм/u', 'näğim', $text );
		// a, ya before or after soft syllable
		// no rule of alif -> a in the latin alphabet, so many words
		// should be written as spoken, some of them with synharmonism
		// $text = preg_replace( '/(?<=ид)а(?=рә)/u', 'ä', $text ); // i do
			// not change this one, because sometimes it is pronounced with a
			// and sometimes it would be ugly with ä
		// also i do not change әмм(а), мөл(а)ем
		// see some words after replacing g
		$text = preg_replace( '/(?<=сәл)а(?=м)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^ри)я/u', 'ya', $text );
		$text = preg_replace( '/(?<=^җин)а(?=я)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^вил)а(?=я)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^ниһ)а(?=я)/u', 'ä', $text );
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
		// exception to the following къ,гъ replaces
		$text = preg_replace( '/^камил/u', 'kamil', $text );
		// къ,гъ
		// $text = preg_replace( '/га(?=.+[әүи])/u', 'ğä', $text );
		// тәрәккыять
		$text = preg_replace( '/к(?=к?[аыоъ])/u', 'q', $text );
		// гакыл
		$text = preg_replace( '/г(?=[аыоъ])/u', 'ğ', $text );
		// exception to fixing vowels after къ,гъ
		$text = preg_replace( '/(?<=ğ)а(?=(лим?|та))/u', 'a', $text ); // this word is
			// pronounced that way
		$text = preg_replace( '/(?<=ğ)о(?=мум)/u', 'o', $text ); // leave this
			// as it is written in cyrillic, because i feel ğömüm strange
		$text = preg_replace( '/(?<=q)а(?=бил)/u', 'a', $text );
		// fix vowels after къ,гъ
		// гамәл
		$text = preg_replace( '/(?<=[qğ])а(?=.+[әеүиь]|е)/u', 'ä', $text );
		// коръән
		$text = preg_replace( '/(?<=[qğ])о(?=.+[әеүиь])/u', 'ö', $text );
		// гыйльфан
		if( $this->currentWordCapitalisationType == '' )
			$this->replaceIntoArray( '/(?<=[qğ])ы(?=й.+[әеүиь])/u', '',
				$text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ])ый(?=.+[әеүиь])/u', 'i', $text );
		// җәмгыять, тәрәккые
		$text = preg_replace( '/(?<=[qğ])ы(?=(я.+[әеүиь]|е))/u', 'i', $text );
		// шөгыле
		$text = preg_replace( '/(?<=[qğ])ы(?=.+[әеүиь])/u', 'e', $text ); // i
			// have to set this replace after previous replaces for ы
		// я after га, before soft syllables or ь
		// гаять
		$text = preg_replace( '/(?<=[ğ]ä)я(?=.+[әеүиь])/u', 'yä', $text );
		// я after кы, гы, before soft syllables or ь
		if( $this->currentWordCapitalisationType == '' )
			$this->shortenInArray( '/(?<=[qğ]i)я(?=.+[әеүиь])/u',
				$text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[qğ]i)я(?=.+[әеүиь])/u', 'ä', $text );
		// я before soft syllables
		// ягъни, яки, җинаять, сәяси
		$text = preg_replace( '/^я(?=.+[әеүиь])/u', 'yä', $text );
		$text = preg_replace( '/(?<=[аәä])я(?=.+[әеүиь])/u', 'yä', $text );
		// ye
		// ye after ga, ә, а
		// гаять, хөсәения, мөлаем
		if( $this->currentWordCapitalisationType == '' )
			$this->lengthenInArray( '/(?<=[äә])е/u', $text,
				$this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=[äәа])е/u', 'ye', $text );
		// exceptions to the next ия replace
		$text = preg_replace( '/әдәбият/u', 'ädäbiyat', $text );
		$text = preg_replace( '/суфиян/u', 'sufiyan', $text );
		// ыя
		// васыять assuming wasiät (may be wasıyät, if thickness/hardness of s is wanted to show)
		// ия
		// әдәбият is also replaced by this and i leave it so, because it is very often
		// pronounced that way, though it is әдәбийат in the cyrillic by its arabic word,
		// (but there is no such rule in the latin). i change my mind, it was teached to say
		// әдәбийат in schools, and it is considered correct by many people
		if( $this->currentWordCapitalisationType == '' )
			$this->shortenInArray( '/(?<=[иы])я/u', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/[иы]я/u', 'iä', $text );
		// э
		$text = preg_replace( '/э(?=.[аыоуәеөүи])/u', '\'', $text ); // тәэмин
		if( $this->currentWordCapitalisationType == '' )
			$this->lengthenInArray( '/э(?=..[аыоуәеөүи])/u',
				$text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/э(?=..[аыоуәеөүи])/u', '\'e', $text ); // ризаэтдин
		// ь
		$text = preg_replace( '/(?<=^мәс)ь(?=әлән)/u', '\'', $text );
		if( $this->currentWordCapitalisationType == '' )
			$this->replaceIntoArray( '/ь/u', '', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		// ъ
		if( $this->currentWordCapitalisationType == '' )
			$this->replaceIntoArray( '/(?<=^qöр)ъ(?=ән)/u',
				'l', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/(?<=^qöр)ъ(?=ән)/u', '\'', $text );
		$text = parent::translate( $text, 'tt-latn' );
		$this->returnCaseOfCyrillicWord( $text );
		$text = '('. $text. ')';
		return $text;
	}

	function convertSimpleTatarWordFromCyrillicToLatin( $text ){
		$this->saveCaseOfCyrillicWord( $text );
		$text = mb_strtolower( $text );
		if(
			preg_match( '/[аоыу]/u', $text )
			// preg_match( '/[аыу]/u', $text )
			// || preg_match( '/о(?!ва?$)/u', $text )
			|| preg_match( '/^[яею].$/u', $text ) // ел, як, юк
		){
			// къ, гъ
			$text = preg_replace( '/к(?!ь)(?!арават)/u', 'q', $text );
			$text = preg_replace( '/г/u', 'ğ', $text );
			// // yı, yev
			// yı
			if( $this->currentWordCapitalisationType == '' )
				$this->lengthenInArray( '/(?<=[аыоу])е/u', $text,
					$this->currentWordTargetLetterCase );
			// // ayev
			// $text = preg_replace( '/(?<=[аыоу])е(?=ва?$)/u', 'ye', $text );
			// // i added latin e to fix v after previous replace in "ov,ev"
			// // $text = preg_replace( '/(?<=e)в(?=а?$)/u', 'v', $text );
			// yı
			$text = preg_replace( '/(?<=[аыоу])е/u', 'yı', $text );
			if( $this->currentWordCapitalisationType == '' )
				$this->lengthenInArray( '/^е/u', $text, $this->currentWordTargetLetterCase );
			$text = preg_replace( '/^е/u', 'yı', $text );
			// aw
			// ау, аяу
			$text = preg_replace( '/(?<=[ая])у/u', 'w', $text );
			// // ov,ev
			// // latin e is added because it might be set in "ayev"
			// $text = preg_replace( '/(?<=[аеe])в(?=а?$)/u', 'v', $text );
		}else{
			// yä
			if( $this->currentWordCapitalisationType == '' )
				$this->shortenInArray( '/(?<=и)я(?=.+[әүеи])/u', $text,
					$this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия(?=.+[әүеи])/u', 'iä', $text );
			if( $this->currentWordCapitalisationType == '' )
				$this->shortenInArray( '/(?<=и)я$/u', $text,
					$this->currentWordTargetLetterCase );
			$text = preg_replace( '/ия$/u', 'iä', $text );
			$text = preg_replace( '/я/u', 'yä', $text );
			// ye
			// е is by default e so i use lengthen when it becomes ye
			// "by default" ie in toLatin array
			if( $this->currentWordCapitalisationType == '' )
				$this->lengthenInArray( '/(?<=[әө])е/u', $text,
					$this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=[әө])е/u', 'ye', $text );
			$text = preg_replace( '/(?<=и)е/u', 'e', $text );
			$text = preg_replace( '/^е/u', 'ye', $text );
			// yü
			$text = preg_replace( '/(?<=[әө])ю/u', 'yü', $text );
			// ю is by default yu so i use lengthen when it becomes ü
			// "by default" ie in toLatin array
			if( $this->currentWordCapitalisationType == '' )
				$this->shortenInArray( '/(?<=и)ю/u', $text,
					$this->currentWordTargetLetterCase );
			$text = preg_replace( '/(?<=и)ю/u', 'ü', $text );
			$text = preg_replace( '/^ю/u', 'yü', $text );
			// äw
			$text = preg_replace( '/(?<=ә)ү/u', 'w', $text );
		}
		// ь
		if( $this->currentWordCapitalisationType == '' )
			$this->replaceIntoArray( '/ь/u', '', $text, $this->currentWordTargetLetterCase );
		$text = preg_replace( '/ь/u', '', $text );
		$text = parent::translate( $text, 'tt-latn' );
		$this->returnCaseOfCyrillicWord( $text );
		$text = '|'. $text. '|';
		return $text;
	}

	function convertRussianWordWithTatarEnding( $parts ){
		$parts[0] = $this->
			convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
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
			convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithRussianEnding( $parts ){
		$parts[0] = $this->
			convertTatarWordWithArabicRootFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertTatarWordWithRussianRootFromCyrillicToLatin( $parts[1] );
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

	function convertTatarWordFromLatinToCyrillic( $text ) {
		$text = parent::translate( $text, 'tt-cyrl' );
		// $text = '('. $text. ')';
		return $text;
	}

	function toUpper( $text ) {
		$text = str_replace( 'i', 'İ', $text );
		$text = mb_strtoupper( $text );
		// $text=str_replace(array('I','ı'),array('İ','I'),$text ); // i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}

	function toLower( $text ) {
		$text = str_replace( 'I', 'ı', $text );
		$text = mb_strtolower( $text );
		// $text=str_replace(array('i','İ'),array('ı','i'),$text );
		return $text;
	}

	function replaceIntoArray( $pattern, $replacement, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] = $replacement;
			}
		}
	}

	function shortenInArray( $pattern, $subject, &$target ) {
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
				$target[$matches[0][$i][1]] = substr( $target[$matches[0][$i][1]], 0, 1 );
				// ul->u, ll->l
			}
		}
		// fwrite($logfileqdb,
			// ':'.var_export($target,true)
			// ." ;;;\n"
			// );
	}

	function lengthenInArray( $pattern, $subject, &$target ) {
		$patternCount = preg_match_all(
			$pattern, $subject, $matches, PREG_OFFSET_CAPTURE
		);
		if ( $patternCount > 0 ) {
			for ( $i = 0; $i < $patternCount; $i++ ){
				$target[$matches[0][$i][1]] .= 'l';
			}
		}
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			// separating -{XINHUA}-s is not needed, they are already replaced with 00 bytes.
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
			$this->currentWordTargetLetterCase = array();
			for ( $i = 0; $i < $wordsCount; $i++ ) {
				if ( 0 === preg_match( '/[a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\']/u', $words[$i] ) ) {
					continue; // no latin
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ][a-zäəöɵüışçñğ\']+$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'FC'; // first capital
				} elseif ( preg_match( '/^[a-zäəöɵüışçñğ\']+$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'AL'; // all low
				} elseif ( preg_match( '/^[A-ZÄƏÖƟÜİŞÇÑĞ\']$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'OC'; // only 1
					// letter and it is upper/capital
				// } elseif (
					// i comment this out because style of writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					// preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] )
					// && 0===preg_match( '/^ЕРЭ$/u', $words[$i] )
				// ){
					// $this->currentWordCapitalisationType = 'AC'; // all upper/capital
				} else {
					$tmp=$words[$i];
					$tmp=preg_replace('/Ye/u','Y', $tmp);
					$this->currentWordCapitalisationType = '';
					// $this->currentWordTargetLetterCase = array();
					$this->replaceIntoArray(
						'/[a-zäəöɵüışçñğ\']/u', 'l', $tmp,
						$this->currentWordTargetLetterCase );
					$this->replaceIntoArray(
						'/[A-ZÄƏÖƟÜİŞÇÑĞ]/u', 'u', $tmp, $this->currentWordTargetLetterCase
					);
					// $this -> replaceIntoArray(
						// '/[еёяюцщ]/u', 'll', $words[$i], $this->currentWordTargetLetterCase);
					// $this -> replaceIntoArray(
						// '/[ЕЁЯЮЦЩ]/u', 'ul', $words[$i], $this->currentWordTargetLetterCase);
					// $this -> replaceIntoArray(
						// '/[ьЬъЪ]/u', '', $words[$i], $this->currentWordTargetLetterCase);
					ksort( $this->currentWordTargetLetterCase );
					$this->currentWordTargetLetterCase = implode(
						$this->currentWordTargetLetterCase );
				}
				$words[$i] = $this->toLower( $words[$i] );
				$words[$i] = $this->convertTatarWordFromLatinToCyrillic( $words[$i] );
				// $words[$i] = implode( $this->currentWordTargetLetterCase ); // uncomment
					// to see target cases
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
					// $words[$i] = $this -> toUpper( $words[$i] );
				} elseif ( $this->currentWordCapitalisationType == '' ) {
					$targetLength = mb_strlen( $this->currentWordTargetLetterCase );
					$targetWord = '';
					for ( $j = 0; $j < $targetLength; $j++ ) {
						$letter = mb_substr( $words[$i], $j, 1 );
						if ( mb_substr( $this->currentWordTargetLetterCase, $j, 1 ) == 'u' ) {
							if( $letter=='е' && $j > 0 ){
								$letter='э'; // ЕРЭ
							}
							$targetWord .= mb_strtoupper( $letter );
						} else {
							$targetWord .= $letter;
						}
					}
					$words[$i] = $targetWord;
				}
				// $words[$i]=$this->currentWordCapitalisationType.$words[$i];
				$this->currentWordTargetLetterCase = array();
			}// i
			$text = join( $words );
		} elseif ( $toVariant == 'tt-latn' ) {
			// separating -{XINHUA}-s is not needed, they are already replaced with 00 bytes.
			// $words = preg_split( '/\b/u', $text );
			$words = preg_split(
				'/(?<![А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])(?=[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])|'.
				'(?<=[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])(?![А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ])/u',
				$text );
			$wordsCount = count( $words );
			$this->currentWordTargetLetterCase = array();
			for ( $i = 0; $i < $wordsCount; $i++ ) { // this should be optimised with using i+=2
				if ( 0 === preg_match( '/[А-ЯЁӘӨҮҖҢҺа-яёәөүҗңһ]/u', $words[$i] ) ){
					continue; // no cyrillic
				}
				/*
				} elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ][а-яёәөүҗңһ]+$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'FC'; // first capital
				} elseif ( preg_match( '/^[а-яёәөүҗңһ\']+$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'AL'; // all low
				} elseif ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]$/u', $words[$i] ) ){
					$this->currentWordCapitalisationType = 'OC'; // only 1 letter
						// and it is upper/capital
				// }elseif (
					// i comment this out because style of writing all with upper case while
					// it is not an abbreviation is probably not used in wikipedia
					// preg_match( '/^[А-ЯЁӘӨҮҖҢҺ]{2,}$/u', $words[$i] )
					// && 0===preg_match( '/^ЕРЭ$/u', $words[$i] )
				// ){
					// $this->currentWordCapitalisationType = 'AC';// all upper/capital
				} else {
					$this->currentWordCapitalisationType = '';
					// $this->currentWordTargetLetterCase = array();
					$this->replaceIntoArray(
						'/[а-хчшыьэәөүҗңһ]/u', 'l', $words[$i],
						$this->currentWordTargetLetterCase );
						// digits also need not to be capitalised
					$this->replaceIntoArray(
						'/[А-ХЧШЫЬЭӘӨҮҖҢҺ]/u', 'u', $words[$i],
						$this->currentWordTargetLetterCase
					);
					$this->replaceIntoArray(
						'/[ёяюцщ]/u', 'll', $words[$i], $this->currentWordTargetLetterCase );
					$this->replaceIntoArray(
						'/[ЁЯЮЦЩ]/u', 'ul', $words[$i], $this->currentWordTargetLetterCase );
					$this->replaceIntoArray(
						'/[ъЪ]/u', '', $words[$i], $this->currentWordTargetLetterCase );
					ksort( $this->currentWordTargetLetterCase );
					// $this->currentWordTargetLetterCase = implode(
						// $this->currentWordTargetLetterCase );
				}
				*/
				// $words[$i] = $this->toLower( $words[$i] );
				$words[$i] = $this->convertTatarWordFromCyrillicToLatin( $words[$i] );
				/*
				$this->currentWordTargetLetterCase = implode(
					$this->currentWordTargetLetterCase );
				// $words[$i] = implode( $this->currentWordTargetLetterCase ); // uncomment
					// to see target cases
				if ( $this->currentWordCapitalisationType == 'FC' ) {
					$words[$i] =
						$this->toUpper( mb_substr( $words[$i], 0, 1 ) )
						. mb_substr( $words[$i], 1 );
				} elseif ( $this->currentWordCapitalisationType == 'OC' ) {
					if ( mb_strlen( $words[$i] ) == 1 ) {
						$words[$i] = $this->toUpper( $words[$i] );
					} else {
						$words[$i] =
							$this->toUpper( mb_substr( $words[$i], 0, 1 ) )
							. mb_substr( $words[$i], 1 );
					}
				// }elseif( $this->currentWordCapitalisationType == 'AC' ){
					// $words[$i] = $this -> toUpper( $words[$i] );
				} elseif ( $this->currentWordCapitalisationType == '' ){
					$targetLength = mb_strlen( $this->currentWordTargetLetterCase )+3;
					$targetWord = '';
					for ( $j = 0; $j < $targetLength; $j++ ) {
						$letter = mb_substr( $words[$i], $j, 1 );
						if ( mb_substr( $this->currentWordTargetLetterCase, $j, 1 ) == 'u' ){
							$targetWord .= $this->toUpper( $letter );
						} else {
							$targetWord .= $letter;
						}
					}
					$words[$i] = $targetWord;
				}
				// $words[$i]=$this->currentWordCapitalisationType.$words[$i];
				$this->currentWordTargetLetterCase = array();
				*/
			}// i
			$text = join( $words );
			/* // alternative way with using "preg_replace_callback"s
			// in "convertTatarFromCyrillicToLatin" function
			// like this:
			// $text = preg_replace_callback(
				// '/к(?=арав|амаз)/ui',
				// function($m){return $m[0]=='к'?'k':'K';},
				// $text
			// );
			// $text = preg_replace_callback(
				// '/г(?=[аыуъ])/ui',
				// function($m){return $m[0]=='г'?'ğ':'Ğ';},
				// $text
			// );
			$words = preg_split( '/\b/u', $text );
			$wordsCount = count($words);
			for ( $i=0; $i<$wordsCount; $i++ ){
				if ( 0===preg_match( '/[А-ЯЁӘӨҮҖҢҺ]/ui', $words[$i] ) ){
					continue; // no cyrillic
				}
				$words[$i] = $this -> convertTatarFromCyrillicToLatin2( $words[$i] );
			}
			$text=join( $words );*/
			// return parent::translate( $text, $toVariant );
		}
		// $text=str_replace('\'','0', $text );
		// $text=htmlspecialchars($text );
		// $text='***'.$text.'***';
		// file_put_contents('x.txt', $text );
		// $logfileqdb=fopen('x.txt','a');
		// fwrite($logfileqdb,$text );
		return $text;
	}

	function saveCaseOfCyrillicWord( &$text ) {
		if ( preg_match( '/^[А-ЯЁӘӨҮҖҢҺ][а-яёәөүҗңһ]+$/u', $text ) ){
			$this->currentWordCapitalisationType = 'FC'; // first capital
		} elseif ( preg_match( '/^[а-яёәөүҗңһ\']+$/u', $text ) ){
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
				// digits also need not to be capitalised
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
	}

	function returnCaseOfCyrillicWord( &$text ) {
		$this->currentWordTargetLetterCase = implode(
			$this->currentWordTargetLetterCase );
		// $words[$i] = implode( $this->currentWordTargetLetterCase ); // uncomment
			// to see target cases
		if ( $this->currentWordCapitalisationType == 'FC' ) {
			$text =
				$this->toUpper( mb_substr( $text, 0, 1 ) )
				. mb_substr( $text, 1 );
		} elseif ( $this->currentWordCapitalisationType == 'OC' ) {
			if ( mb_strlen( $text ) == 1 ) {
				$text = $this->toUpper( $text );
			} else {
				$text =
					$this->toUpper( mb_substr( $text, 0, 1 ) )
					. mb_substr( $text, 1 );
			}
		// }elseif( $this->currentWordCapitalisationType == 'AC' ){
			// $text = $this -> toUpper( $text );
		} elseif ( $this->currentWordCapitalisationType == '' ){
			$targetLength = mb_strlen( $this->currentWordTargetLetterCase )+3;
			$targetWord = '';
			for ( $j = 0; $j < $targetLength; $j++ ) {
				$letter = mb_substr( $text, $j, 1 );
				if ( mb_substr( $this->currentWordTargetLetterCase, $j, 1 ) == 'u' ){
					$targetWord .= $this->toUpper( $letter );
				} else {
					$targetWord .= $letter;
				}
			}
			$text = $targetWord;
		}
		// $text=$this->currentWordCapitalisationType.$text;
		$this->currentWordTargetLetterCase = array();
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
