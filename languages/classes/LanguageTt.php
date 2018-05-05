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

/**
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {

	// 2017-02-18, author dinar qurbanov:
	// by making this converter, i look like supporting it. but it is not so.
	// *i think this alphabet has many disadvantages, i do not want to make it popular.*
	// i regard this as
	// historical museum showpiece. i think it should be ok to put it into tatar wikipedia,
	// into conversion system of mediawiki. that converted pages are denied for search engines
	// to index, as i know.
	// exact version of latin orthography (and alphabet)
	// was not chosen by voting by wikipedians, and wikipedians have not voted to edit
	// rules of the tatar latin orthography to be used in wikipedia, so, i have
	// decided to make this exactly as it was commanded by 2000's #882 resolution of
	// cabinet of ministers of tatarstan.
	// i use scans published by user Kitap ( https://tt.wikipedia.org/wiki/Татарстанда_татар_телен
	// _дәүләт_теле_буларак_куллану_кануны#Татар_теленең_латин_язулы_орфографиясенең_гамәлдән
	// _чыккан,_хәзерге_вакытта_рәсми_булмаган_кайбер_кагыйдәләре ), but i am not sure whether
	// they are of resolution #882 or #618.
	// that 2000's #882 resolution is canceled by russia law
	// and by resolution #38 of 2013, of cabinet of ministers of republic of tatarstan, and new
	// alphabet is accepted by 2013's law of tatarstan 1-ЗРТ, but that new alphabet is (even) less
	// usable: there is no rules, no character for palatilasation in russian words, and the
	// alphabets' table does not show all use cases of cyrillic letters.
	// and i am going to mark this script as tt-latn-2000. i have found from gerrit comment
	// that it is not ok. ("2000" subtag of variant is not registered in iana yet,
	// but must, see https://en.wikipedia.org/wiki/IETF_language_tag ). then maybe
	// i will mark as tt-latn-x-2000 where it is not variant, but in private-use subtag.

	private $test = 0;

	public $toLatin = [
		// capital letters are handled outside
		// (outside of place this array was planned to use,
		// and is used, ie outside of parent::translate)
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
	];

	public $toCyrillic = [
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
		// 'ɵ' => 'ө', // 'Ɵ' => 'Ө', // this won't work correctly,
		// because only ö is supported in the conversion function, for now
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
	];

	function loadDefaultTables() {
		$this->mTables = [
			'tt-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'tt-latn-x-2000' => new ReplacementArray( $this->toLatin ),
			'tt' => new ReplacementArray()
		];
	}

	// function convertLowercasedTatarWordCyrlToLat(
		// $text, $compoundOtherPart = null, $test = null
	// ) {
	// }

	// function convLcTtWordWithoutThickSuffixesCyToLa( $text, $beforeThickSuffixes = false ) {
	function convertLowercasedTatarWordCyrlToLat(
		$text, $compoundOtherPart = null, $test = null
	) {
		// $text is not only lowercased, it is also deabbreviated,
		// so that abrreviations are splitted into single capital letters
		// and words with fisrt capital letter.
		// but previously this function handled also abbreviations
		// and code for some of them is still here.
		//
		if( $test ) {
			$this->test = $test;
		}
		//
		if (
			preg_match( '/^ткр$/u', $text )
		) {
			return 'tqr';
		}
		//
		// compounds
		if ( preg_match( '/стәрлетама/u', $text ) ) {
			$parts = preg_split( '/(?<=^......)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if (
			preg_match(
				'/^(җигәнтама|йөрәкта|тимер(булат|чыбы)|кайсыбер|ярымутра|миңне.+)/u',
				$text
			)
		) {
			$parts = preg_split( '/(?<=^.....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/^((([кт]өн|һәр)ь|(сул|арт)ъ)я[кг]|биектау|(күп|бер|мең)[ьъ]еллы[кг]'
			. '|карабодай|якшыгол|(таш|кул)ъязма|шәльяу)/u', $text )
		) {
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/^(супер|радио).+/u', $text ) ) {
			$parts = preg_split( '/(?<=^.....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] =
				$this->convertSimpleRussianWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}
		if ( preg_match( '/^(вики|гига|авто|мото|теле).+/u', $text ) ) {
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] =
				$this->convertSimpleRussianWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1], $parts[0] );
			$text = implode( $parts );
			return $text;
		}
		if ( preg_match( '/^(көн(чыгыш|батыш)|гөлбакча|ч[иы]ная[кг]|башкор[тд]'
			. '|коточкыч|бик.{3,}|аяктубы'
			. '|бер(аз|кат|кай(чан)?|туган|вакыт)'
			. '|кай(бер|чак)|һәр(чак|вакыт)|кол(мәт|сәет|мәмәт)|күктау|байегет|(бил|бәк)бау'
			. '|күзал|икепулатлы)/u', $text ) ) {
			// сәет is arabic word but probably it works well as tatar word
			$parts = preg_split( '/(?<=^...)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/^елъязма|унъел|акъегет/u', $text ) ) {
			$parts = preg_split( '/ъ/u', $text, null );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		// if ( preg_match( '/.+су/u', $text ) ) {
		if ( preg_match( '/.+(?<!^ба)(?<!^ы)(?<!^кон)(?<!сек)су/u', $text ) ) {
			$parts = preg_split( '/(?=су)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			// if( $this->surelyIsATatarNounWithSuffixes( $parts[1] ) ) {
				// return $this->convertCompoundTatarWordCyrlToLat( $parts );
			// }
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/.+сыман/u', $text ) ) {
			$parts = preg_split( '/(?=сыман)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/.+баш/u', $text ) ) {
			$parts = preg_split( '/(?=баш)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/.{2,}(намә|ханә|ниса|кяр|улл|[еэ]тдин|җан)/u', $text, $matches ) ) {
			$parts = preg_split(
				'/(?='.$matches[1].')/u', $text, null, PREG_SPLIT_NO_EMPTY
			);
			$parts[0] = $this->convertLowercasedTatarWordCyrlToLat( $parts[0], $matches[1] );
			$parts[1] = $this->processWordWithArabicStemCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}
		if ( preg_match( '/.{2,}(?<!^кукм)(?<!^[аә]нк)(?<!^[бкч])ара$/u', $text ) ) {
			// except кукмара, анкара, бара, чара, кара
			$parts = preg_split( '/(?=ара)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/аскорма|аккош|су(үсем|тудыр)|әрдоган|азкөч|яугир'
			. '|ун(бер|ике|өч|дүрт|биш|алты|җиде|сигез|тугыз)'
			. '|юлтимер|аккирәй|айгөл|яшүсмер|илбаш/u', $text ) ) {
			$parts = preg_split( '/(?<=^..)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			if( $parts[0] == 'яш' ) {
				$parts[0] .= 'ь';
			}
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/^бөекбрит/u', $text ) ) {
			$parts = preg_split( '/(?<=^....)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			// return $this->convertCompoundWordCyrlToLat( $parts ); // it
				// works but let i make this "manually", to avoid going
				// through this check again (in recursion)
			$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->processWordWithRussianStemCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}
		// никадәр, шулкадәр, берникадәр
		if ( preg_match( '/кадәр/u', $text ) ) {
			$parts = preg_split( '/(?=кадәр)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			if( isset( $parts[1] ) ){
				$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
				$parts[1] = $this->convertSimpleArabicWordFromCyrillicToLatin( $parts[1] );
				$text = implode( $parts );
				return $text;
			}
		}
		if ( preg_match( '/^(солтангали|гыйлемхан)/u', $text ) ) {
			// габдел.+|
			$parts = preg_split( '/(?<=^......)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertSimpleArabicWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->convertLowercasedTatarWordCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}
		if ( preg_match( '/^(маһи|габде?).{3,}/u', $text, $matches ) ) {
			$parts = preg_split( '/(?<=^'.$matches[1].')/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->convertSimpleArabicWordFromCyrillicToLatin( $parts[0] );
			$parts[1] = $this->processWordWithArabicStemCyrlToLat( $parts[1] );
			$text = implode( $parts );
			return $text;
		}
		if ( preg_match( '/оглу/u', $text ) ) {
			$parts = preg_split( '/(?=оглу)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			// $text = $this->convertCompoundTatarWordCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertCompoundTatarWordCyrlToLat( $parts );
		}
		if ( preg_match( '/.+стан(?!т(ин|а))/u', $text ) ) {
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
		}
		if ( preg_match( '/известьташ/u', $text ) ) {
			// this works even without splitting,
			// but а in таш is tatar а, so i better split this
			$parts = preg_split( '/(?<=ь)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			return $this->convertRussianWordWithTatarEndingCyrlToLat(
				$parts ); // таш is not ending but this works
		}
		// end of compounds.
		//
		/*
		// catch russian words names with вка,
		// only those which cannot be family names with tatar qa suffix.
		// russian village names сосновка, саминовка,
		// константиноградовка, покровка, etc, датировка
		// where ка is not tatar ending
		if ( preg_match(
			'/((сосн|град|самин|датир|кодир|дозир|петропавл|люб|покр)о|новониколае)вка/u',
			$text )
		) {
			$parts = preg_split( '/(?<=[ое]вка)/u', $text,
				null, PREG_SPLIT_NO_EMPTY );
			$parts[0] = $this->
				convertSimpleRussianWordFromCyrillicToLatin( $parts[0] );
			if( isset( $parts[1] ) ) {
				$parts[1] = $this->
					convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			}
			return implode( $parts );
		}
		*/
		//
		$rootType = $this->tryGetRootOfThickWord( $text, $parts );
		// if( mb_strlen( $parts[1] ) < 2 ) { // can be optimized
			// $text = $this->convLcTtWordWithoutThickSuffixesCyToLa( $text );
			// return $text;
		// }
		// if( $rootType == 1 ) { // thick suffix after thick noun
		if( $rootType == 1 || $rootType == 3 ) { // thick suffix after thick noun
			// root part may be compound and maybe borrowed from different languages
			$parts[0] = $this->convLcTtWordWithoutThickSuffixesCyToLa( $parts[0], true );
			// suffix part can be converted as native tatar words
			$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			if( $this->test ) {
				$parts[0] .= 'n';
			}
		} elseif( $rootType == 2 ) { // thick suffix after thick verb
			if( $this->test ) {
				// root part may be tatar verb, i will try to handle is as such for now
				$parts[0] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[0] );
				// suffix part can be converted as native tatar words
				$parts[1] = $this->convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
				$parts[0] .= 'v';
			} else {
				$text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
				return $text;
			}
		// } elseif( $rootType == 3 ) { // thick suffix after
		} else { // $rootType == 0 - no thick suffix
			$parts[0] = $this->convLcTtWordWithoutThickSuffixesCyToLa( $parts[0] );
		}
		if( $this->test ) {
			$parts[0] .= $rootType;
		}
		$text = implode( $parts );
		return $text;
		// if( preg_match( '/[әөү]/u', $parts[0] ) ) {
		// }else{
		// }
	}

	function convLcTtWordWithoutThickSuffixesCyToLa( $text, $beforeThickSuffixes = false ) {
		//
		/*
		// catching words like вәлиевка which consist of tatar root +
		// + russian suffix ov/ev + tatar suffixes.
		// to split them like вәлиев|ка, then вәли|ев
		if ( preg_match(
			'/[ое]в'
			. '(?=а?[гк]а(ча)?|[дт]а(н|гы|й)?|ның?|ч[ае]|лар)/u',
			$text )
		) {
			// catching only words with suffixes after ov, ev suffix.
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
					// family name may be with russian or arabic or turkic stem
			$parts[1] = $this->
				convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
			return $parts[0] . $parts[1];
		}
		*/
		//
		if (
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
				. '|^г(а((ярь|фиятулл)|(еб|яр|й[рн])е|((лим?|дел)(е|ле|сез)?)|еп(ле|сез))'
					. '|о(мер|реф|шер)|ыйффәт)'
				. '|ка(бер|леб|дер)|маэмай|вилаят|аэтдин|ильяс'
				. '|васыят|ать|фирдәүс|бигате|фиркасе|ядкар|шагыйре|ризыклары'
				. '|^гамь|^гаме(?!т)/u',
				$text )
		) {
			return $this->processWordWithArabicStemCyrlToLat( $text, $beforeThickSuffixes );
		}
		//
		if (
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
			// 3 consecutive consonants except артта, астта, антта, кортлар
			// and except югалтса, югалтмаган, югалткан, утыртканнар
			|| preg_match(
				'/[бвгджзклмнпрстфхцчшщ]{3,}(?<![рнс]тт)(?<![лр]т[смк])(?<!ртл)/u',
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
			// делавэр, мэр, пэр
			|| preg_match( '/[^әөүҗңһ]+э/u', $text )
			// other russian words
			// || preg_match( '/^кама(?!лыш)/u', $text )
			// i try ив$ for объектив etc
			// фф for эффект, etc
			|| preg_match( '/актив|^нигерия|импер|^ефрат|тугрик|сигнал'
				. '|^ив|ив$|фф/u', $text )
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
			// tatar and arabic words --except words starting with к г--.
			// i moved regexes for к г also here.
			// these would go into *arabic* branch, that also work ok for now, but i
			// send багира, тагил here because problems may appear
			// with г, and send химия here,
			// because problems may appear with thick и.
			// бензинсыз was detected as arabic, but
			// бензин goes to tatar branch, so add it there.
			// самин for village name саминовка
			|| preg_match(
				'/^('
				// к
				// кукмара is tatar word but it work ok as russian word (its у is slightly
				// different than russian у, but it is not shown in the latin).
				// i do not add кафтан because there is tatar word qaftan.
				// камал is arabic but it will be converted properly as russian.
				// but i remove камал, because it can be qama+l.
				// remove калуга, because it can be qal+u+ğa.
				. 'к('
				// ка
				. 'а(н(дидат|а(л|да|ш)|түк|зас)|бин|м(аз|зул|би)'
				. '|р(м(алка|ик)|амзин|т(ина|уз)|навал)'
				. '|у(чук|ф)|за(нка|да|ки)|л(и(й|гула)|вин)|питал'
				. '|спий|чал|кур|йман|питан|ма)'
				// do not catch карлар
				. '|арл($|ын|да(н|гы)?|га(ча)?|сыз|ның)'
				// do not catch камалыш
				// . '|ама[сл]ы$'
				// . '|ама(л?|[лс]ын)($|да(н|гы)?|га(ча)?|сыз|ның)'
				// . 'ама(?!лыш)'
				// ку
				// removed куба because it can be qup+a
				. '|у(р(с|асан|тка)|к(мара|уруза|$)|туз|зьм|льт|бан|пала|инси)'
				// ко
				. '|о('
				. 'м(и(сс|$)|анд(а|ир)|мун|б(инат|айн)|пан)'
				. '|н(с(тан|ул)|ка)'
				. '|д($|ын?|та(н|гы)?|ка(ча)?|сыз|чы|ның|иров|лы)'
				. '|жан|фта|с(ынка|та)|р(аб|пус|ея)|лум|ка|ала|х|лбурн|пи?'
				. ')'
				// к...
				. '|изнер'
				. ')'
				// г
				// га
				. '|г('
				// i do not add ga here because there is tatar suffix
				// ğa which can be used separately: 6 ğa.
				. 'а(ук|на|м(аш|бия)|р(ант|сия|ри|дин|филд|хипел)'
				. '|л(мудуг|лия)|биния|й$|ити|ва[йя])'
				// го
				. '|о(р(илла|ки|ьк)|а|би|лланд|фман)'
				// г...
				. '|(аз|ол?)($|да(н|гы)?|га(ча)?$|сыз|чы|ның?|ын?|лар)'
				. '|вин|у(м(анитар|ми)|бан|ам)|еве'
				. ')'
				// б
				. '|б('
				// ба
				. 'а(гира|лабан|бынин|вар|рнаул|нк|йт|гама|рак)'
				// бо
				. '|о(л(гария|ив(ия|ар))|тинка|гдан)'
				. '|елоус|ензин|исау|ушу|урка'
				. ')'
				// т
				. '|т(а(гил|миянг|нк)|о([кг]|нга)|увалу)'
				. '|талала|тиран'
				// х
				. '|х(орватия|ими|арьк|авьер|орват|акас)'
				// ю
				. '|ю(рист|биле)|юридик|юхн|юлия'
				// л
				. '|л(и(га|тва)|окк|а(всан|йкра|нка|твия))|лив(?!ә)'
				// м
				. '|м('
				. 'а(гнит|к(сим|$)|ргарита|даг|р(шан|[кг])|йка|угли|тери[кг]|лайз)'
				. '|о(лдавия|замби)|и(чиган|дуэй)'
				. '|у(зыка|скул)|е(лвин|ркель)'
				. ')'
				// и
				. '|и(юн|жау|нка|н(дивид|тер))'
				. '|июл|извест'
				// н
				. '|н(и(жгар|уэ)|ебраска|аум|ерв)'
				. '|ноя|нефть'
				// п
				. '|п(ермь|ластинка|а(уза|рагвай)|о(дкаст|вар|лк))'
				. '|порту|пояс'
				// в
				. '|в(епс|ышка|а(лда|ссал|нуату)|у(з|лкан)|акуум)'
				. '|васили'
				// о
				. '|о(скар|трад|кт)|органик|окру|объект'
				// с
				. '|с(о(с(тав|нов)|к(рат)?|г)|а(рат|ван|па)|ьерра|е(рвер|гмент))'
				. '|самин|субъект|сингапур|система|сервис|савин'
				// д
				. '|д(екрет|углас|арья|иалект|атир|ивизи|аут|ев|елавэр|оз)'
				. '|дискусс'
				// р
				. '|ре(жисс|зерв)'
				. '|республика|рабфа'
				// а
				. '|а(ксаков|грар|угуст|р(хи|канзас)|ньелли|ква|л(яска|фавит)|дмирал|галега)'
				// з
				. '|з(игзаг|акари)'
				. '|заказ'
				// у
				. '|у(аскаран|о(ррен|кер|ттс|ллис)|э(льс|йк))|указ'
				// уганда can be у+ган+да
				// й
				. '|йошкар|йорк'
				// э
				. '|энерг|эпикур|эфир|эласт'
				// ф
				. '|фуфай|физи|фукс|фолликул|фея'
				// я
				. '|яков|янка'
				// е
				. '|елена'
				// ч
				. '|чабакса'
				// ж
				. '|жук'
				. ')'
				. '/u', $text, $matches )
			|| preg_match( '/бург|пед/u', $text )
			// i moved following regexes up:
			// there is not only words that look like tatar words, seems i have forgot
			// what i was going to do an added different words:
			// these would go into *tatar* branch
			// i was going to add karta into russian but there is also tatar qarta
			// and other word for karta - xarita in literary language
			// karta and qarta cannot be distinguished without semantics or context
			// added уганда but there is also tatar word уганда, they cannot be
			// distinguished without semantic check. also канар, but i do not add it.
			// also family name кафка - kafka, i leave it as tatar or arabic qafqa
			// also there is tatar word авар
			// also i do not add орган
			// ола should be added here but i leave it
			// because it works as tatar word
			// все was for ТВсе, removed
		) {
			return $this->processWordWithRussianStemCyrlToLat( $text, $beforeThickSuffixes );
		}
		//
		if (
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
			|| preg_match( '/(?<!^[яю].)(?<!^соф)ь(?!е)/u', $text )
			// әдәбият etc
			|| preg_match( '/ият/u', $text )
			// i cannot catch words гаяре, гаебе with signs because there are
			// such words in tatar: бая, сагаеп
			// also they tend to enter russian branch
			// so i catch them before russian words check.
			// other arabic words:
			|| preg_match( '/куф[аи]|^г(ае[пт]|ыйлем|омум)|^рия|вакыйга|расыйх|^гыйсъ/u',
				$text )
			// робагый, корбан, әдәби, мәдәни have gone to tatar branch
			// but i do not need to fix them
			// мөэмин
			|| preg_match( '/өэ/u', $text )
			// || preg_match( '/улла/u', $text )
		) {
			return $this->processWordWithArabicStemCyrlToLat( $text, $beforeThickSuffixes );
		}
		//
		// no signs of russian or arabic words
		// return $this->processWordWithTurkicStemCyrlToLat( $text );
		//
		/*
		// words borrowed from russian which homonyms
		// would be catched as tatar words, but that are rarely used
		// or inactive tatar words made with word-formative suffixes.
		// i catch them as russian words.
		if(
			preg_match( '/^(канаш|барак)/u', $text )
			|| (
				preg_match( '/^канал/u', $text )
				&& 'tele' == $compoundOtherPart
			)
		) {
			return $this->processWordWithRussianStemCyrlToLat( $text );
		}
		if(
			preg_match( '/^газим/u', $text )
		) {
			if ( 'җан' == $compoundOtherPart ) {
				return $this->processWordWithArabicStemCyrlToLat( $text );
			} else {
				return $this->processWordWithRussianStemCyrlToLat( $text );
			}
		}
		if(
			$this->surelyIsATatarVerbWithSuffixes( $text )
			||$this->surelyIsATatarNounWithSuffixes( $text )
		) {
			return $this->processWordWithTurkicStemCyrlToLat( $text );
		}
		// suffixes for verbs
		$sama = 'с(а|ын)|м[аы]'; // са|ма|мы|сын
		$rlpgz = '[ркнлшпм]|й[км]|ң|гыз'; // р|к|н|л|ш|п|м|йк|йм|ң|гыз
		$trnlpn = '[трнлшпңм]';
		$drgan = 'дыр?|ган'; // ды дыр ган
		$trqan = 'тыр?|кан'; // ты тыр кан
		// suffixes for noun-likes
		// ча ла лар ны ның сыз лы мы мын сың быз сыз
		$calaniszlimi='ча|ла|ның?|сыз|лы|мы|сың|быз|сыз';
		// ча ла лар ны ның сыз лы мы мен сең без сез рәк
		$caelaeniszlimi='чә|лә|нең?|сез|ле|ме|сең|без|сез|дер|рәк';
		$qata='ка|та[нй]?|тыр'; // ка та тан тай тыр
		$gada='га|да[нй]?|дыр'; // га да дан дай дыр
		$gaedae='гә|дә[нй]?|дер'; // га да дан дай дер
		$mnbzgz="м|ң|быз|гыз"; // м|ң|быз|гыз
		$mnbizgiz="м|ң|без|гез"; // м|ң|без|гез
		if (
			// tatar words and other words that work well with tatar converter.
			// short thick roots, that can be like beginning of russian words,
			// should be added to special functions below.
			preg_match(
				'/^('
				// short roots with 0-2 next morphemes
				// verbs
				// ку кун кыл кыйл ту кал уз
				// . '(кун?|кый?л|ту|кал|уз|тул|яр|ал)'
				// . '($|'.$drgan.'|'.$sama.'|а|ы('.$rlpgz.')|у)'
				// ау
				// . '|а(у($|д(ы|ар)|ган|'.$sama.')|в((?!густ)а(?!тар|рия)|ы('.$rlpgz.')|у))'
				// кат арт куш аш
				// . '(кат|арт|куш|аш)'
				// . '($|'.$trqan.'|'.$sama.'|а|ы('.$rlpgz.')|у)'
				// кап
				// . '|ка(п($|'.$trqan.'|'.$sama.'|кын)|б(а|ы('.$rlpgz.')|у))'
				// куп
				// . '|ку(п($|т(ы|ар)|кан|'.$sama.')|б(а|ы('.$rlpgz.')|у))'
				// ак ек как як
				// . '|(а|е|ка|я)(к(?!тын)($|'.$trqan.'|'.$sama.')|г(а(?!ф[аә]р)|ы('.$rlpgz.')|у))'
				// курык
				// . '|кур(ык($|ты|кан|'.$sama.')|к(а|ы([ртнлшп]|й[км]|ң|гыз)|у))'
				// кара кана
				// . 'ка[рн](а(?!мзин)($|ган|гыз|у|'.$sama.'|'.$trnlpn.'(?!чык|из))|ый)'
				// noun-likes
				// кат еш арт аш карт
				// . '(кар?т|еш|арт|аш)($|'.$qata.'|'.$calaniszlimi.'|ы|рак)'
				// кап
				// . '|ка(п($|'.$qata.'|'.$calaniszlimi.')|б(ы|рак))'
				// ук як юк
				// . '|[уяю](к($|'.$qata.'|'.$calaniszlimi.')|г(ы|рак))'
				// уку ел ком кар казан кан каз
				// . '|(уку|ел|ком|ка[рнз]|казан)((?!амзин)$|'.$gada.'|'.$calaniszlimi.'|ы|рак)'
				// ал
				// . '|ал($|'.$gada.'|'.$calaniszlimi.'|ды|рак)'
				// юнь
				. 'юн(ь($|'.$gaedae.'|'.$caelaeniszlimi.')|е($|'.$mnbizgiz.'))'
				// к
				. '|калкын|кечкенә[йяею]|коерык|кө[йяею]|комачау'
				. '|кызы[кг]|кыя[кг]|кояш|кавын|кыз|капка'
				. '|коры|коңгырт|карага[йе]|карават|каршы'
				. '|ки[яею]|кына|куан|кәүҗияк|ко[йе]ры[кг]'
				. '|кыса|кыска|кыр|катнаш|казы|кашык|кайбыч'
				. '|котлуг|ка$|кубрат|кытай|кайсы|каенсар|куян'
				// |кы[йяею](?!фәт|м?мәт)
				// у
				. '|урта[кг]|урнаш|утыр|угры|углан|утра[ув]'
				. '|укы|утыз'
				// т
				. '|тука[йяею]|тукран|тагын|ту[йяею]|тө[йяею]|тапкыр|тырыш|тугры'
				. '|тукта|туфрак|тутыр|тавыш|такыя'
				. '|туглан|ти(?!тикака)|ти[яею]|тукы'
				. '|түбән|тамга'
				// б
				. '|(бөе|байра)[кг]|быел|бу[йе]|буын|бавыр|балкы|бакыр'
				. '|бәя|би[еи]'
				// '|болга(?!рия)'
				// ба[кг](?!ира)
				// г
				. '|га($|ча)|гүя|гаян|гыйнвар|гырылда|гына|гагауз'
				// я
				. '|ямь|якын|яш[әиь]|яса|ярдәм|яхшы|яшел|яфрак|ярәш|ялгыз'
				// а
				. '|аер|ассызы[кг]|ака[йяею]|авыл|аза[кг]|авыз|авыргазы'
				. '|азык|акча|аксым'
				// х
				. '|хоку[кг]|хакан|хә[йе]|хайван|халы?к'
				// в
				// . '|ва[кг](?!он|ыйг|уол)'
				. '|ваем'
				// җ
				. '|җамыя[кг]|(җы|җә)[йяею]|җылы'
				// ч
				. '|чө[йяею]|чекерә[йяею]|чә[йе]|чуен|чыелда|чокыр|чы[кг]|чакыр|чуаш|чагыш'
				// е
				. '|ера[кг]|елма[йеяю]'
				// о
				. '|очра|олуг'
				// м
				. '|матбугат|муен|мең'
				// ы
				. '|ышан'
				// с
				. '|сугар|си[яею]|соң|сыек|сө[ейяю]'
				// ю
				. '|юнәл|юеш|юри(?!дик|ст)|юнь|югары|юкә|югал'
				// н
				. '|нократ|нокта|начар|нык'
				// п
				. '|пәрәв|почма[кг]'
				// й
				. '|йогынты|йомша[кг]|шапша[кг]'
				// р
				. '|рәвеш|рәми'
				// и
				. '|и[яюе]'
				// л
				. '|ливәшәү|лаек'
				// ә
				. '|әйе'
				// д
				. '|дәү'
				// э
				. '|э[шз]'
				. ')/u',
				$text )
		) {
			return $this->processWordWithTurkicStemCyrlToLat( $text );
		}
		*/
		if (
			// arabic words and other words that work well with arabic converter
			// preg_match( '/ө/u', $text )
			preg_match(
				'/^('
				// к
				. 'канәгат|кабул|кадәр|канун|каләм|кабил|кыяфәт|кавия|кале[пб]|каюм|куәт|кәгаз'
				. '|корбан|коръән|кабер|катгый|кагъбә|каһарман|касыйм|кыямәт|кыйтга|кита[пб]'
				. '|кыйммәт|кади|каф|кари|кадер|кардәш|каһин|кәеф|кыйсса|кыям|камил|куф'
				// җ
				. '|җавап|җәмгыят|җәмгы|җинаят|җәмәгат'
				// в
				// васыяви
				. '|вәкил|ваз(ый|и)ф|вәкал|вә$|ватан|вакыйг|вафат'
				. '|вилаят|васыя|вакыт|вәли|вәзгыят|в[аә]гыйз'
				// н
				. '|намзәт|нәүбәт|нәстәгъ|нәгыйм|ниһаять|нәзарәт|нәкъ|нияз'
				// м
				. '|мөрәҗ|мөстәкыйль|мәсьәлән'
				. '|мәгар|мәкал|мәгълүм|максат|мәэмүр|маһи|мәдән'
				. '|маэмай|мәшәкат|мәгънә|мәгъдән|мәшгул|мәүлүд'
				// г
				. '|газиз|гадәләт|гамәл|гыйрак|гарә[пб]|госман|гаят|галим|гасыр|гаяр|гает|гайре'
				. '|гае[пб]|гариф'
				. '|гар(?!анти|сия|ри|дин|филд|и)'
				. '|гыйффәт|гыйльфан|гайнан|гайн|гыйният|гашыйк|галләм|горур'
				. '|гаҗә[пб]|гыйл[еь]м|гомер|гомәр|гореф|гомум|гары[кг]|гыйззәт|гаилә|галәм'
				. '|гадәт|гыйсъян|гата|гали|гошер|голәма|габд|гади|гадел'
				. '|гыйбарәт|гафур|гам(?!ет|аш|би)|гыйбад|гафият|гани|газыйм'
				// и
				. '|иҗтим|идарә|игътибар|икътис|игълан|ильяс|инкыйлаб|иганә|исмәгыйл|илфак'
				// т
				. '|тәэмин|тәвәк|тәшвик|тәрәкк|тәнвин|тәэсир|тасвир|тәэссорат|тәнкыйт|тәкъдим'
				. '|табиг|тәгаен|тәмуг|тәкъвим|тәмам'
				// ш
				. '|шөгыл'
				. '|шигыр|шәры[кг]'
				// с
				. '|сәлам|сәясәт|сәнагат|сәгат|сәнгат|сәгъди|сурәт|садыйк'
				. '|сәяси|суфиян|солтан|ситдыйк|сәркати[пб]'
				// п
				. '|пакь'
				// д
				. '|дөнья'
				. '|диван|дәүләт|дәвам|дәһли|дәва|дәвер'
				// ә
				. '|әфган|әнвәр|әхла[кг]|әхмәт|әнкара|әүхәди|әгъза|әүвәл'
				// л
				. '|лөгат'
				. '|лихьян|лалә'
				// р
				. '|рөкъга'
				. '|расыйх|рия|равия|риза|ризык|рәфыйк|риваят|робагый|равил'
				// я
				. '|ягъни|яг[ъа]ф[әа]р|яки|яку[пб]|ядкар|ягъкуб'
				// һ
				. '|һава|һәр|һәла[кг]'
				// б
				. '|бәян|бәләгат|бәгыр|балигъ|бәлигъ|бәлагат|бәһа|бәла|бакый|билал'
				// х
				. '|хөсәен'
				. '|хикәя|халәт|хәрабә|хак(?!ас)|ханә|хәрәкә'
				// ф
				. '|фагыйләт|фигыл|фирка|фидакар|фирдәүс'
				// ш
				. '|шигый|шагыйр|шәфи[кг]'
				// з
				. '|зәгыйф'
				. ')/u',
				$text )
		) {
			return $this->processWordWithArabicStemCyrlToLat( $text, $beforeThickSuffixes );
		}
		// words borrowed from russian language
		// return $this->processWordWithRussianStemCyrlToLat( $text );
		return $this->processWordWithTurkicStemCyrlToLat( $text );
	} // convertLowercasedTatarWordCyrlToLat

	/*
	function surelyIsATatarVerbWithSuffixes( $text ) {
		// there are short roots. long roots, that cannot
		// be like beginning of russian word, should be added above.
		$text = preg_replace( ['/я/u', '/е/u', '/ю/u'], ['йа', 'йы', 'йу'], $text );
		// echo $text;
		$roots = [
			'кун?', 'кый?л', 'тул?', 'ка[лтп]', 'уз', 'йар', 'а[лш]', 'укы?', 'арт', 'у', 'оч',
			'куш', 'с?а[ув]', 'тай(ан)?', 'йугал', 'ба[рт]', 'тул?', 'кама', 'сал', 'бул',
			'кы[рй]', '(баш|кот)кар',
			'(ка|а|су|йы|йа|куры?|ба|йабы|та|йо)[кг]',
			'(йокл|кар|тукт|макт)[аы]',
			'болга', 'кайт', 'кайыр', 'тара', 'суз', 'йом',
			'тарка', 'бойы', 'буйа', 'йаз', 'айа', 'айны', 'ойы', 'ох?ша', 'ойал',
			'йат', 'от', 'кой', 'куй', 'тарт', 'тор', 'тот', 'уй', '(та|ку)[пб]', 'куй',
		];
		foreach( $roots as $root ) {
			if( preg_match( '/^'.$root.'/u', $text, $matches ) ) {
				$parts = preg_split( '/(?<=^'.$matches[0].')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
				if( !isset( $parts[1] ) ) {
					if( preg_match( '/б$/u', $parts[0] ) ) {
						return false;
					}
					return true;
				} else {
					// echo $parts[1];
					if( $this->surelyAreValidSuffixesAfterThickVerb( $parts ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	function surelyIsATatarNounWithSuffixes( $text ) {
		// there are short roots. long roots, that cannot
		// be like beginning of russian word, should be added above.
		$text = preg_replace( ['/я/u', '/е/u', '/ю/u'], ['йа', 'йы', 'йу'], $text );
		$roots = [
			'ко[тм]', 'матур', 'ун', 'тугыз', 'алты?', 'алма', 'ал', 'алд', 'савыт',
			'урман', 'а[шй]', 'йалтыр', 'кар?т', 'йы[лш]', 'арка', 'аркан', 'корт',
			'арт', 'ка[рнз]', 'казан', 'каза(къ?|г)', 'болгар', 'җай', 'корал',
			'ка[пб]', 'кай', 'багъ?', 'бакыр', 'йар', 'кул', 'са[нл]', 'баш', 'кае[нш]',
			'кабат', 'комар', 'канат', 'карта', 'койы', 'каты', 'су',
			'уй', 'тары', 'бай', 'йаз', 'айык', 'ойа', 'саба',
			'(та|йа)[ув]',
			'(у|тайа|йа|йу|чу|ва|ча|айы|ту|ны|кала|кае|тайа|са(ба)?|бу)[кг]'
		];
		foreach( $roots as $root ) {
			if( preg_match( '/^'.$root.'/u', $text, $matches ) ) {
				$parts = preg_split( '/(?<=^'.$matches[0].')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
				if( !isset( $parts[1] ) ) {
					return true;
				} else {
					// echo $parts[1];
					if( $this->surelyAreValidSuffixesAfterThickNoun( $parts ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}
	*/

	function tryGetRootOfThickWord( $text, &$parts ) {
		$parts = [];
		$len = mb_strlen( $text );
		$len = min( $len, 15 );
		for( $i = $len; $i >= 2; $i-- ) {
			$parts[0] = mb_substr( $text, 0, $i );
			$parts[1] = mb_substr( $text, $i );
			if(
				preg_match( '/[әөү]/u', $parts[1] )
			) {
				break;
			}
			// $rootType = $this->isValidDivision( $parts );
			//
			$saved_parts = $parts;
			$parts[1] = strtr(
				$parts[1],
				['я'=>'йа', 'ев'=>'йэв', 'е'=>'йы', 'ю'=>'йу']
			);
			$noun2 = preg_replace( '/г$/u', 'к', $parts[0] );
			$noun2 = preg_replace( '/б$/u', 'п', $noun2 );
			if(
				isset( $this->wordsWithFalseTatarSuffixes[$parts[0]] )
				|| isset( $this->wordsWithFalseTatarSuffixes[$noun2] )
			) {
				if( $i == $len ) {
					return 0;
				}
				if(
					$this->surelyAreValidSuffixesAfterThickNoun( $parts )
				) {
					return $this->wordsWithFalseTatarSuffixes[$parts[0]];
				}
			}
			$parts = $saved_parts;
			if(
				preg_match( '/^ев/u', $parts[1] )
				&& preg_match( '/([аоуыияёюэе])$/u', $parts[0], $matches )
			) {
				if( $matches[1] != 'и' ) {
					$parts[0] .= 'й';
				}
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
				$parts[1] = strtr(
					$parts[1],
					['я'=>'йа', 'ев'=>'йэв', 'е'=>'йы', 'ю'=>'йу']
				);
				if( isset( $this->wordsWithFalseTatarSuffixes[$parts[0]] ) ) {
					if(
						$this->surelyAreValidSuffixesAfterThickNoun( $parts )
					) {
						return $this->wordsWithFalseTatarSuffixes[$parts[0]];
					}
				}
			}
			$parts = $saved_parts;
			if(
				preg_match( '/^е/u', $parts[1] )
				// юбилее
				// && preg_match( '/[аоуыияёюэе]$/u', $parts[0] )
				// && preg_match( '/[аоуы]$/u', $parts[0] )
				&& preg_match( '/^[^әөү]*[аоуые]$/u', $parts[0] )
			) {
				$parts[0] .= 'й';
				$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
				$parts[1] = strtr(
					$parts[1],
					['я'=>'йа', 'ев'=>'йэв', 'е'=>'йы', 'ю'=>'йу']
				);
				if( isset( $this->wordsWithFalseTatarSuffixes[$parts[0]] ) ) {
					if(
						$this->surelyAreValidSuffixesAfterThickNoun( $parts )
					) {
						return $this->wordsWithFalseTatarSuffixes[$parts[0]];
					}
				}
			}
			$verb = $parts[0];
			$verb = preg_replace( '/б$/u', 'п', $verb );
			$verb = preg_replace( '/г$/u', 'к', $verb );
			$verb = preg_replace( '/ы$/u', 'а', $verb );
			if(
				// !preg_match( '/[әөүеэи]/u', $parts[0] )
				isset( $this->thickTatarVerbs[$verb] )
				&& $this->surelyAreValidSuffixesAfterThickVerb( $parts )
			) {
				return 2;
			}
			// return 0;
			//
			/*
			if( $rootType ) {
				// return $parts;
				return $rootType;
			}
			*/
		}
		for( $i = 2; $i <= $len; $i++ ) {
			$parts[0] = mb_substr( $text, 0, $i );
			$parts[1] = mb_substr( $text, $i );
			if(
				preg_match( '/[әөү]/u', $parts[1] )
				// need to catch мәдән|ие,
				// leaving юбиле|ендагы
			) {
				continue;
			}
			$parts[1] = strtr(
				$parts[1],
				['я'=>'йа', 'ев'=>'йэв', 'е'=>'йы', 'ю'=>'йу']
			);
			if(
				$this->surelyAreValidSuffixesAfterThickNoun( $parts )
			) {
				return 1;
			}
			if(
				preg_match( '/^ев/u', $parts[1] )
				&& preg_match( '/([аоуыияёюэе])$/u', $parts[0], $matches )
			) {
				if( $matches[1] != 'и' ) {
					$parts[0] .= 'й';
				}
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
				if(
					$this->surelyAreValidSuffixesAfterThickNoun( $parts )
				) {
					return 1;
				}
			}
			if(
				preg_match( '/^е/u', $parts[1] )
				// юбилее
				// && preg_match( '/[аоуыияёюэе]$/u', $parts[0] )
				// && preg_match( '/[аоуы]$/u', $parts[0] )
				&& preg_match( '/^[^әөү]*[аоуые]$/u', $parts[0] )
			) {
				$parts[0] .= 'й';
				$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
				if(
					$this->surelyAreValidSuffixesAfterThickNoun( $parts )
				) {
					return 1;
				}
			}
		}
		$parts[0]=$text;
		$parts[1]='';
		// return $parts;
		return 0;
	}

	function __construct(
		$langobj, $maincode, $variants = [],
		$variantfallbacks = [], $flags = [], $manualLevel = []
	) {
		parent::__construct(
			$langobj, $maincode, $variants, $variantfallbacks, $flags, $manualLevel
		);
		$this->thickTatarVerbs = array_fill_keys(
			[
				'кун', 'ку', 'кыйл', 'кыл', 'кыл', 'тул', 'ту', 'кал', 'кат', 'кап', 'уз', 'йар',
				'ал', 'аш', 'укы', 'ук', 'арт', 'у', 'оч',
				'куш', 'сау', 'ау', 'тайан', 'тай', 'йугал', 'бар', 'бат', 'тул', 'ту', 'кама',
				'сал', 'бул',
				'кыр', 'кый', 'башкар', 'коткар',
				'как', 'ак', 'сук', 'йык', 'йак', 'курык', 'бак', 'йабык', 'так', 'йок',
				'йокла', 'кара', 'тукта', 'макта',
				'болга', 'кайт', 'кайыр', 'тара', 'суз', 'йом',
				'тарка', 'бойы', 'буйа', 'йаз', 'айа', 'айны', 'ойы', 'охша', 'оша', 'ойал',
				'йат', 'от', 'кой', 'куй', 'тарт', 'тор', 'тот', 'уй', 'тап', 'куп', 'куй',
			],
			1
		);
		$this->wordsWithFalseTatarSuffixes = array_fill_keys(
			[
				'волга', 'шпага', 'лига', 'омега', 'синагога', 'фольга', 'агалега', 'тонга',
				'рига', 'брига', 'вакыйга',
				'драматург', 'ток', 'физик', 'округ', 'материк', 'классик', 'кубок', 'каталог',
				'духовное', 'октябрьское', 'предприятие', 'раменское', 'иҗтимагый', 'яссы',
				'шигый', 'мөгаен', 'табигый', 'яхшы', 'гаеп', 'нәгыйм',
				'барак',
				'гуам', 'имам',
				'елмай', 'маэмай', 'мөлаем',
				'горилла', 'игълан', 'бәла', 'әхлак', 'һәлак',
				'валдай', 'команда', 'канада',
				'яков',
				'калий',
				'щётка', 'электричка', 'вышка', 'клетчатка', 'золотоношка', 'оливка', 'куртка',
				'кепка', 'перчатка', 'чукотка', 'обкладка', 'аляска', 'небраска', 'вентреска',
				'сосновка', 'градовка', 'саминовка', 'датировка', 'кодировка', 'дозировка',
				'петропавловка', 'любовка', 'покровка', 'новониколаевка',
			],
			3
		);
	}

	/*
	function isValidDivision( $parts ) {
		// $parts[1] = preg_replace(
			// ['/я/u', '/ев/u', '/е/u', '/ю/u'],
			// ['йа', 'йев', 'йы', 'йу'],
			// $parts[1]
		// );
		$parts[1] = strtr(
			$parts[1],
			['я'=>'йа', 'ев'=>'йэв', 'е'=>'йы', 'ю'=>'йу']
		);
		if(
			'' == $parts[1]
			||
			// if not thick suffixes, exit.
			// е, и, э are not checked here, because:
			// they maybe of thick suffixes:
			// юбилее - юбилей+ы,
			// копия - коп+ий+а.
			// э - имамийэв.
			// ий+а here maybe really not correct, but it should work,
			// minimizing root part.
			preg_match( '/[әөү]/u', $parts[1] )
			||
			isset( $this->russianWordsEndingWithKa[$parts[0]] )
			&& preg_match( '/^ка/u', $parts[1] )
			||
			preg_match(
				'/^(вол|шпа|ли|оме|синаго|фоль|агале|тон|ри|бри|вакый)$/u',
				$parts[0]
			)
			&& preg_match( '/^га/u', $parts[1] )
			||
			preg_match( '/^(драматур|то|физи|окру|матери|класси|кубо|катало)$/u', $parts[0] )
			&& preg_match( '/^гы/u', $parts[1] )
			||
			preg_match(
				'/^('
				. 'духовной|октябрьской|предприятий|раменской|иҗтимаг|ясс|яхш|гай|шиг|нәг|мөгай'
				. '|табиг'
				. ')$/u',
				$parts[0]
			)
			&& preg_match( '/^ы/u', $parts[1] )
			||
			preg_match( '/^(ба)$/u', $parts[0] )
			&& preg_match( '/^рак/u', $parts[1] )
			||
			preg_match( '/^(гуа|има)$/u', $parts[0] )
			&& preg_match( '/^м/u', $parts[1] )
			||
			preg_match( '/^(елм|маэм|им|мөг|мөл)$/u', $parts[0] )
			&& preg_match( '/^а/u', $parts[1] )
			||
			preg_match( '/^(горил|игъ|бә|әх|мө|һә)$/u', $parts[0] )
			&& preg_match( '/^ла/u', $parts[1] )
			||
			preg_match( '/^(вал|коман|кана)$/u', $parts[0] )
			&& preg_match( '/^да/u', $parts[1] )
			// ||
			// preg_match(
				// '/(сосн|град|самин|датир|кодир|дозир|петропавл|люб|покр)/u',
				// $parts[0]
			// )
			// && preg_match( '/^овка/u', $parts[1] )
			||
			preg_match( '/^(як)$/u', $parts[0] )
			&& preg_match( '/^ов/u', $parts[1] )
			||
			preg_match( '/^(кал)$/u', $parts[0] )
			&& preg_match( '/^ий/u', $parts[1] )
		) {
			return 0;
		}
		if(
			$this->surelyAreValidSuffixesAfterThickNoun( $parts )
		) {
			return 1;
		}
		$verb = $parts[0];
		$verb = preg_replace( '/б$/u', 'п', $verb );
		$verb = preg_replace( '/г$/u', 'к', $verb );
		$verb = preg_replace( '/ы$/u', 'а', $verb );
		if(
			// !preg_match( '/[әөүеэи]/u', $parts[0] )
			isset( $this->thickTatarVerbs[$verb] )
			&& $this->surelyAreValidSuffixesAfterThickVerb( $parts )
		) {
			return 2;
		}
		return 0;
	}
	*/

	function surelyAreValidSuffixesAfterThickVerb( $parts ) {
		$awVerbAv = false;
		$awVerbAu = false;
		$aoVerb = false;
		$rVerb = false;
		$qVerbq = false;
		$qVerbg = false;
		$pVerbp = false;
		$pVerbb = false;
		$stcsVerb = false;
		$zylmnuVerb = false;
		if( preg_match(  '/[ая]в$/u', $parts[0] ) ) {
			$awVerbAv = true; // verb ending with av
		} elseif( preg_match(  '/[ая]у$/u', $parts[0] ) ) {
			$awVerbAu = true; // verb ending with au
		} elseif( preg_match(  '/[аяы]$/u', $parts[0] ) ) {
			$aoVerb = true; // verb ending with а or ы
		} elseif( preg_match(  '/р$/u', $parts[0] ) ) {
			$rVerb = true; // verb ending with р
		} elseif( preg_match(  '/к$/u', $parts[0] ) ) {
			$qVerbq = true; // verb ending with к
		} elseif( preg_match(  '/г$/u', $parts[0] ) ) {
			$qVerbg = true; // verb ending with к
		} elseif( preg_match(  '/п$/u', $parts[0] ) ) {
			$pVerbp = true; // verb ending with п
		} elseif( preg_match(  '/б$/u', $parts[0] ) ) {
			$pVerbb = true; // verb ending with п
		} elseif( preg_match(  '/[стчш]$/u', $parts[0] ) ) {
			$stcsVerb = true; // verb ending with с/т/ч/ш
		} else {
			$zylmnuVerb = true; // verb ending with з/й/л/м/н/у/ю
		}
		$text = $parts[1];
		// $qVerbq кы кыч кан - - - - сын ты
		// $pVerbp кы кыч кан - - - - сын ты
		// $awVerbAu гы гыч ган - - - - сын ды
		// $aoVerb гы гыч ган гыз р у вы сын ды
		// $rVerb гы гыч ган ыгыз ыр у уы сын ды
		// $zylmnuVerb гы гыч ган ыгыз ыр у уы сын ды
		// $stcsVerb кы кыч кан ыгыз ыр у уы сын ты
		// $awVerbAv - - - ыгыз ыр у уы - -
		// $qVerbg - - - ыгыз ыр у уы - -
		// $pVerbb - - - ыгыз ыр у уы - -
		if(
			// салына
			(
				!( $aoVerb || $pVerbp || $qVerbq || $awVerbAu )
				||
				$parts[0] == 'курк'
			)
			&& preg_match( '/^ы[нл]/u', $text, $matches )
			||
			// ашала, таныла
			$aoVerb
			&& preg_match( '/^[нл]/u', $text, $matches )
			||
			// калмый
			// ма is checked below
			!( $pVerbb || $qVerbg || $awVerbAv )
			&& preg_match( '/^мы/u', $text, $matches )
			||
			( $stcsVerb || $pVerbp || $qVerbq )
			&& preg_match( '/^кала/u', $text, $matches )
			||
			( $zylmnuVerb || $aoVerb || $rVerb || $awVerbAu )
			&& preg_match( '/^гала/u', $text, $matches )
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				// return $this->areValidSuffixesAfterAlon( $suffixes_parts[1] );
				$suffixes_parts[2] = $parts[0]; // will be previous of previous morpheme
				return $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts );
			}
			return true;
		}
		if(
			( $zylmnuVerb || $awVerbAv || $pVerbb || $qVerbg || $rVerb || $parts[0] == 'курк' )
			&& preg_match( '/^ыш/u', $text, $matches )
			||
			$aoVerb
			&& preg_match( '/^ш/u', $text, $matches )
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				// if( $this->areValidSuffixesAfterAlosVerb( $suffixes_parts[1] ) ){
				if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ){
					return true;
				}
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		if(
			$parts[0] == 'ау' // awVerbAu
			&& preg_match( '/^дар/u', $text, $matches )
			||
			// awVerbAu except ау: сау, яу
			$awVerbAu && $parts[0] != 'ау'
			&& preg_match( '/^дыр/u', $text, $matches )
			||
			$parts[0] == 'там' // $zylmnuVerb
			&& preg_match( '/^ыз/u', $text, $matches )
			||
			// zylmnuVerb including там
			$zylmnuVerb
			&& preg_match( '/^дыр/u', $text, $matches )
			||
			preg_match( '/^оч|ка[чт]$/u', $parts[0] ) // stcsVerb
			&& preg_match( '/^ыр/u', $text, $matches )
			||
			// stcsVerb including оч, кач
			$stcsVerb
			&& preg_match( '/^тыр/u', $text, $matches )
			||
			$parts[0] == 'куп' // pVerbp
			&& preg_match( '/^тар/u', $text, $matches )
			// ||
			// moved down
			// $parts[0] == 'куб' // pVerbb
			// && preg_match( '/^ар/u', $text, $matches )
			||
			// including куп
			( $pVerbp || $qVerbq )
			&& preg_match( '/^тыр/u', $text, $matches )
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				// return $this->areValidSuffixesAfterAwdar( $suffixes_parts[1] );
				return $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts );
			}
			return true;
		}
		if(
			$parts[0] == 'куб' // pVerbb
			&& preg_match( '/^ар/u', $text, $matches )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ){
					return true;
				}
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		if(
			(
				$aoVerb || $rVerb
				// югалт
				|| $parts[0] == 'йугал'
				// каралт
				|| (
					$parts[0] == 'л'
					&& isset( $parts[2] )
					&& $parts[2] == 'кара'
				)
				// матурайт
				|| $parts[0] == 'ай'
			)
			&& preg_match( '/^т/u', $text, $matches )
			||
			$parts[0] == 'курк'
			&& preg_match( '/^ыт/u', $text, $matches )
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				return $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts );
			}
			return true;
		}
		// мы is checked above
		if(
			!( $awVerbAv || $pVerbb || $qVerbg )
			&& preg_match( '/^ма/u', $text )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				// return $this->areValidSuffixesAfterAwmaVerb( $suffixes_parts[1] );
				if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ){
					return true;
				}
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		if(
			!( $awVerbAv || $pVerbb || $qVerbg )
			&& preg_match( '/^сын/u', $text )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^...)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		if(
			(
				!( $aoVerb || $pVerbp || $qVerbq )
				|| $parts[0] == 'курк'
			)
			&& preg_match( '/^а/u', $text )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^.)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
					return true;
				}
				if(
					preg_match( '/^ар/u', $text, $matches )
					&& preg_match( '/[^лр]$/u', $suffixes_parts[0] )
					// with help of http://kitap.net.ru/hisamova1-8.php
					|| preg_match( '/^а[кг]/u', $text, $matches )
					|| preg_match( '/^ача[кг]/u', $text, $matches )
				) {
					$suffix = $matches[0];
					$suffixes_parts = preg_split(
						'/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY
					);
					if( !isset( $suffixes_parts[1] ) ) {
						return true;
					}
					if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ){
						return true;
					}
				}
				return false;
			}
			return true;
		}
		if(
			$aoVerb
			&& preg_match( '/^й/u', $text )
		) {
			$suffixes_parts = preg_split( '/(?<=^.)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
					return true;
				}
				if( preg_match( '/^йы?[км]/u', $text, $matches ) ) {
					$suffix = $matches[0];
					$suffixes_parts = preg_split(
						'/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY
					);
					if( !isset( $suffixes_parts[1] ) ) {
						return true;
					}
					if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
						return true;
					}
				}
				if( preg_match( '/^йачак/u', $text, $matches ) ) {
					// $suffix = $matches[0];
					$suffixes_parts = preg_split(
						'/(?<=^.....)/u', $text, 2, PREG_SPLIT_NO_EMPTY
					);
					if( !isset( $suffixes_parts[1] ) ) {
						return true;
					}
					if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
						return true;
					}
				}
				return false;
			}
			return true;
		}
		// ар is already checked
		if(
			!( $awVerbAv || $pVerbb || $qVerbg )
			&& preg_match( '/^(сын|ма)/u', $text, $matches )
			||
			// ыш, ак are already checked
			(
				!( $awVerbAu || $pVerbp || $qVerbq || $aoVerb )
				|| $parts[0] == 'курк'
			)
			&& preg_match( '/^ы([ркпң]|м(та)?|гыз|й[мк])/u', $text, $matches )
			||
			$aoVerb
			&& (
				// йм, йк are already checked
				preg_match( '/^([мкпңш]|г(а[нк]|ы(н|ры|[зч]?)))/u', $text, $matches )
				||
				preg_match( '/^р/u', $text, $matches )
				&& $parts[0] != 'ма'
				// ||
				// moved down
				// preg_match( '/^с/u', $text, $matches )
				// && $parts[0] == 'ма'
			)
			||
			// в is checked below
			( !( $awVerbAu || $pVerbp || $qVerbq ) || $parts[0] == 'ук' || $parts[0] == 'курк' )
			&& preg_match( '/^у/u', $text, $matches )
			||
			( $stcsVerb || $pVerbp || $qVerbq )
			&& preg_match( '/^к(ы(н|ры|ч?)|а[нк])/u', $text, $matches )
			||
			// $aoVerb is checked above
			( $zylmnuVerb || $awVerbAu || $rVerb )
			&& preg_match( '/^г(ы(н|ры|ч?)|а[нк])/u', $text, $matches )
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				$suffixes_parts[2] = $parts[0];
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		if (
			$aoVerb
			&& preg_match( '/^в/u', $text, $matches )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^.)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ){
					return true;
				}
				// ялтыравык
				if ( preg_match( '/^вы[кг]/u', $text, $matches ) ) {
					$suffixes_parts = preg_split( '/(?<=^...)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
					if( isset( $suffixes_parts[1] ) ) {
						return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
					}
					return true;
				}
			}
			return false;
		}
		if(
			!$awVerbAv
			&& preg_match( '/^са/u', $text )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				return $this->areValidSuffixesAfterQalsa( $suffixes_parts[1] );
			}
			return true;
		}
		if(
			preg_match( '/^с/u', $text, $matches )
			&& $parts[0] == 'ма'
		){
			$suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				// $suffixes_parts[2] = $parts[0];
				if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
					return true;
				}
			}
			return true;
		}
		if(
			( $stcsVerb || $pVerbp || $qVerbq )
			&& preg_match( '/^ты/u', $text )
			||
			( $zylmnuVerb || $rVerb || $aoVerb || $awVerbAu )
			&& preg_match( '/^ды/u', $text )
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->areValidSuffixesAfterQalsa( $suffixes_parts[1] ) ) {
					return true;
				}
				// калдык
				if(
					( $stcsVerb || $pVerbp || $qVerbq )
					&& preg_match( '/^тык/u', $text )
					||
					( $zylmnuVerb || $rVerb || $aoVerb || $awVerbAu )
					&& preg_match( '/^дык/u', $text )
				) {
					$suffixes_parts = preg_split( '/(?<=^...)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
					if( isset( $suffixes_parts[1] ) ) {
						return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
					}
					return true;
				}
				return false;
			}
			return true;
		}
		if( preg_match( '/^быз/u', $text ) && $parts[0] == 'ма' ) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split( '/(?<=^...)/u', $text, 2, PREG_SPLIT_NO_EMPTY );
			if( isset( $suffixes_parts[1] ) ) {
				return $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts );
			}
			return true;
		}
		return false;
	} // function surelyAreValidSuffixesAfterThickVerb( $parts ) {

	function surelyAreValidSuffixesAfterThickNoun( $parts ) {
		$text = $parts[1];
		if(
			// лы is moved down
			// мы is moved down
			preg_match(
				'/^(ча|ны(ң|кы)?|сыз|лы[кг]|лар|мын|сың|быз|дыр|рак|чы[кг]?)/u',
				$text, $matches
			)
			||
			preg_match(
				'/^а[ув]/u',
				$text, $matches
			)
			&& preg_match( '/^(алт|тугыз|ун|утыз|кырыг|алтмыш|туксан)$/u', $parts[0] )
			||
			preg_match( '/[нм]$/u', $parts[0] )
			&& preg_match(
				'/^на[рн]/u',
				$text, $matches
			)
			||
			// китабым, китабың, китабыгыз, китабыбыз, китаплары
			// лар is checked above
			// китабы is moved down
			// not after алма, шырпы, китап, ак (but after китаб, аг)
			!preg_match( '/[аыпк]$/u', $parts[0] )
			// not after санау (but after санав)
			&& !(
				'у' == $parts[0]
				&& preg_match( '/а$/u', $parts[2] )
			)
			// not after алтау (but after алтав)
			&& !(
				'ау' == $parts[0]
			)
			&& !preg_match( '/^(мын|сың|быз|сыз|дыр)$/u', $parts[0] )
			&& preg_match(
				'/^ы([нмң]|[гб]ыз)/u',
				$text, $matches
			)
			||
			// алмасы, алмам, алмаң, алмабыз, алмагыз, алмалары
			// быз, лар are checked above
			preg_match( '/[аы]$/u', $parts[0] )
			&& !preg_match( '/^с?ы$/u', $parts[0] )
			&& preg_match( '/^(сын?|м|ң|гыз)/u', $text, $matches )
			||
			// this function is to check tatar words, so бвгд
			// for words like клуб are not included
			// apr25: now i have included them to be able to check suffixes
			// after russian words
			// also о for го etc
			preg_match( '/[бвгдкпстфхһцчшщ][ьъ]?$/u', $parts[0] )
			&& preg_match( '/^(к[аы]|та[нйш]?|тыр)/u', $text, $matches )
			||
			preg_match( '/([оаеёийуыэюя]|[жҗзлмнңр][ьъ]?)$/u', $parts[0] )
			&& preg_match( '/^(г[аы]|да[нйш]?|дыр)/u', $text, $matches )
			// ||
			// preg_match( '/^с?ын$/u', $parts[0] )
			// && preg_match( '/^ың/u', $text, $matches )
			||
			$parts[0] == 'кай'
			&& preg_match( '/^ан/u', $text, $matches )
			||
			preg_match( '/(^кай|ы[мңн])$/u', $parts[0] )
			&& !preg_match( '/^(мын|сың|быз|сыз|дыр)$/u', $parts[0] )
			&& preg_match( '/^а/u', $text, $matches )
			||
			preg_match( '/^[оэ]ва?/u', $text, $matches )
			// not рәф+ы+йк+ов
			&& !preg_match( '/^йк$/u', $parts[0] )
			// ||
			// preg_match( '/^а/u', $text, $matches )
			// i comment this out because i cannot easily convert arabic
			// words like рөкъга properly if "а" suffix is separated
			||
			preg_match( '/^ий/u', $text, $matches )
		) {
			$suffixes_parts = preg_split(
				'/(?<=^'.$matches[0].')/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( !isset( $suffixes_parts[1] ) ) {
				// last suffix
				return true;
			}
			// not last suffix
			if(
				$this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts )
			) {
				return true;
			}
			return false;
		}
		if( preg_match( '/^мы$/u', $text, $matches ) ) {
			// if мы is not last it is not valid
			return true;
		}
		if(
			preg_match(
				'/^лы/u',
				$text
				// , $matches
			)
		) {
			$suffixes_parts = preg_split(
				'/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( !isset( $suffixes_parts[1] ) ) {
				// last suffix
				return true;
			}
			// not last suffix
			if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
				return true;
			}
			if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ) {
				return true;
			}
			// if not valid then check next match
			return false;
		}
		if(
			// китабы
			// not after алма, шырпы, китап, ак (but after китаб, аг)
			!preg_match( '/[аыпк]$/u', $parts[0] )
			// not after санау (but after санав)
			&& !(
				'у' == $parts[0]
				&& preg_match( '/а$/u', $parts[2] )
			)
			// not after алтау (but after алтав)
			&& !(
				'ау' == $parts[0]
			)
			&& !preg_match( '/^(мын|сың|быз|сыз|дыр)$/u', $parts[0] )
			&& 0 < preg_match(
				'/^ы/u',
				$text
			)
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split(
				'/(?<=^.)/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( isset( $suffixes_parts[1] ) ) {
				if( $this->surelyAreValidSuffixesAfterThickNoun( $suffixes_parts ) ) {
					return true;
				}
				if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ) {
					return true;
				}
				if(
					preg_match( '/^ы[кг]/u', $text )
					&& $parts[0] == 'кан'
				) {
					// $suffix = $matches[0];
					$suffixes_parts = preg_split(
						'/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY
					);
					if( isset( $suffixes_parts[1] ) ) {
						// return $this->areValidSuffixesAfterAwdar( $suffixes_parts[1] );
						return $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts );
					}
					return true;
				}
				return false;
			}
			return true;
		}
		// ы is moved up
		// лы is moved up
		if(
			preg_match( '/^ла/u', $text, $matches )
			&& !preg_match( '/^ы?м$/u', $parts[0] )
			||
			preg_match( '/^р/u', $text, $matches )
			&& (
				$parts[0] == 'якты'
				|| $parts[0] == 'кыска'
				|| $parts[0] == 'юка'
			)
		) {
			$suffix = $matches[0];
			$suffixes_parts = preg_split(
				'/(?<=^'.$suffix.')/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( isset( $suffixes_parts[1] ) ) {
				// return $this->areValidSuffixesAfterAwdar( $suffixes_parts[1] );
				return $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts );
			}
			return true;
		}
		if( preg_match( '/^ай/u', $text, $matches ) ) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split(
				'/(?<=^..)/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( !isset( $suffixes_parts[1] ) ) {
				return true;
			}
			if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ) {
				return true;
			}
		}
		if(
			preg_match( '/^а/u', $text, $matches )
			&& (
				$parts[0] == 'аш'
				|| $parts[0] == 'кан'
				|| $parts[0] == 'йалтыр'
				|| $parts[0] == 'калтыр'
				|| $parts[0] == 'йар'
				|| $parts[0] == 'сан'
			)
		) {
			// $suffix = $matches[0];
			$suffixes_parts = preg_split(
				'/(?<=^.)/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( !isset( $suffixes_parts[1] ) ) {
				return true;
			}
			if( $this->surelyAreValidSuffixesAfterThickVerb( $suffixes_parts ) ) {
				return true;
			}
		}
		return false;
	} // function surelyAreValidSuffixesAfterThickNoun( $parts ) {

	function areValidSuffixesAfterQalsa( $text ) {
		// this is used for forms like qalsa and qaldo
		if( preg_match( '/^[мч]ы$/u', $text, $matches ) ) {
			// in fact co is only used/possible after qalsa form.
			// i can check it here, but i cannot check it easily in areValidSuffixesAfterQalsam.
			// checking it is not very needed, so let it be allowed also after qaldo form.
			return true;
		}
		if( preg_match( '/^м/u', $text, $matches ) ) {
			$suffixes_parts = preg_split(
				'/(?<=^'.$matches[0].')/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( isset( $suffixes_parts[1] ) ) {
				return $this->areValidSuffixesAfterQalsam( $suffixes_parts[1] );
			}
			return true;
		}
		if( preg_match( '/^(к|ң|гыз|лар)/u', $text, $matches ) ) {
			$suffixes_parts = preg_split(
				'/(?<=^'.$matches[0].')/u', $text, 2, PREG_SPLIT_NO_EMPTY
			);
			if( isset( $suffixes_parts[1] ) ) {
				return $this->areValidSuffixesAfterQalsam( $suffixes_parts[1] );
			}
			return true;
		}
		return false;
	}

	function areValidSuffixesAfterQalsam( $text ) {
		if( preg_match( '/^[мч]ы$/u', $text ) ) {
			return true;
		}
		return false;
	}

	function processWordWithRussianStemCyrlToLat( $text, $beforeThickSuffixes = false ) {
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
		//
		// search for tatar endings
		//
		/*
		// ...ия - iä
		// except предприятие, тамиянг
		$parts = preg_split( '/(?<!^там)(?=ия)(?!иятие)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		// ^ it is easier to pass the ia suffix into tatar part
		// this makes aeratsiä, i would like to write aeratsiya, but
		// aeratsiä is also ok
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		// аллеясе
		$parts = preg_split( '/(?<=е)(?=я(се|.+[әүң]))/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		}
		*/
		//
		// $parts = $this->tryGetRootOfThickWord( $text );
		// if( $parts[1] != '' ) {
			// return $this->convertRussianWordWithTatarEndingCyrlToLat( $parts );
		// }
		//
		// tatar suffixes are not found
		// $text = $this->convertSimpleRussianWordFromCyrillicToLatin( $text );
		return $this->convertSimpleRussianWordFromCyrillicToLatin( $text, $beforeThickSuffixes );
	} // processWordWithRussianStemCyrlToLat

	function processWordWithArabicStemCyrlToLat( $text, $beforeThickSuffixes = false ) {
		// process words with arabic stem
		/*
		// family name with ov(a)
		// нәгыймов
		$parts = preg_split( '/(?=ова?)/u', $text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertArabicWordWithRussianEndingCyrlToLat( $parts );
		}
		// family name with ev(a)
		// рәмиев, вәлиев, ...галиев, ...хәев
		if ( preg_match( '/.+ева?/u', $text ) ) {
			$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?)/u', 'йе', $text );
			$parts = preg_split( '/(?=ева?)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			return $this->convertArabicWordWithRussianEndingCyrlToLat( $parts );
		}
		*/
		/*
		// ...ла,лау,лама,лаучы,...
		// тасвирлау but not in мөлаем
		// better not to make го|ләма
		$parts = preg_split( '/(?=л[аә]($|([уү]|м[аә]|яч[аә]к)(ч[ые])?))(?!л(аем|әма|амә))'
			. '(?<!^бә)(?<!ул)(?<!^мәка)/u',
			$text, null, PREG_SPLIT_NO_EMPTY );
		if ( count( $parts ) == 2 ) {
			return $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
		}
		*/
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
		// } elseif ( count( $parts ) > 2 ) {
			// $parts[1] = implode( array_slice( $parts, 1 ) );
			// array_splice( $parts, 2 );
			// $text = $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
		// }
		// ...е
		// икътисадые
		/*
		if ( preg_match( '/ы(?=(е$|ен))/u', $text ) ) {
			$parts = preg_split( '/(?<=ы)(?=(е$|ен))/u',
				$text, null, PREG_SPLIT_NO_EMPTY );
			$parts[0] .= 'й';
			if ( preg_match( '/[әөеүи][^аоыуиәөеүи]+ы(е$|ен)/u', $text ) ) {
				$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			} else {
				$parts[1] = 'ы' . mb_substr( $parts[1], 1 );
			}
			return $this->convertArabicWordWithTatarEndingCyrlToLat( $parts );
		}
		*/
		// suffixes are not found
		return $this->convertSimpleArabicWordFromCyrillicToLatin( $text, $beforeThickSuffixes );
	} // processWordWithArabicStemCyrlToLat

	function processWordWithTurkicStemCyrlToLat( $text, $beforeThickSuffixes = false ) {
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
			$text = preg_replace( '/(?<=[аоыуәе])е(?=ва?$)/u', 'йе', $text );
			$parts = preg_split( '/(?=ева?$)/u', $text, null, PREG_SPLIT_NO_EMPTY );
			$parts[1] = 'э' . mb_substr( $parts[1], 1 );
			// $text = $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
			// return parent::translate( $text, 'tt-latn' );
			return $this->convertTatarWordWithRussianEndingCyrlToLat( $parts );
		}
		// russian suffixes are not found
		// $text = $this->convertSimpleTatarWordFromCyrillicToLatin( $text );
		return $this->convertSimpleTatarWordFromCyrillicToLatin( $text, $beforeThickSuffixes );
	}

	function convertSimpleRussianWordFromCyrillicToLatin( $text, $beforeThickSuffixes = false ) {
		// dirijabl or dirijabl' or dirijabel ?
		// i leave it as it is : dirijabl'
		// v
		$text = preg_replace( '/в(?!(еб|ики))/u', 'v', $text );
		// я
		// ия
		// $text = preg_replace( '/(?<=и)я/u', 'a', $text );
		// $text = preg_replace( '/ия(?=[^аыу]*$)/u', 'iä', $text );
		$text = preg_replace( '/(?<=и)я(?=.*[аыу].*$)/u', 'a', $text );
		if( $beforeThickSuffixes ) {
			$text = preg_replace( '/(?<=и)я$/u', 'a', $text );
		}
		$text = preg_replace( '/(?<=и)я/u', 'ä', $text );
		// ья
		// девичья
		$text = preg_replace( '/(?<=ч)ья/u', 'ya', $text );
		// юмья
		$text = preg_replace( '/(?<=м)ья/u', 'ya', $text );
		// софья
		$text = preg_replace( '/(?<=ф)ья/u', 'ya', $text );
		// уильямс
		$text = preg_replace( '/(?<=уил)ья/u', 'ya', $text );
		// $text = preg_replace( '/ь(?=я)/u', '', $text );
		// е
		// exception
		$text = preg_replace( '/(?<=^мураvь)е(?=v)/u', 'yo', $text );
		// премьер, поезд, менделеевск, бушуев, казадаев
					// change case array
		$text = preg_replace( '/(?<=[аоуеьъ])е/u', 'ye', $text );
		$text = preg_replace( '/^е/u', 'ye', $text );
		$text = preg_replace( '/(?<=режисс)е/u', 'yo', $text );
		$text = preg_replace( '/(?<=фл)е(?=ра)/u', 'yo', $text );
		$text = preg_replace( '/(?<=ст)е(?=рка)/u', 'yo', $text );
		$text = preg_replace( '/(?<=арт)е(?=м)/u', 'yo', $text );
		$text = preg_replace( '/(?<=зыр)е(?=к)/u', 'yo', $text );
		$text = preg_replace( '/(?<=кул)е(?=з)/u', 'yo', $text );
		$text = preg_replace( '/(?<=лонт)е(?=р)/u', 'yo', $text );
		$text = preg_replace( '/(?<=^акт)е(?=р)/u', 'yo', $text );
		$text = preg_replace( '/(?<=^приз)е(?=р)/u', 'yo', $text );
		// ё
		$text = preg_replace( '/(?<=[щч])ё/u', 'o', $text );
		// ю
		// $text = preg_replace( '/(?<=ь)ю/u', 'yu', $text );
		// ау, оу
		// шоу, боулинг, маугли - w
		// but in пауза, наумов, барнаул,
		// белоусово, даут it is u
		$text = preg_replace( '/(?<=[оа])(?<!^(па|на|да))(?<!бело)(?<!барна)у/u', 'w', $text );
		// теркәү comes here with new algorithm
		$text = preg_replace( '/(?<=ә)ү/u', 'w', $text );
		// уи,уо,уэ
		// exceptions to next rule
		$text = preg_replace( '/^пуэр/u', 'puer', $text );
		$text = preg_replace( '/^конгруэнт/u', 'kongruent', $text );
		$text = preg_replace( '/^vакуоль/u', 'vakuol\'', $text );
		// интеллектуал, актуаль, гомосексуал
		$text = preg_replace( '/уал/u', 'ual', $text );
		$text = preg_replace( '/vануату/u', 'vanuatu', $text );
		$text = preg_replace( '/гуам/u', 'guam', $text );
		$text = preg_replace( '/репертуар/u', 'repertuar', $text );
		// $text = preg_replace( '/^куи/u', 'kwi', $text );
		// $text = preg_replace( '/^уо/u', 'wo', $text );
		// $text = preg_replace( '/^уэ/u', 'we', $text );
		// $text = preg_replace( '/^мидуэ/u', 'midwe', $text );
		// $text = preg_replace( '/^ниуэ/u', 'niwe', $text );
		// $text = preg_replace( '/(?<!п)(?<![бвгджзклмнпрстфхцчш]{2})у(?=[иоэ])/u', 'w', $text );
		$text = preg_replace( '/у(?=[иоэа])/u', 'w', $text );
		// ь before ю
		// компьютер
		$text = preg_replace( '/ь(?=ю)/u', '', $text );
		// ь before е
		// премьер
		$text = preg_replace( '/ь(?=ye)/u', '', $text );
		// ь before ё
		$text = preg_replace( '/ь(?=yo)/u', '', $text );
		// ь before и
		// ильич il'yiç, ананьино
		$text = preg_replace( '/ь(?=и)/u', '\'y', $text );
		// // брь
		// moved this to processWordWithRussianStemCyrlToLat
		// $text = preg_replace( '/брь/u', 'ber', $text );
		// ц
		$text = preg_replace( '/ц(?=уз)/u', 's', $text );
		$text = preg_replace( '/(?<=рук)ц/u', 's', $text );
		$text = preg_replace( '/(?<=тан)ц/u', 's', $text );
		$text = preg_replace( '/(?<=функ)ц/u', 's', $text );
		$text = preg_replace( '/(?<=реак)ц/u', 's', $text );
		$text = preg_replace( '/(?<=лек)ц/u', 's', $text );
		$text = preg_replace( '/(?<=эн)ц/u', 's', $text );
		$text = preg_replace( '/(?<=кон)ц/u', 's', $text );
		$text = preg_replace( '/(?<=фран)ц/u', 's', $text );
		$text = preg_replace( '/(?<=редак)ц/u', 's', $text );
		// $text = preg_replace( '/(?<=аэра)ц/u', 's', $text );
		$text = parent::translate( $text, 'tt-latn-x-2000' );
		// $this->returnCaseOfCyrillicWord( $text );
		if ( $this->test ) {
			$text = '['. $text. ']';
		}
		return $text;
	} // convertSimpleRussianWordFromCyrillicToLatin

	function convertSimpleArabicWordFromCyrillicToLatin( $text, $beforeThickSuffixes = false ) {
		//
		// exception to the following къ,гъ replaces
		// к -> k
		// ка, ку
		$text = preg_replace( '/^к(?=амил)/u', 'k', $text );
		$text = preg_replace( '/(?<=^вә)к(?=ал)/u', 'k', $text );
		$text = preg_replace( '/^к(?=уф)/u', 'k', $text );
		$text = preg_replace( '/(?<=^яд)к(?=ар)/u', 'k', $text );
		$text = preg_replace( '/к(?=ати[бп])/u', 'k', $text );
		// ак
		$text = preg_replace( '/(?<=^һәла)к/u', 'k', $text );
		// г -> g
		// аг
		$text = preg_replace( '/(?<=^һәла)г/u', 'g', $text );
		//
		// к -> q
		// ка, кы, ко, ку, къ
		// кка, ккы, кко, кку
		// тәрәккыять, куәт
		$text = preg_replace( '/к(?=к?[аыоуъ])/u', 'q', $text );
		// г -> ğ
		// га, гы, го, гу, гъ
		// гакыл, мәшгуль
		$text = preg_replace( '/г(?=[аыоуъ])/u', 'ğ', $text );
		// гакыл, мәшгуль, тәмугка
		// $text = preg_replace( '/г(?=[^аыоуәэөүияёю]?[аыоуъ])/u', 'ğ', $text );
		// if( $beforeThickSuffixes ) {
			// $text = preg_replace( '/к$/u', 'q', $text );
			// $text = preg_replace( '/г$/u', 'ğ', $text );
		// }
		// к -> q
		// ык, ак
		// ыйк
		// к after thick vowel/syllable
		// әхлак, рәфыйк
		$text = preg_replace( '/(?<=[ыа])к(?![ьи])/u', 'q', $text );
		$text = preg_replace( '/(?<=ый)к(?![ьи])/u', 'q', $text );
		// г -> ğ
		// аг, ыг, ог, уг, яг, юг
		// г after thick vowel/syllable
		// тәмугка, тәмуг
		$text = preg_replace( '/(?<=[аыоуяю])г/u', 'ğ', $text );
		// г->g
		$text = preg_replace( '/г/u', 'g', $text );
		// к->k
		$text = preg_replace( '/к/u', 'k', $text );
		//
		if(
			preg_match( '/[әөүеь]/u', $text )
			&& preg_match( '/[аоуыя]/u', $text )
			&& !preg_match( '/^дөнья/u', $text )
			&& !preg_match( '/^мәğълүмат/u', $text )
			&& !preg_match( '/^паkь/u', $text )
			&& !preg_match( '/^лихьян/u', $text )
			&& !preg_match( '/^ğыйльфан/u', $text )
			&& !preg_match( '/^тәэссорат/u', $text )
			&& !preg_match( '/^әнqара/u', $text )
			&& !preg_match( '/^әхмәтшин/u', $text )
			&& !preg_match( '/^дәвам/u', $text )
			&& !preg_match( '/^дәва/u', $text )
			&& !preg_match( '/^ильяс/u', $text )
			&& !preg_match( '/^әхла[qğ]/u', $text )
			&& !preg_match( '/^тәмуğ/u', $text )
			&& !preg_match( '/^әğъза/u', $text )
			&& !preg_match( '/^тәмам/u', $text )
			&& !preg_match( '/^мәйдан/u', $text )
			&& !preg_match( '/^әлифба/u', $text )
			&& !preg_match( '/^әмма/u', $text )
			|| preg_match( '/^ğазиз/u', $text )
			|| preg_match( '/^яğъни/u', $text )
			|| preg_match( '/^яkи/u', $text )
			|| preg_match( '/^васыя/u', $text )
			|| preg_match( '/^әфğан/u', $text )
			|| preg_match( '/^рөqъğа/u', $text )
			|| preg_match( '/^фирqа/u', $text )
			|| preg_match( '/^ğайн(?!ан)/u', $text )
		) {
			if(
				!preg_match( '/^ğалим?/', $text )
				&& !preg_match( '/^васыя/', $text )
				&& !preg_match( '/^мөğаен/', $text )
				&& !preg_match( '/^мөлаем/', $text )
				&& !preg_match( '/^намзәт/u', $text )
				&& !preg_match( '/^шаğыйр/u', $text )
				&& !preg_match( '/^фаğыйләт/u', $text )
				&& !preg_match( '/^ğабдел/u', $text )
				&& !preg_match( '/^хаkим/u', $text )
				&& !preg_match( '/^qаһин/u', $text )
				&& !preg_match( '/^ğаилә/u', $text )
				&& !preg_match( '/^лалә/u', $text )
				&& !preg_match( '/^ğалләм/u', $text )
				&& !preg_match( '/^kамил/u', $text )
				&& !preg_match( '/^матдә/u', $text )
			) {
				$text = preg_replace( '/(?<!т)а/u', 'ä', $text );
			}
			if(
				!preg_match( '/^ğомум/u', $text )
			) {
				$text = preg_replace( '/о/u', 'ö', $text );
				$text = preg_replace( '/у/u', 'ü', $text );
			}
			$text = preg_replace( '/ый/u', 'i', $text );
			if(
				!preg_match( '/^qыямәт/u', $text )
			) {
				$text = preg_replace( '/ы(?=[яе])/u', 'i', $text );
				$text = preg_replace( '/ы/u', 'e', $text );
			}
			$text = preg_replace( '/(?<![иi])я/u', 'йä', $text );
			$text = preg_replace( '/(?<=[әäа])е/u', 'йe', $text );
			$text = preg_replace( '/я/u', 'ä', $text );
			$text = preg_replace( '/ю/u', 'ü', $text );
		} elseif(
			preg_match( '/[әөүеь]/u', $text )
			&& !preg_match( '/[аоуыя]/u', $text )
		) {
			// $text = preg_replace( '/(?<!и)я/u', 'йä', $text );
			$text = preg_replace( '/(?<=ә)е/u', 'ye', $text );
		} else {
			if(
				!preg_match( '/^вазыйф/u', $text )
				&& !preg_match( '/^ğыйраq/u', $text )
				&& !preg_match( '/^qатğ/u', $text )
				&& !preg_match( '/^qыйсс/u', $text )
				&& !preg_match( '/^ğыйсъян/u', $text )
				&& !preg_match( '/^инqыйлаб/u', $text )
				&& !preg_match( '/^qасыйм/u', $text )
				&& !preg_match( '/^баq/u', $text )
				&& !preg_match( '/^робаğ/u', $text )
				&& !preg_match( '/^qыйтğ/u', $text )
				&& !preg_match( '/^насыйр/u', $text )
			) {
				$text = preg_replace( '/ый/u', 'i', $text );
			}
			if( !$beforeThickSuffixes ) {
				$text = preg_replace( '/(?<=^мәğълүм)а(?=т($|.*[әүие]))/u', 'ä', $text );
			}
			$text = preg_replace( '/(?<=^әхл)а(?=[qğ](ы?$|ы?.*[әүие]))/u', 'ä', $text );
			$text = preg_replace( '/(?<=^әхлä[qğ])ы/u', 'e', $text );
			$text = preg_replace( '/(?<=^k)я(?=р($|.*[әүие]))/u', 'ä', $text );
			$text = preg_replace( '/(?<=^k)я(?=р)/u', 'а', $text );
			$text = preg_replace( '/(?<=^әğъз)а(?=($|.*[әүие]))/u', 'ä', $text );
		}
		//
		// ия
		// "ия" by default converted as iä, see below
		// $text = preg_replace( '/(?<=^мәгълүм)а(?=т[^аы]*$)/u', 'ä', $text );
		$text = preg_replace( '/(?<=^ри)я/u', 'a', $text );
		$text = preg_replace( '/(?<=афи)я(?=т)/u', 'a', $text );
		// $text = preg_replace( '/(?<=ни)я(?=тулл)/u', 'ä', $text );
		// $text = preg_replace( '/(?<=дәби)я(?=т)/u', 'a', $text );
		$text = preg_replace( '/(?<=суфи)я(?=н)/u', 'a', $text );
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
		// $text = preg_replace( '/ия(?=[^аыу]*$)/u', 'iä', $text );
		// $text = preg_replace( '/ия(?=.*[аыу].*$)/u', 'ia', $text );
		// $text = preg_replace( '/ия/u', 'ia', $text );
		$text = preg_replace( '/(?<=и)я(?=.*[аыу].*$)/u', 'a', $text );
		if( $beforeThickSuffixes ) {
			$text = preg_replace( '/(?<=и)я(?=.?$)/u', 'a', $text );
		}
		$text = preg_replace( '/(?<=и)я/u', 'ä', $text );
		//
		// w
		// мәүлүдова, әүхәдиева but except фирдәүс
		$text = preg_replace( '/(?<=фирдә)ү/u', 'ü', $text );
		$text = preg_replace( '/(?<=ә)[үу]/u', 'w', $text );
		$text = preg_replace( '/(?<=а)у/u', 'w', $text );
		// э
		$text = preg_replace( '/^э(?=тдин)/u', '\'e', $text );
		// if words like эластик, элү come here by mistake
		$text = preg_replace( '/^э/u', 'e', $text );
		// тәэмин
		$text = preg_replace( '/э(?=.[аыоуәеөүи])/u', '\'', $text );
		// ризаэтдин
		$text = preg_replace( '/э(?=..[аыоуәеөүи])/u', '\'e', $text );
		//
		// ь
		$text = preg_replace( '/(?<=^мәс)ь(?=әлән)/u', '\'', $text );
		$text = preg_replace( '/ь/u', '', $text );
		//
		// ъ
		$text = preg_replace( '/(?<=^qöр)ъ(?=ән)/u', '\'', $text );
		//
		// в
		// габделхәев, солтангалиев
		$text = preg_replace( '/(?<=[eе])в/u', 'v', $text );
		//
		$text = parent::translate( $text, 'tt-latn-x-2000' );
		//
		if ( $this->test ) {
			$text = '(' . $text . ')';
		}
		//
		return $text;
	} // convertSimpleArabicWordFromCyrillicToLatin

	function convertSimpleTatarWordFromCyrillicToLatin( $text, $beforeThickSuffixes = false ) {
		if (
			preg_match( '/[аоыу]/u', $text )
			// preg_match( '/[аыу]/u', $text )
			// || preg_match( '/о(?!ва?$)/u', $text )
			// ел, як, юк,
			// Я, Ю
			// Е - Ye, not Yı
			|| preg_match( '/^([яю].?|е.)$/u', $text )
			|| preg_match( '/^эв$/u', $text )
			// ||
			// preg_match( '/[яюе]/u', $text )
			// && !preg_match( '/ь/u', $text )
		) {
			// къ, гъ
			$text = preg_replace( '/к(?!ь)(?!арават)/u', 'q', $text );
			$text = preg_replace( '/г/u', 'ğ', $text );
			// ya
			// ия before "thick" syllables
			$text = preg_replace( '/ия(?=.+[аоуы])/u', 'ia', $text );
			// ия at end
			// $text = preg_replace( '/(?<=^ри)я/u', 'a', $text );
			// ийэ - имамиевага -> имамийэвага
			$text = preg_replace( '/(?<=и)й(?=э)/u', '', $text );
			// // yı, yev
			// yı
			// // ayev
			// $text = preg_replace( '/(?<=[аыоу])е(?=ва?$)/u', 'ye', $text );
			// // i added latin e to fix v after previous replace in "ov,ev"
			// // $text = preg_replace( '/(?<=e)в(?=а?$)/u', 'v', $text );
			// yı
			$text = preg_replace( '/(?<=[аыоуъь])е/u', 'yı', $text );
			$text = preg_replace( '/^е/u', 'yı', $text );
			// aw
			// ау, аяу etc except гагауз
			$text = preg_replace( '/(?<=[ая])у(?<!^ğаğау)/u', 'w', $text );
			// äw - wrong spelling әу
			$text = preg_replace( '/(?<=[әä])у/u', 'w', $text );
			// // ov,ev
			// // latin e is added because it might be set in "ayev"
			// $text = preg_replace( '/(?<=[аеe])в(?=а?$)/u', 'v', $text );
			// moved family name ending check to outer function
			$text = preg_replace( '/(?<=[оеэ])в/u', 'v', $text );
			// maybe exceptional ый -> i
			$text = preg_replace( '/(?<=ğ)ый(?=нвар)/u', 'i', $text );
		} else {
			// yä
			// ия before thin syllables
			$text = preg_replace( '/ия(?=.+[әүеи])/u', 'iä', $text );
			// ия at end
			$text = preg_replace( '/ия/u', 'iä', $text );
			// я except янил
			$text = preg_replace( '/я(?!нил)/u', 'yä', $text );
			// ye
			// е is by default e, so i use "lengthen" when it becomes ye.
			// ("by default" means "in toLatin array").
			$text = preg_replace( '/(?<=[әө])е/u', 'ye', $text );
			$text = preg_replace( '/(?<=и)е/u', 'e', $text );
			// exception: енче
			// commenting out, because this is not correct spelling,
			// but for some cases see code outside, in function translate
			// $text = preg_replace( '/^енче$/u', 'ençe', $text );
			// ye
			$text = preg_replace( '/^е/u', 'ye', $text );
			$text = preg_replace( '/йе/u', 'yye', $text );
			// ийэ - имамиевага -> имамийэвага
			$text = preg_replace( '/(?<=и)й(?=э)/u', '', $text );
			// yü
			// exception for ию
			// y should not be removed in these words, as written in
			// tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
			// (see also 2001 rules in qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/19.jpg )
			// and btw these words has gone to processWordWithRussianStemCyrlToLat
			// and are sent to here as exception
			// maybe this exception should be removed, because it may be just an error.
			$text = preg_replace( '/(?<=^и)ю([нл])ь?/u', 'yü$1', $text );
			// yü
			$text = preg_replace( '/(?<=[әө])ю/u', 'yü', $text );
			// ю is by default yu so i use lengthen when it becomes ü
			// "by default" ie in toLatin array
			// see tt.wikipedia.org/wiki/Файл:Ию.jpg
			// and 2001 rules: qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/18.jpg
			$text = preg_replace( '/(?<=и)ю/u', 'ü', $text );
			$text = preg_replace( '/^ю(?!хиди)/u', 'yü', $text );
			// äw
			$text = preg_replace( '/(?<=[әä])[үу]/u', 'w', $text );
			// в in ливәшәү
			$text = preg_replace( '/(?<=ли)в(?=әшәw)/u', 'v', $text );
			// ев
			$text = preg_replace( '/(?<=[eэ])в/u', 'v', $text );
		}
		// ь
		$text = preg_replace( '/ь/u', '', $text );
		$text = parent::translate( $text, 'tt-latn-x-2000' );
		if ( $this->test ) {
			$text = '|' . $text . '|';
		}
		return $text;
	} // convertSimpleTatarWordFromCyrillicToLatin

	function convertRussianWordWithTatarEndingCyrlToLat( $parts ) {
		// $parts[0] = $this->processWordWithRussianStemCyrlToLat(
			// $parts[0] ); // recursion because
			// there are графиктагы and графигында, etc
		$parts[0] = $this->convertSimpleRussianWordFromCyrillicToLatin( $parts[0] );
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
			convertSimpleArabicWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			processWordWithRussianStemCyrlToLat( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	function convertArabicWordWithTatarEndingCyrlToLat( $parts ) {
		$parts[0] = $this->
			convertSimpleArabicWordFromCyrillicToLatin( $parts[0] );
		$parts[1] = $this->
			convertSimpleTatarWordFromCyrillicToLatin( $parts[1] );
		$text = implode( $parts );
		return $text;
	}

	// function convertCompoundWordCyrlToLat( $parts ) {
		// $parts[0] = $this->
			// convertLowercasedTatarWordCyrlToLat( $parts[0] );
		// $parts[1] = $this->
			// convertLowercasedTatarWordCyrlToLat( $parts[1] );
		// $text = implode( $parts );
		// return $text;
	// }

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
		// w
		// w spelling at end of word or before consonant
		// i moved this replace before ya->я because
		// with я i do not know softness (thinness)
		$text = preg_replace( '/(?<=[aıou])w(?=$|[^aıouäeöüi])/u', 'у', $text );
		$text = preg_replace( '/(?<=[äeöü])w(?=$|[^aıouäeöüi])/u', 'ү', $text );
		$text = preg_replace( '/(?<=u)w(?=ı)/u', '', $text );
		$text = preg_replace( '/(?<=ü)w(?=e)/u', '', $text );
		// w in some words
		// шонгауэр
		$text = preg_replace( '/(?<=nga)w(?=er)/u', 'у', $text );
		$text = preg_replace( '/(?<=^k)w(?=insi)/u', 'у', $text );
		$text = preg_replace( '/^w(?=orren)/u', 'у', $text );
		$text = preg_replace( '/^w(?=oker)/u', 'у', $text );
		$text = preg_replace( '/^w(?=el\'s)/u', 'у', $text );
		$text = preg_replace( '/^w(?=otts)/u', 'у', $text );
		$text = preg_replace( '/(?<=^mid)w(?=ey)/u', 'у', $text );
		$text = preg_replace( '/(?<=^ni)w(?=e)/u', 'у', $text );
		$text = preg_replace( '/^w(?=ollis)/u', 'у', $text );
		$text = preg_replace( '/^w(?=eyk)/u', 'у', $text );
		$text = preg_replace( '/^w(?=askaran)/u', 'у', $text );
		$text = preg_replace( '/(?<=^iv)w(?=ar)/u', 'у', $text );
		$text = preg_replace( '/(?<=^bold)w(?=in)/u', 'у', $text );
		$text = preg_replace( '/^w(?=ilik)/u', 'у', $text );
		$text = preg_replace( '/^w(?=illis)/u', 'у', $text );
		$text = preg_replace( '/^w(?=ayl)/u', 'у', $text );
		$text = preg_replace( '/^w(?=ilyams)/u', 'у', $text );
		$text = preg_replace( '/^w(?=izerspun)/u', 'у', $text );
		// fix strangeness/shortness of the source latin orthography
		// iä -> ия
		// iä -> ыя
		// ia -> иа or ия
		$text = preg_replace( '/(?<=materi)a/u', 'а', $text );
		$text = preg_replace( '/(?<=milli)a/u', 'а', $text );
		$text = preg_replace( '/(?<=avi)a/u', 'а', $text );
		$text = preg_replace( '/(?<=vari)a/u', 'а', $text );
		$text = preg_replace( '/(?<=sotsi)a/u', 'а', $text );
		$text = preg_replace( '/(?<=^di)a/u', 'а', $text );
		$text = preg_replace( '/(?<=xristi)a/u', 'а', $text );
		$text = preg_replace( '/(?<=medi)a/u', 'а', $text );
		$text = preg_replace( '/(?<=koloni)a(?=l)/u', 'а', $text );
		$text = preg_replace( '/(?<=viktori)a(?=n)/u', 'а', $text );
		$text = preg_replace( '/(?<=yuli)a(?=n)/u', 'а', $text );
		$text = preg_replace( '/(?<=gregori)a(?=n)/u', 'а', $text );
		$text = preg_replace( '/(?<=potentsi)a(?=l)/u', 'а', $text );
		$text = preg_replace( '/(?<=territori)a(?=l)/u', 'а', $text );
		$text = preg_replace( '/(?<=komissari)a(?=t)/u', 'а', $text );
		$text = preg_replace( '/(?<=provintsi)a(?=l)/u', 'а', $text );
		$text = preg_replace( '/(?<=pi)a(?=n)/u', 'а', $text );
		$text = preg_replace( '/(?<=kristi)a(?=n)/u', 'а', $text );
		// default ия
		$text = preg_replace( '/(?<=i)[äa]/u', 'я', $text );
		// iü -> ию
		$text = preg_replace( '/(?<=i)ü/u', 'ю', $text );
		// y
		// add specific cyrillic letters
		// ya -> я
		// ya as first letter
		$text = preg_replace( '/^ya/u', 'я', $text );
		// yya
		$text = preg_replace( '/yya/u', 'йя', $text );
		// ya as first letter of second part of compound word
		$text = preg_replace( '/(?<=^[kt]ön)ya(?=[qğ])/u', 'ья', $text );
		$text = preg_replace( '/(?<=^(taş|ast|yıl|qul))ya(?=z)/u', 'ъя', $text );
		$text = preg_replace( '/(?<=^şäl)ya(?=у)/u', 'ья', $text );
		// ya after vowels
		$text = preg_replace( '/(?<=[aıouie])y[aä]/u', 'я', $text );
		// ya as [ya] (ья,ъя)
		$text = preg_replace( '/(?<=dön)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=lix)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=ğis)ya/u', 'ъя', $text );
		$text = preg_replace( '/(?<=^il)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=viç)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=yum)ya/u', 'ья', $text );
		// for ulyanov, though i made it ul'yanov at test
		// ^ because should not catch molekulyar
		$text = preg_replace( '/(?<=^ul)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=sof)ya/u', 'ья', $text );
		$text = preg_replace( '/(?<=ken)ya/u', 'ъя', $text );
		$text = preg_replace( '/(?<=ğıys)ya/u', 'ъя', $text );
		$text = preg_replace( '/(?<=(art|sul))ya(?=[qğ])/u', 'ъя', $text );
		$text = preg_replace( '/(?<=yul)ya(?=zma)/u', 'ъя', $text );
		$text = preg_replace( '/(?<=här)ya(?=[qğ])/u', 'ья', $text );
		$text = preg_replace( '/(?<=ital)ya(?=n)/u', 'ья', $text );
		// all other occurences of {consonant}ya, default
		// ya after consonants in russian words as softening of consonant + mini y + u
		// молекуляр, варяг, октябрьское, ассимиляция, рязань
		// $text = preg_replace( '/(?<=simil)ya/u', 'я', $text );
		// $text = preg_replace( '/(?<=slav)ya/u', 'я', $text );
		// $text = preg_replace( '/(?<=var|kul|okt|otr)ya/u', 'я', $text );
		// $text = preg_replace( '/(?<=^r)ya/u', 'я', $text );
		$text = preg_replace( '/(?<=[^aouıiäöüeiая])ya/u', 'я', $text );
		// ya after apostrophe in russian words
		// $text = preg_replace( '/(?<=yum\')ya/u', 'я', $text );
		// yo
		// yo as first letter in russian words
		$text = preg_replace( '/^yo(?=lka)/u', 'ё', $text );
		// yo after consonants in russian words as softening of consonant + mini y + o
		// $text = preg_replace( '/(?<=plan)yo/u', 'ё', $text );
		// $text = preg_replace( '/(?<=şof)yo/u', 'ё', $text );
		// $text = preg_replace( '/(?<=samol)yo/u', 'ё', $text );
		// $text = preg_replace( '/(?<=dirij)yo/u', 'ё', $text );
		$text = preg_replace( '/(?<=[bçdfgjklmnprstvz])yo/u', 'ё', $text );
		// yı -> е
		// yı as first letter
		$text = preg_replace( '/^yı/u', 'е', $text );
		// yı as first letter of second part of compound word
		$text = preg_replace( '/(?<=^(küp|ber))yı(?=l)/u', 'ье', $text );
		$text = preg_replace( '/(?<=^un)yı(?=l)/u', 'ъе', $text );
		$text = preg_replace( '/(?<=^meñ)yı(?=l)/u', 'ье', $text );
		$text = preg_replace( '/(?<=^utız)yı(?=l)/u', 'ъе', $text );
		// yı after vowels
		$text = preg_replace( '/(?<=[aıouei])yı/u', 'е', $text );
		// iye
		$text = preg_replace( '/(?<=i)y(?=e)/u', '', $text );
		// yu -> ю
		// yu as first letter
		$text = preg_replace( '/^yu/u', 'ю', $text );
		// yu after vowels
		$text = preg_replace( '/(?<=[aıou])yu/u', 'ю', $text );
		// yu after consonants in russian words
		$text = preg_replace( '/(?<=komp)yu/u', 'ью', $text );
		// нью, фьючерс, интервью
		$text = preg_replace( '/(?<=[nfvx])yu/u', 'ью', $text );
		$text = preg_replace( '/(?<=ber)yu(?=l)/u', 'ью', $text );
		// yu after consonants in russian words as softening of consonant + mini y + u
		// make default
		// $text = preg_replace( '/(?<=^[bl])yu/u', 'ю', $text );
		// $text = preg_replace( '/(?<=v[ao]l)yu/u', 'ю', $text );
		// $text = preg_replace( '/(?<=^[sa]l)yu/u', 'ю', $text );
		$text = preg_replace( '/(?<=[^aouıiäöüeаяюеауү])yu/u', 'ю', $text );
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
		// except яши, яшел, яшәү, яшүсмер
		$text = preg_replace( '/(?<=^я[şm])(?![äieüaouı])/u', 'ь', $text );
		// yä after vowels
		// oyä for noyäber
		// ayä for riwayäte
		$text = preg_replace( '/(?<=[äöüaoi])yä/u', 'я', $text );
		// ye -> е
		// ye as first letter
		$text = preg_replace( '/^ye/u', 'е', $text );
		// ye after y
		$text = preg_replace( '/yye/u', 'йе', $text );
		// ye as first letter of second part of compound word
		// акъегет
		$text = preg_replace( '/(?<=^aq)ye/u', 'ъе', $text );
		// унъеллык this is wrong, it is йы
		// $text = preg_replace( '/(?<=^un)ye/u', 'ъе', $text );
		// ye after vowels in russian words
		// $text = preg_replace( '/(?<=pro)ye/u', 'е', $text );
		// $text = preg_replace( '/(?<=po)ye/u', 'е', $text );
		// ye after vowels
		// mendeleyevsk
		$text = preg_replace( '/(?<=[aoueäö])ye/u', 'е', $text );
		// ye after consonants in russian words
		// премьер, ателье
		$text = preg_replace( '/(?<=(prem|atel))ye/u', 'ье', $text );
		// вьет, сьерра, пьетро
		$text = preg_replace( '/(?<=v|s|p)ye/u', 'ье', $text );
		// карьер
		$text = preg_replace( '/(?<=kar)ye/u', 'ье', $text );
		// аньелли
		$text = preg_replace( '/(?<=an)ye/u', 'ье', $text );
		// объектив
		$text = preg_replace( '/(?<=ob)ye/u', 'ъе', $text );
		// субъект
		$text = preg_replace( '/(?<=ub)ye/u', 'ъе', $text );
		// yü -> ю
		// yü as first letter
		$text = preg_replace( '/^yü/u', 'ю', $text );
		// show softness if it is not shown
		$text = preg_replace( '/(?<=^юn$)/u', 'ь', $text );
		// yü after vowels
		// exception to next replace
		// июнь, июль
		$text = preg_replace( '/(?<=^i)yü([nl])(?!e)/u', 'ю$1ь', $text );
		// өю, җәю, ию, июнь
		$text = preg_replace( '/(?<=[äöi])yü/u', 'ю', $text );
		// yi
		$text = preg_replace( '/(?<![y\'])(?<!^)yi/u', 'ьи', $text );
		$text = preg_replace( '/(?<=\')yi/u', 'и', $text );
		// e
		// e spelling at beginnig of word
		// exception: e in ençe - commenting this out, because
		// that is not correct spelling, but, for some cases,
		// i make it outside, in function translate
		// $text = preg_replace( '/^ençe$/u', 'енче', $text );
		// e spelling at beginnig of word
		$text = preg_replace( '/^e/u', 'э', $text );
		// e after vowels in russian words
		$text = preg_replace( '/(?<=[aou])e/u', 'э', $text );
		// e as "ae"
		$text = preg_replace( '/(?<=^br)e(?=yk)/u', 'э', $text );
		$text = preg_replace( '/(?<=^kr)e(?=k)/u', 'э', $text );
		$text = preg_replace( '/(?<=^m)e(?=r)(?!rkel)/u', 'э', $text );
		$text = preg_replace( '/(?<=^delav)e(?=r)/u', 'э', $text );
		$text = preg_replace( '/(?<=^у)e(?=l\'s)/u', 'э', $text );
		$text = preg_replace( '/(?<=^midу)e(?=y)/u', 'э', $text );
		$text = preg_replace( '/(?<=^niу)e/u', 'э', $text );
		$text = preg_replace( '/(?<=^у)e(?=yk)/u', 'э', $text );
		// i cannot add here men->мэн because there is tatar word мен
		// пэр except перманганат, перчатка, пермь, перещепкино,
		// период, персона
		$text = preg_replace( '/(?<=^p)e(?=r)(?!r(m|çat|e|i|son))/u', 'э', $text );
		// шонгауэр
		$text = preg_replace( '/(?<=gaу)e(?=r)/u', 'э', $text );
		// e after apostrophe
		// $text = preg_replace( '/(?<=riza)\'e/u', 'э', $text );
		// $text = preg_replace( '/(?<=tä)\'e/u', 'э', $text );
		$text = preg_replace( '/(?<=\')e/u', 'э', $text );
		// е -> ы : шәрык
		// but шәрех
		$text = preg_replace( '/(?<=şär)e(?=q)/u', 'ы', $text );
		// ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-
		// ts
		// ts as ц, in russian words
		// $text = preg_replace( '/ts(?=iя)/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=io)/u', 'ц', $text );
		// ...ация, ...ацион
		// $text = preg_replace( '/(?<=[aя])ts(?=i)/u', 'ц', $text );
		// some rules for ts found using apertium-tat.tat.lexc and words_mansur.txt
		// from https://sourceforge.net/p/apertium/svn/HEAD/tree/languages/apertium-tat/
		// and http://www.corpus.tatar/
		// several tsi are for тси
		$text = preg_replace( '/(?<=^tu)ts(?=i)/u', 'тс', $text ); // тутси
		$text = preg_replace( '/(?<=^por)ts(?=i)/u', 'тс', $text ); // портсигар
		$text = preg_replace( '/(?<=be)ts(?=i)/u', 'тс', $text ); // бетси, бетсис
		$text = preg_replace( '/(?<=ra)ts(?=iman)/u', 'тс', $text ); // ратсимандреси
		$text = preg_replace( '/(?<=pi)ts(?=ilad)/u', 'тс', $text ); // питсиладис
		$text = preg_replace( '/(?<=bigli)ts(?=i)/u', 'тс', $text ); // биглитси
		$text = preg_replace( '/(?<=rikke)ts(?=i)/u', 'тс', $text ); // риккетсиоз
		$text = preg_replace( '/(?<=smar)ts(?=i)/u', 'тс', $text ); // смартсити
		$text = preg_replace( '/(?<=moke)ts(?=i)/u', 'тс', $text ); // мокетси
		$text = preg_replace( '/(?<=kir)ts(?=i)/u', 'тс', $text ); // киртсинхе
		// tsi by default is ци. 23126 wordforms with *ци* are found in the corpus
		$text = preg_replace( '/ts(?=i)/u', 'ц', $text );
		// several itsa are for итса
		$text = preg_replace( '/(?<=si)ts(?=a)/u', 'тс', $text ); // ситса
		$text = preg_replace( '/(?<=pi)ts(?=a)/u', 'тс', $text ); // питсак
		// itsa by default is ица
		$text = preg_replace( '/(?<=i)ts(?=a)/u', 'ц', $text );
		// several otse are for отсе
		$text = preg_replace( '/(?<=o)ts(?=ek)/u', 'тс', $text ); // отсек
		$text = preg_replace( '/(?<=flo)ts(?=erv)/u', 'тс', $text ); // флотсервис
		$text = preg_replace( '/(?<=zago)ts(?=eno)/u', 'тс', $text ); // заготсено
		$text = preg_replace( '/(?<=lxo)ts(?=e)/u', 'тс', $text ); // лхотсе
		// otse by default is оце
		$text = preg_replace( '/(?<=o)ts(?=e)/u', 'ц', $text );
		// several ts... are for тс...
		//тс,тсэ,тсс
		$text = preg_replace( '/^ts[es]?$/u', 'тс', $text ); // тс
		/*
		// abbreviations are separated, so these checks do not work
		$text = preg_replace( '/^ts(?=[jsktnfşeo]$)/u', 'тс', $text ); // ТСЖ,тсс,ТСК,ТСТ,ТСН,ТСФ
			// ,ТСШ,Тсэ,ТСО
		$text = preg_replace( '/^ts(?=sr)/u', 'тс', $text ); // ТССР
		$text = preg_replace( '/^ts(?=pa)/u', 'тс', $text ); // ТСПА
		$text = preg_replace( '/^ts(?=nru)/u', 'тс', $text ); // ТСНРУ
		$text = preg_replace( '/^ts(?=kn)/u', 'тс', $text ); // ТСКН
		$text = preg_replace( '/^ts(?=tt)/u', 'тс', $text ); // ТСТТ
		$text = preg_replace( '/^ts(?=sc)/u', 'тс', $text ); // ТССҖ
		*/
		$text = preg_replace( '/^ts(?=u[^n])/u', 'тс', $text );
		// тсутсуми, тсуйоси, тсуда, тсутому
		// , тсутсуи, тсушида,
		// but not цунами
		// ts... by default is ц...
		$text = preg_replace( '/^ts/u', 'ц', $text );
		// tsenko, tsov, tsev, tsin by default are ц...
		$text = preg_replace( '/^ts(?=(enko|[oe]v|in))/u', 'ц', $text );
		// exception to following rule
		$text = preg_replace( '/massaçusetts/u', 'массачусеттс', $text );
		$text = preg_replace( '/уotts/u', 'уоттс', $text );
		// ...ts by default is ...ц
		$text = preg_replace( '/ts$/u', 'ц', $text );
		// exception to following rule
		// башкортстан
		$text = preg_replace( '/başqortstan/u', 'башкортстан', $text );
		$text = preg_replace( '/frants/u', 'франц', $text );
		// (cons except ylnr)ts by default is (cons except ylnr)ц
		$text = preg_replace( '/(?<![aouıiäöüeаяюеауүэыylnr])ts/u', 'ц', $text );
		// exception to following rule
		$text = preg_replace( '/irkutsk/u', 'иркутск', $text );
		$text = preg_replace( '/ştatski/u', 'штатски', $text );
		// ts(cons) by default is ц(cons)
		$text = preg_replace( '/ts(?![aouıiäöüeаяюеауүэы])/u', 'ц', $text );
		// itsı is ицы
		// no, many итсы like габаритсыз, ситсы
		// $text = preg_replace( '/(?<=i)ts(?=ı)/u', 'ц', $text );
		// no, many етсы like билетсыз, nearly as many етса as еца
		// $text = preg_replace( '/(?<=e)ts(?=a)/u', 'ц', $text );
		/*
		// several ...ts are for ...тс
		$text = preg_replace( '/(?<=^[msrapgdkbuivteş])ts$/u', 'тс', $text ); // МТС,СТС,РТС
			// , АТС, ПТС, ГТС, ДТС, КТС, БТС, Итс, УТС, ИТС, ВТС, ТТС, ЭТС, ШТС
		$text = preg_replace( '/(?<=(gey|dar|sta))ts$/u', 'тс', $text ); // Гейтс,дартс,статс
		// too many ...тс
		// ...ts by default is ...ц
		$text = preg_replace( '/ts$/u', 'ц', $text );
		*/
		//
		// $text = preg_replace( '/(?<=(fran|muni|kvar))ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=muni)ts/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=etner)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=pro)ts/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=ito)/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=entr)/u', 'ц', $text );
		$text = preg_replace( '/(?<=a)ts(?=etil)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=va)ts(?=lav)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=эn)ts(?=ikl)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=so)ts(?=ial)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=vesti)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=ilя)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=volю)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=ambi)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=aэra)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=delega)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=so)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=kirilli)ts/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=id)/u', 'ц', $text );
		// $text = preg_replace( '/(?<=done)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=kan)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=ak)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=prin)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=dis)ts/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=ifr)/u', 'ц', $text );
		$text = preg_replace( '/(?<=vi)ts/u', 'ц', $text );
		// $text = preg_replace( '/(?<=indeе)ts/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=iklon)/u', 'ц', $text );
		// $text = preg_replace( '/ts(?=ement)/u', 'ц', $text );
		$text = preg_replace( '/(?<=gli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^li)ts/u', 'ц', $text ); // лицензия, лицей
		$text = preg_replace( '/(?<=^me)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ofi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=poli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^re)ts/u', 'ц', $text ); // retsenziä, retsessiv, retsept
		$text = preg_replace( '/(?<=^za)ts/u', 'ц', $text ); // zatsepin
		$text = preg_replace( '/(?<=^ka)ts/u', 'ц', $text ); // katsalapov
		$text = preg_replace( '/(?<=^ma)ts/u', 'ц', $text ); // matsuyev
		// words with ц to add
		// lyutserna motsart tatptitseprom soljenitsın metsenatlıq mitsubisi rıtsar' aetsäy atseton
		// ötsen mitsui datsyuk brutsellez kotsowrek pritsep donitsettinıñ nitsek ketsyan yatsenyuk
		// tetsı paratsetamol nagovitsın atso umnitsı kuritsın epitsentr platsında bogoroditse
		// pretsedent sinitsın golitsın spitsın polkovodetsı nitroglitserin nogovitsın lisitsın
		// pritseplar motsartnı galitsına vetsek spetsekotrans matsuuranıñ kotsyubenko
		// ptitsı platsenta mitseliy matıtsın kupetsı kunitsın ulitse katso pretsessiä dratsena
		// şpritsı militseyskaya mesyatsa kontratseptiv golitsıno askomitsetlar avitsenna
		$text = preg_replace( '/(?<=^lyu)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mo)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=pti)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=ljeni)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^rı)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ae)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^a)ts(?=et)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ö)ts(?=en$)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^da)ts(?=yu)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^bru)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ko)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=pri)ts(?=e)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^doni)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=ni)ts(?=ek$)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ke)ts(?=yan)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ya)ts(?=enyu)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^te)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^para)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^navogi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^a)ts(?=o)/u', 'ц', $text );
		$text = preg_replace( '/(?<=^umni)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^kuri)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^epi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pla)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^rodi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pre)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^sini)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^goli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^spi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=vode)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=govi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^lisi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^gali)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ve)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^spe)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ko)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pla)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^matı)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^kupe)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^kuni)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^uli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^ka)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pre)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^dra)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^şpri)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mili)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mesya)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^kontra)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^goli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^askomi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^avi)ts/u', 'ц', $text );
		// şvetsariäneñ fatseliä pritsel mudretsa matitsın granitsı traditson proflitsey petsä
		// patsan yedinitsı donitsetti bliznetsı epitsentrı troitsı sabatsay pitsunda komatsu
		// vilemovitse bogoroditsı
		$text = preg_replace( '/(?<=^fa)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pri)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mudre)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^mati)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^grani)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^profli)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pe)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pa)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^yedini)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^blizne)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^troi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^saba)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^pi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=^koma)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=movi)ts/u', 'ц', $text );
		$text = preg_replace( '/(?<=rodi)ts/u', 'ц', $text );
		// all other ts s will become тс, it is default
		// s as ц
		$text = preg_replace( '/(?<=fran)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=truk)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=stan)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=funk)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=reak)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=^lek)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=^эn)s/u', 'ц', $text );
		$text = preg_replace( '/(?<=^kon)s(?=ert)/u', 'ц', $text );
		$text = preg_replace( '/(?<=ak)s(?=iя)/u', 'ц', $text );
		$text = preg_replace( '/(?<=ren)s(?=iя)/u', 'ц', $text );
		// -ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц-ц
		// şç
		// şç as щ, in russian words
		$text = preg_replace( '/şç(?=otka)/u', 'щ', $text );
		$text = preg_replace( '/(?<=li)şç(?=e)/u', 'щ', $text );
		$text = preg_replace( '/(?<=xru)şç(?=o)/u', 'щ', $text );
		// o
		// o -> ё after soft russian consonants
		$text = preg_replace( '/(?<=^sç)o/u', 'ё', $text );
		// пугачёв
		$text = preg_replace( '/(?<=aç)o/u', 'ё', $text );
		// щётка, хрущёв
		$text = preg_replace( '/(?<=щ)o/u', 'ё', $text );
		// arabic
		//        words
		// arabic words // i made empty comments but phpcs check blamed on that
		// ya after consonants, except after apostrophe
		// exception to the next replace
		// гыйсъян
		// already made upper at "ya as [ya] (ья,ъя)"
		// $text = preg_replace( '/(?<=ğıys)ya/u', 'ъя', $text );
		// dönya
		$text = preg_replace( '/(?<=[^aıouäeöüi\'])ya/u', 'ья', $text );
		// ä
		// ä -> а after ğ, q, in arabic words
		// exception әфганстан instead of әфганьстан
		$text = preg_replace( '/äfğän/u', 'әфган', $text );
		// гадәләт, кадәр
		$text = preg_replace( '/(?<=[ğq])ä/u', 'а', $text );
		// exception to next (following) rules ie
		// do not add ь after that
		// кардәш
		$text = preg_replace( '/qаrdä/u', 'кардә', $text );
		// add ь after that
		// кәгазь
		// кагазе
		// кәгазьгә
		// гарь, фидакарь
		$text = preg_replace( '/(?<=^käğаz)(?!e)/u', 'ь', $text );
		// just for a wrong spelling qäğäz, as an example:
		$text = preg_replace( '/(?<=^qаğаz)(?!e)/u', 'ь', $text );
		$text = preg_replace( '/(?<=[ğq][аа]r)(?![eäi])/u', 'ь', $text );
		$text = preg_replace( '/(?<=ğаt)(?!e)/u', 'ь', $text );
		$text = preg_replace( '/(?<=^mäşäqаt)(?!e)/u', 'ь', $text );
		$text = preg_replace( '/(?<=^ğаm)(?![eä])/u', 'ь', $text );
		$text = preg_replace( '/(?<=^mäqаl)(?![eä])/u', 'ь', $text );
		// $text = preg_replace( '/(?<=[ğq]а[^яеy])(?![äeöüi])/u', 'ь', $text );
		// $text = preg_replace( '/(?<=[ğq]а[bcçdfgğhjklmnñpqrsştvwxz])(?=([аоуы]|$))/u', 'ь', $text );
		// $text = preg_replace( '/(?<=ğаt)(?!e)/u', 'ь', $text );
		// $text = preg_replace( '/(?<=[ğq]а[bcçdfgğhjklmnñpqrsşvwxz])(?!e)/u', 'ь', $text );
		// $text = preg_replace( '/(?<=[ğq]а[bcçdfgğhjklmnñpqrsştvwxz]{2})(?=([аоуы]|$))/u', 'ь', $text );
		$text = preg_replace( '/(?<=[ğq]ая.)(?![äeöüi])/u', 'ь', $text );
		// ä -> а by alif -> a rule in arabic words
		// (informal rule used in cyrillic but canceled in latin)
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
		$text = preg_replace( '/(?<=id)ä(?=rä)/u', 'а', $text );
		$text = preg_replace( '/(?<=möh)ä(?=cir)/u', 'а', $text );
		$text = preg_replace( '/(?<=äğz)ä/u', 'а', $text );
		$text = preg_replace( '/(?<=möb)ä(?=räk)/u', 'а', $text );
		$text = preg_replace( '/(?<=sän)ä(?=ğаt)/u', 'а', $text );
		$text = preg_replace( '/(?<=xär)ä(?=bä)/u', 'а', $text );
		$text = preg_replace( '/(?<=häl)ä(?=q)/u', 'а', $text );
		$text = preg_replace( '/(?<=näz)ä(?=rät)/u', 'а', $text );
		$text = preg_replace( '/(?<=särk)ä(?=tib)/u', 'а', $text );
		$text = preg_replace( '/(?<=mäğlüm)ä(?=t)/u', 'а', $text );
		$text = preg_replace( '/(?<=ğöläm)ä/u', 'а', $text );
		$text = preg_replace( '/(?<=fidak)ä(?=r)/u', 'я', $text );
		// show softness if it is not shown
		$text = preg_replace( '/(?<=wil)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=wil)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=cin)[aä](яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=cin)[aä](яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=nih)ä(яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=nih)ä(яt)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=яdk)ä(r)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=яdk)ä(r)(?![ei])/u', 'а$1ь', $text );
		$text = preg_replace( '/(?<=riw)[äa](яt)(?=[ei])/u', 'а$1', $text );
		$text = preg_replace( '/(?<=riw)[äa](яt)(?![ei])/u', 'а$1ь', $text );
		// '
		// ' is converted as ь by default
		// ' as hamza
		// $text = preg_replace( '/(?<=(tä|m[aä]|mö))\'/u', 'э', $text );
		$text = preg_replace( '/(?<=tä)\'(?=min|sir)/u', 'э', $text );
		$text = preg_replace( '/(?<=mö)\'(?=min)/u', 'э', $text );
		$text = preg_replace( '/(?<=mä)\'(?=mür)/u', 'э', $text );
		$text = preg_replace( '/(?<=ma)\'(?=may)/u', 'э', $text );
		$text = preg_replace( '/(?<=tä)\'(?=эssorat)/u', '', $text );
		$text = preg_replace( '/(?<=riza)\'(?=эtdin)/u', '', $text );
		$text = preg_replace( '/(?<=q[öo]r)\'/u', 'ъ', $text );
		// ' as '
		// $text = preg_replace( '/(?<=^d)\'$/u', '\'', $text ); // this does
		// not work because ' is later converted to ь
		// ' as -
		$text = preg_replace( '/(?<!(jabl|atel|vest|neft))\''
			.'(?=n[eı]ñ?|t[aä]n?|d[aä]n?|ğa|gä|qa|kä|l(ıq?|ek?)|[eıэ](n|$))/u', '-', $text );
		$text = preg_replace( '/(?<=^mk)\'/u', '-', $text );
		$text = preg_replace( '/(?<=^mak)\'/u', '-', $text );
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
		$text = preg_replace( '/(?<=s)ü(?=rät)/u', 'у', $text );
		$text = preg_replace( '/(?<=ğоm)ü(?=m)/u', 'у', $text );
		// e
		// äxläğendä -> әхлагындә
		// $text = preg_replace( '/(?<=ğ)e(?=[bcçdfgğhjklmnñpqrsştvwxyzщцтс]{2}ä)/u', 'ы', $text );
		// when softness is shown further by e
		// шөгыле, җәмгысе
		// $text = preg_replace( '/(?<=ğ)e(?=.{1,2}[eä])/u', 'ы', $text );
		$text = preg_replace( '/(?<=ğ)e/u', 'ы', $text );
		// e -> ы.ь after ğ in arabic words
		// ä in (?![eä]) is for an incorrect spelling közğedäy, maybe there are
		// also other cases
		// шөгыль,шөгыльләнү,фигыль,шигырь
		// $text = preg_replace( '/(?<=ğ)e(.)(?![eä])/u', 'ы$1ь', $text );
		$text = preg_replace( '/(?<=ğы[lr])/u', 'ь', $text );
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
		$text = preg_replace( '/(?<=^was)i(?=я[tw][ei])/u', 'ы', $text );
		$text = preg_replace( '/(?<=^ras)i(?=x)/u', 'ый', $text );
		$text = preg_replace( '/(?<=^ğaz)i(?=m)/u', 'ый', $text );
		// exception to the next replace
		// нәгыйм
		$text = preg_replace( '/(?<=^näğ)i(?=m)/u', 'ый', $text );
		$text = preg_replace( '/(?<=^wäğ)i(?=z)/u', 'ый', $text );
		$text = preg_replace( '/(?<=^waq)i(?=ğ)/u', 'ый', $text );
		$text = preg_replace( '/(?<=^ictimağ)i/u', 'ый', $text );
		$text = preg_replace( '/(?<=^ğ)i(?=zz)/u', 'ый', $text );
		$text = preg_replace( '/(?<=^ğ)i(?=nwar)/u', 'ый', $text );
		// i -> ый after ğ,q in arabic words when softness is not shown
		// further with soft wovel
		// мөстәкыйль, гыйльфан
		$text = preg_replace(
			// '/(?<=[qğ])i([^aıouäeöüiяе])(?![^aıouäeöüiяе]?[eä])/u',
			'/(?<=[qğ])iy?([^aıouäeöüiяе])(?=$|[^aıouäeöüiяе]?[aıou])/u',
			'ый$1ь', $text );
		// i -> ый after ğ,q in arabic words when softness is shown
		// further with soft wovel
		// гыйлем
		// also when softness is not shown futher
		// тәрәккый, фәгыйләт
		// $text = preg_replace( '/(?<=[ğq])i(?=lem)/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])iy?(?![eяy])/u', 'ый', $text );
		$text = preg_replace( '/(?<=[ğq])iy?(?=[eяy])/u', 'ы', $text );
		// zäğiflege - зәгыйфьлеге, möstäqillegen - мөстәкыйльлеген
		// but not at гыйффәт (ie not so at middle of arabic word), not qimmät, ğizzät
		$text = preg_replace(
			'/(?<=[qğ])ый([^aıouäeöüiяе])(?=[^aıouäeöüiяе][äeöüi])(?!(fät|mät|zät))/u',
			'ый$1ь', $text );
		// i -> и at iqtisadi
		// i could not find why it becomes ый, this should be fixed there,
		// and command below, about this word, should be restored
		// 2018-ap-3: it just works now without this fix
		// $text = preg_replace( '/iqtisadi/u', 'икътисади', $text );
		// i -> ый before q in arabic words
		$text = preg_replace( '/(?<=räf)i(?=q)/u', 'ый', $text );
		$text = preg_replace( '/(?<=sitd)i(?=q)/u', 'ый', $text );
		$text = preg_replace( '/(?<=ğaş)i(?=q)/u', 'ый', $text );
		$text = preg_replace( '/(?<=sad)i(?=q)/u', 'ый', $text );
		$text = preg_replace( '/(?<=ğaz)i(?=m)/u', 'ый', $text );
		// q
		// exception to next rule
		$text = preg_replace( '/(?<=tärä)q(?=q)/u', 'к', $text );
		// q -> къ after soft vowels
		// before consonants and at end of word in arabic words
		// $text = preg_replace( '/q(?=[^aıouäeöüi])/u', 'къ', $text );
			// this makes also ...лыкълар...
		// $text = preg_replace( '/(?<=i)q(?=tis)/u', 'къ', $text ); // c-d out, see above
		// $text = preg_replace( '/(?<=täşwi)q(?=[^aıouäeöüi]|$)/u', 'къ', $text );
		// $text = preg_replace( '/(?<=rö)q(?=ğа)/u', 'къ', $text );
		// $text = preg_replace( '/(?<=li)q(?=[^aıouäeöüi]|$)/u', 'къ', $text );
		// $text = preg_replace( '/(?<=tä)q(?=dim)/u', 'къ', $text );
		// $text = preg_replace( '/(?<=nä)q/u', 'къ', $text );
		// $text = preg_replace( '/(?<=i)q(?=tis)/u', 'къ', $text );
		// $text = preg_replace( '/(?<=[iäö])q(?=[^aıouäeöüi]*[aıou]|$)/u', 'къ', $text );
		$text = preg_replace( '/(?<=[iäö])q(?=[^aıouäeöüiаыоуәэөүяю]|$)/u', 'къ', $text );
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
		// at end of word after soft syllable
		// $text = preg_replace( '/(?<=bäli)ğ(?!e)/u', 'гъ', $text );
		// after ä -> {а as ә}
		// $text = preg_replace( '/(?<=qа)ğ(?=bä)/u', 'гъ', $text );
		// i have replaced the above 6 replaces with this one
		$text = preg_replace( '/(?<=[äiа])ğ(?![aıouäeöüiаы])/u', 'гъ', $text );
		$text = preg_replace( '/(?<=^olu)ğ(?![aıаы])/u', 'гъ', $text );
		// before consonants after я
		$text = preg_replace( '/(?<=я)ğ(?=ni)/u', 'гъ', $text );
		$text = preg_replace( '/(?<=я)ğ(?=f[aä]r)/u', 'гъ', $text );
		$text = preg_replace( '/(?<=я)ğ(?=qub)/u', 'гъ', $text );
		// казакъ
		$text = preg_replace( '/(?<=qaza)q/u', 'къ', $text );
		// k
		// k -> кь before consonants and at end of word in arabic words
		// pak пакь but kompakt компакт
		$text = preg_replace( '/(?<=^pa)k(?=[^aıouäeöüi]|$)/u', 'кь', $text );
		// ь
		// ь is deleted according to 2012 law, so i can restore it, i am going to add
		// several rules only, i think something should be used for ь,
		// probably ' as in 2000's rules
		$text = preg_replace( '/(?<=ul)(?=tima)/u', 'ь', $text );
		$text = preg_replace( '/(?<=bol)(?=şevik)/u', 'ь', $text );
		$text = preg_replace( '/(?<=kul)(?=tur)/u', 'ь', $text );
		$text = preg_replace( '/(?<=уil)(?=яm)/u', 'ь', $text );
		$text = parent::translate( $text, 'tt-cyrl' );
		// apostrophe, this should be here, after parent::translate
		$text = preg_replace( '/(?<=^д)ь/u', '\'', $text );
		return $text;
	} // convertLowercasedTatarWordLatToCyrl

	function latinToUpper( $text ) {
		$text = str_replace( 'i', 'İ', $text );
		$text = mb_strtoupper( $text );
		// $text = str_replace( [ 'I', 'ı' ], [ 'İ', 'I' ], $text ); // i think
		// this would work without mb_internal_encoding('utf-8');
		return $text;
	}

	function latinToLower( $text ) {
		$text = str_replace( 'I', 'ı', $text );
		$text = mb_strtolower( $text );
		// $text = str_replace( [ 'i', 'İ' ], [ 'ı', 'i' ], $text );
		return $text;
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			// separating -{XINHUA}-s is not needed,
			// they are already replaced with 00 bytes.
			$text = preg_replace( '/YUY/u', 'ЮУЙ', $text );
			$text = preg_replace( '/(?<=\d)e/u', 'е', $text );
			// $text = preg_replace( "/d'İvuar/u", 'д@Ивуар', $text ); // moved
			// this into convertLowercasedTatarWordLatToCyrl after parent::translate
			$w = 'a-zA-ZäəÄƏöɵÖƟüÜıİşŞçÇñÑğĞ\'';
			$words = preg_split( "/([$w]+)/u", $text,
				null, PREG_SPLIT_DELIM_CAPTURE );
			$wordsCount = count( $words );
			for ( $i = 1; $i < $wordsCount; $i += 2 ) {
				$words[$i] = preg_split( '/(?=\')/u', $words[$i], 2 );
				if ( count( $words[$i] ) == 1 ){
					if (
						$this->isRomanNumeral( $words[$i][0] )
						// &&!( $words[$i+1][0]=='.' && strlen( $words[$i] )==1 )
						// && strlen( $words[$i] )>1
						&& ( preg_match( '/^($|[ —])/u', $words[$i + 1] ) )
					) {
						$words[$i] = $words[$i][0];
						continue;
					} else {
						$words[$i] = $this->convertCamelcasedTatarWordLatToCyrl( $words[$i][0] );
					}
				} else {
					if ( $this->isRomanNumeral( $words[$i][0] ) ) {
						$words[$i][1] =
							$this->convertCamelcasedTatarWordLatToCyrl( $words[$i][1] );
						$words[$i] = implode( $words[$i] );
					} else {
						$words[$i] = implode( $words[$i] );
						$words[$i] = $this->convertCamelcasedTatarWordLatToCyrl( $words[$i] );
					}
				}
			}// i
			$text = implode( $words );
			// $text = preg_replace( '/д@Ивуар/u', "д'Ивуар", $text ); // moved
			// this into convertLowercasedTatarWordLatToCyrl after parent::translate
		} elseif ( $toVariant == 'tt-latn-x-2000' ) {
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
			// 5енче, 5е
			$text = preg_replace( '/(?<=\d)е/u', 'e', $text );
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
				} elseif ( preg_match( '/^АКШ[а-яёәөүҗңһ]*$/u', $words[$i] ) ) {
					// fix single к which is by default converted as k
					// but in some abbreviations it should be converted as q
					$words[$i] = 'AQ'
						. $this->convCapitalisedWordCyrlLat(
							mb_substr( $words[$i], 2 )
							);
				} else {
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
					if (
						preg_match( '/-$/', $words[$i - 1] )
						&& (
							preg_match(
								'/^('
								.'n[eı](ñ|[ğq]ı|[gk]e)?$'
								.'|(t[aä]|d[aä])([ğq]ı|[gk]e)?$'
								.'|(t[aä]n|d[aä]n|ğa|gä|qa|kä)(ç[aä])?$'
								.'|l(ıq?|ek?)'
								.'|[eı](n|$)'
								.')/ui',
								$words[$i]
							)
							|| preg_match(
								'/^ma?k$/ui',
								$words[$i - 2]
							)
						)
					){
						// $words[$i-1]='\'';
						$words[$i - 1] = preg_replace( '/-$/', '\'', $words[$i - 1] );
					}
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

	function convertCamelcasedTatarWordLatToCyrl( $text ) {
		$text = preg_split( '/(?=[A-ZÄÖÜİŞÇÑĞ])/u', $text );
		$wordsICount = count( $text );
		if ( $wordsICount == 1 || $text[0] != '' ) {
			// if uppercase letter is not found,
			// should be 1 element in array
			// if word starts with upper case letter,
			// there is first empty string in the preg_split result
			// empty string need not to be converted
			// if no empty string, it should start with lower case
			// lower case string need not to be lowercased
			$text[0] =
				$this->convertLowercasedTatarWordLatToCyrl( $text[0] );
		}
		for ( $j = 1; $j < $wordsICount; $j++ ) {
			// if $wordsICount == 1 this inside part does not run
			$text[$j] =
				$this->convCapitalisedWordLatCyrl( $text[$j] );
		}
		$text = implode( $text );
		return $text;
	}

	function isRomanNumeral( $text ) {
		return preg_match(
			'/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/',
			// from https://stackoverflow.com/a/267405
			$text
		);
	}

} // class TtConverter extends LanguageConverter {

/**
 * Tatar
 *
 * @ingroup Language
 */
class LanguageTt extends Language {
	function __construct() {
		parent::__construct();

		$variants = [ 'tt', 'tt-latn-x-2000', 'tt-cyrl' ];
		$variantfallbacks = [
			'tt' => 'tt-cyrl',
			'tt-cyrl' => 'tt',
			'tt-latn-x-2000' => 'tt',
		];

		$this->mConverter = new TtConverter( $this, 'tt', $variants, $variantfallbacks );
	}
}
