<?php
/**
 * Uzbek specific code.
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
 * @ingroup Language
 */

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class UzConverter extends LanguageConverter {
	/**
	 * @var array Cyrillic to Latin
	 *
	 * Rules:
	 * 1. ц = ts after a vowel, otherwise 's'.
	 */
	public $toLatin = array(
		'а' => 'a', 'А' => 'A',
		'б' => 'b', 'Б' => 'B',
		'в' => 'v', 'В' => 'V',
		'г' => 'g', 'Г' => 'G',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ж' => 'j', 'Ж' => 'J',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'I',
		'й' => 'y', 'Й' => 'Y',
		'к' => 'k', 'К' => 'K',
		'л' => 'l', 'Л' => 'L',
		'м' => 'm', 'М' => 'M',
		'н' => 'n', 'Н' => 'N',
		'о' => 'o', 'О' => 'O',
		'п' => 'p', 'П' => 'P',
		'р' => 'r', 'Р' => 'R',
		'с' => 's', 'С' => 'S',
		'т' => 't', 'Т' => 'T',
		'у' => 'u', 'У' => 'U',
		'ф' => 'f', 'Ф' => 'F',
		'х' => 'x', 'Х' => 'X',
		// rule 1
		'ц' => 's', 'Ц' => 'S',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'ъ' => 'ʼ', 'Ъ' => 'ʼ',
		'ь' => '', 'Ь' => '',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'я' => 'ya', 'Я' => 'Ya',
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		'қ' => 'q', 'Қ' => 'Q',
		'ғ' => 'gʻ', 'Ғ' => 'Gʻ',
		'ҳ' => 'h', 'Ҳ' => 'H',
	);

	/**
	 * @var array Latin to Cyrillic
	 *
	 * Rules:
	 * 1. 'e' = 'э' at the beginning of a word or after a vowel (except for after an 'e'),
	 * 	otherwise 'e' = 'е': iye = ийе (not 'ийэ'), konveyer = конвейер (not 'конвейэр'),
	 * 	pleyer = плейер (not 'плейэр'), etc.
	 * 2. There is no reliable rule to convert 's' to 'ц', 's' can be transliterated to 'с' or 'ц':
	 * 	sirk = цирк, sen = сен. Use $tSWords array below to convert words that have 'ц' in them.
	 * 3. Replace compounds before ъ rule
	 * FIXME: add a rule to convert ʼ to the uppercase Ъ when letters before and after that symbol
	 * 		  are also uppercase
	 * FIXME: add words that have the cyrillic 'ь' in them
	 */
	public $toCyrillic = array(
		'a' => 'а', 'A' => 'А',
		'b' => 'б', 'B' => 'Б',
		'd' => 'д', 'D' => 'Д',
		// rule 1
		'e' => 'е', 'E' => 'Е',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'h' => 'ҳ', 'H' => 'Ҳ',
		'i' => 'и', 'I' => 'И',
		'j' => 'ж', 'J' => 'Ж',
		'k' => 'к', 'K' => 'К',
		'l' => 'л', 'L' => 'Л',
		'm' => 'м', 'M' => 'М',
		'n' => 'н', 'N' => 'Н',
		'o' => 'о', 'O' => 'О',
		'p' => 'п', 'P' => 'П',
		'q' => 'қ', 'Q' => 'Қ',
		'r' => 'р', 'R' => 'Р',
		// rule 2
		's' => 'с', 'S' => 'С',
		't' => 'т', 'T' => 'Т',
		'u' => 'у', 'U' => 'У',
		'v' => 'в', 'V' => 'В',
		'x' => 'х', 'X' => 'Х',
		'y' => 'й', 'Y' => 'Й',
		'z' => 'з', 'Z' => 'З',
		// START COMPOUNDS
		// NOTE: If you change something here change the compounds converter below too.
		'o‘' => 'ў', 'O‘' => 'Ў',
			'oʻ' => 'ў', 'Oʻ' => 'Ў',
		'g‘' => 'ғ', 'G‘' => 'Ғ',
			'gʻ' => 'ғ', 'Gʻ' => 'Ғ',
		'sh' => 'ш', 'Sh' => 'Ш', 'SH' => 'Ш',
		'ch' => 'ч', 'Ch' => 'Ч', 'CH' => 'Ч',
		'yo‘' => 'йў', 'Yo‘' => 'Йў', 'YO‘' => 'ЙЎ',
			'yoʻ' => 'йў', 'Yoʻ' => 'Йў', 'YOʻ' => 'ЙЎ',
		'ts' => 'ц', 'Ts' => 'Ц', 'TS' => 'Ц',
		'yo' => 'ё', 'Yo' => 'Ё', 'YO' => 'Ё',
		'yu' => 'ю', 'Yu' => 'Ю', 'YU' => 'Ю',
		'ya' => 'я', 'Ya' => 'Я', 'YA' => 'Я',
		// rule 1
		'ye' => 'е', 'Ye' => 'Е', 'YE' => 'Е',
		// END COMPOUNDS
		'ʼ' => 'ъ',
		// quotes
		'„' => '«', '“' => '»',
	);

	// Most words that start with an 's' and are transliterated to a 'с' (сен, суд, салом, ...)
	// The following words start with an 's' but need to be transliterated to 'ц'
	public $tSWords = array(
		// The following is tricky because there is a word form санга so maybe disable it for now
		//'sanga' => 'цанга',
		'sapfa' => 'цапфа',
		'sedra' => 'цедра',
		'seziy' => 'цезий',
		'seytnot' => 'цейтнот',
		'sellofan' => 'целлофан',
		'selluloid' => 'целлулоид',
		'sellyuloza' => 'целлюлоза',
		'selsiy' => 'цельсий',
		'sement' => 'цемент',
		'sementla' => 'цементла', // this is the stem of the verb
		'senz' => 'ценз',
		'senzor' => 'цензор',
		'senzura' => 'цензура',
		'sent' => 'цент',
		'sentner' => 'центнер',
		'sentnerli' => 'центнерли',
		'sentnerchi' => 'центнерчи',
		'sentralizm' => 'централизм',
		'sentrizm' => 'центризм',
		'sentrist' => 'центрист',
		'sentrifuga' => 'центрифуга',
		'seriy' => 'церий',
		'sesarka' => 'цесарка',
		'sex' => 'цех',
		'sian' => 'циан',
		'sianli' => 'цианли',
		'sivilizatsiya' => 'цивилизация',
		// The following word exists in the dictionary, but the correct form is сигара, so disabling it.
		//'sigara' => 'цигара',
		'sikl' => 'цикл',
		'siklik' => 'циклик',
		'sikllashtir' => 'цикллаштир', // this is the stem of the verb
		'sikkli' => 'циклли',
		'siklon' => 'циклон',
		'siklotron' => 'циклотрон',
		'silindr' => 'цилиндр',
		'silindrik' => 'цилиндрик',
		'silindrli' => 'цилиндрли',
		'singa' => 'цинга',
		'sink' => 'цинк',
		'sinkograf' => 'цинкограф',
		'sinkografiya' => 'цинкография',
		'sirk' => 'цирк',
		'sirkoniy' => 'цирконий',
		'sirkul' => 'циркуль',
		'sirkulyar' => 'циркуляр',
		'sirkchi' => 'циркчи',
		'sirroz' => 'цирроз',
		'sisterna' => 'цистерна',
		'sisternali' => 'цистернали',
		'sistit' => 'цистит',
		'sitata' => 'цитата',
		'sitatabozlik' => 'цитатабозлик',
		// The following is not a word, so disabling it.
		//'sito-' => 'цито-',
		'sitodiagnostika' => 'цитодиагностика',
		'sitokimyo' => 'цитокимё',
		'sitoliz' => 'цитолиз',
		'sitologiya' => 'цитология',
		'sitrus' => 'цитрус',
		'siferblat' => 'циферблат',
		'siferblatli' => 'циферблатли',
		'sikol' => 'цоколь',
		'sunami' => 'цунами',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'uz-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'uz-latn' => new ReplacementArray( $this->toLatin ),
			'uz' => new ReplacementArray()
		);
	}

	/**
	 * Translate Cyrillic to Latin
	 * @param $text
	 * @return string
	 */
	function translateToLatn ( $text ) {
		$vowels = "аАеЕёЁиИоОуУэЭюЮяЯўЎ";
		$uppercase = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЪЬЭЮЯЎҚҒҲ';
		$exceptions = array(
			// rule 1
			'ц'=> 'ts', 'Ц' => 'Ts', 'Ц_' => 'TS'
		);
		// After a vowel
		$text = preg_replace_callback(
			'/([' . $vowels . '])([цЦ])(' . $uppercase . ')/ui',
			function( $matches ) use ( &$exceptions, &$uppercase ) {
//				echo "------------\n";
//				var_dump($matches);
				// if the previous and next letters are uppercase, then make both letters (ts) uppercase
				if ( $matches[2] === 'Ц' &&
					 strpos( $uppercase, $matches[1] ) !== FALSE &&
					 ( $matches[3] && strpos( $uppercase, $matches[3] ) )
				) {
					$result = 'Ц_';
				} else {
					$result = $matches[2];
				}
				return $matches[1] . $exceptions[ $result ];
			},
			$text
		);
		return $text;
	}

	/**
	 * Translate Latin to Cyrillic
	 * @param $text
	 * @return string
	 */
	function translateToCyrl ( $text ) {
		// Replace ц ($tSWords) words first. Replace only the first letter because we want to
		// preserve the case of other letters.
		$text = preg_replace_callback(
			'/\b('. implode( '|', array_keys( $this->tSWords ) ) .')/ui',
			function( $matches ) {
				if ( ctype_upper( $matches[1][0]) ) {
					$result = 'Ц';
				} else {
					$result = 'ц';
				}
				return $result . substr( $matches[1], 1 );
			},
			$text
		);

		// Replace COMPOUNDS next
		$text = preg_replace_callback(
			'/(o‘|oʻ|g‘|gʻ|sh|ch|yo‘|yoʻ|ts|yo|yu|ya|ye)/ui',
			function( $matches ) {
				if ( ctype_upper( $matches[1] ) ) {
					$result = ucfirst( $matches[1] );
				} else {
					$result = $matches[1];
				}
				return $this->toCyrillic[ $result ];
			},
			$text
		);

		$exceptions = array(
			// rule 1
			'e'=> 'э', 'E' => 'Э',
		);
		// After a vowel
		$text = preg_replace_callback(
			'/([aeiou]|o‘)([e])/ui',
			function( $matches ) use ( &$exceptions ) {
				if ( ctype_upper( $matches[2] ) ) {
					$result = strtoupper( $matches[2] );
				} else {
					$result = $matches[2];
				}
				return $matches[1] . $exceptions[ $result ];
			},
			$text
		);
		// Beginning of a word
		$text = preg_replace_callback(
			'/\b([e])/ui',
			function( $matches ) use ( &$exceptions ) {
				if ( ctype_upper( $matches[1] ) ) {
					$result = strtoupper( $matches[1] );
				} else {
					$result = $matches[1];
				}
				return $exceptions[ $result ];
			},
			$text
		);
		return $text;
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'uz-cyrl' ) {
			$text = $this->translateToCyrl( $text );
		} else if ( $toVariant == 'uz-latn' ) {
			$text = $this->translateToLatn( $text );
		}
		return parent::translate( $text, $toVariant );
	}
}

/**
 * Uzbek
 *
 * @ingroup Language
 */
class LanguageUz extends Language {
	function __construct() {
		global $wgHooks;
		parent::__construct();

		$variants = array( 'uz', 'uz-latn', 'uz-cyrl' );
		$variantFallbacks = array(
			'uz' => 'uz-latn',
			'uz-cyrl' => 'uz',
			'uz-latn' => 'uz',
		);

		$this->mConverter = new UzConverter( $this, 'uz', $variants, $variantFallbacks );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}
}
