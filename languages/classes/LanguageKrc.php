<?php
/**
 * Karachay language specific code.
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
 * Converter for Karachay language. It converts from Cyrillic to Latin. Cyrillic is by default.
 *
 * @ingroup Language
*/
class KrcConverter extends LanguageConverter {

	function loadDefaultTables() {
		$this->mTables = array(
				'krc-cyrl' => new ReplacementArray(),
				'krc-latn' => new ReplacementArray( self::$toLatn ),
				'krc'    => new ReplacementArray()
		);
	}

	// Univocal replacements table.
	private static $toLatn = array(
		'а' => 'a',
		'б' => 'b',
		'в' => 'v',
		'г' => 'g',
		'д' => 'd',
		'ж' => 'j',
		'з' => 'z',
		'и' => 'i',
		'й' => 'y',
		'к' => 'k',
		'л' => 'l',
		'м' => 'm',
		'н' => 'n',
		'о' => 'o',
		'п' => 'p',
		'р' => 'r',
		'с' => 's',
		'т' => 't',
		'ў' => 'w',
		'ф' => 'f',
		'х' => 'h',
		'ч' => 'ç',
		'ш' => 'ş',
		'ы' => 'ı',
		'э' => 'e',
		'гъ' => 'ğ',
		'дж' => 'c',
		'къ' => 'q',
		'нг' => 'ñ',
		'ц' => 'ts',
		'щ' => 'şç',
		'А' => 'A',
		'Б' => 'B',
		'В' => 'V',
		'Г' => 'G',
		'Д' => 'D',
		'Ж' => 'J',
		'З' => 'Z',
		'И' => 'İ',
		'Й' => 'Y',
		'К' => 'K',
		'Л' => 'L',
		'М' => 'M',
		'Н' => 'N',
		'О' => 'O',
		'П' => 'P',
		'Р' => 'R',
		'С' => 'S',
		'Т' => 'T',
		'Ў' => 'W',
		'Ф' => 'F',
		'Х' => 'H',
		'Ч' => 'Ç',
		'Ш' => 'Ş',
		'Ы' => 'I',
		'Э' => 'E',
		'Гъ' => 'Ğ',
		'Дж' => 'C',
		'Къ' => 'Q',
		'Нг' => 'ñ',
		'Ц' => 'Ts',
		'Щ' => 'Şç',
	);

	// Replacement table for vowels coming after vowel.
	private static $toLatnAfterV = array(
		'е' => 'ye',
		'ю' => 'yu',
		'ё' => 'yo',
		'я' => 'ya',
		'Е' => 'Ye',
		'Ю' => 'Yu',
		'Ё' => 'Yo',
		'Я' => 'Ya',
	);

	// Replacement table for vowels coming after consonants.
	private static $toLatnAfterC = array(
		'е' => 'e',
		'ю' => 'ü',
		'ё' => 'ö',
		'я' => 'â',
		'Е' => 'E',
		'Ю' => 'Ü',
		'Ё' => 'Ö',
		'Я' => 'Â',
	);

	// Replacement table for vowels in the beginning.
	private static $toLatnInB = array(
		'е' => 'ye',
		'ю' => 'ü',
		'ё' => 'ö',
		'я' => 'ya',
		'Е' => 'Ye',
		'Ю' => 'Ü',
		'Ё' => 'Ö',
		'Я' => 'Ya',
	);

	// This function sets proper case for 'Uu', depending on original 'Уу'.
	private static function capU( $original, $out ) {
		if ( preg_match( '/[уяюеё]/um', $original ) ) {
			return $out;
		} else {
			return ucfirst( $out );
		}
	}

	// This function replaces series of 'У' depending on context.
	public static function replaceU( $left, $u, $right ) {
		$patternOfLC = '/[bvcgğdjzykqlmnñprstfhçşw]/umi';
		$patternOfLV = '/[aeöiouüıяюеё]/umi';
		$patternOfRC = '/[bvcgğdjzykqlmnñprstfhçşwüяюеё]/umi';
		$patternOfRV = '/[aeöiouı]/umi';
		if ( $u === "" ) {
			return $left . $right;
		}
		if ( preg_match( $patternOfLC, $left ) ) {
			return $left . self::capU( $u, "u" ) . $right;
		} elseif ( preg_match( $patternOfLV, $left ) ) {
			return $left . self::capU( $u, "w" ) . $right;
		} elseif ( preg_match( $patternOfRC, $right ) ) {
			return $left . self::capU( $u, "u" ) . $right;
		} elseif ( preg_match( $patternOfRV, $right ) ) {
			return $left . self::capU( $u, "w" ) . $right;
		} else {
			return $left . self::capU( $u, "u" ) . $right;
		}
	}

	// This function replaces vowels.
	public static function replaceV( $left, $v ) {
		if ( preg_match( '/[bvcgğdjzykqlmnñprstfhçşw]/umi', $left ) ) {
			return $left . self::capU( $v, strtr( $v, self::$toLatnAfterC ) );
		} elseif ( preg_match('/[aouıieаюеёъьü]/umi', $left) ) {
			return $left . self::capU( $v, strtr( $v, self::$toLatnAfterV ) );
		} else
			return $left . self::capU( $v, strtr( $v, self::$toLatnInB ) );
	}

	// Translation of text.
	public function translate( $text, $toVariant='cyrl' ) {
		if ( $toVariant !== 'krc-latn' ){
			return $text;
		}
		// Maximum length to pass to mb_substr()
		$textLength = mb_strlen($text);
		$text = strtr($text, self::$toLatn);
		$done = "";
		$tocheck = $text;
		while ( preg_match( '/у/msiu', $tocheck ) ) {
			preg_match( '/([^у]*)(у)(.*)/smiu', $tocheck, $matches );
			$partOne = mb_substr( $matches[1], 0, -1, "UTF8" );
			$partTwo = mb_substr( $matches[1], -1, 1, "UTF8" );
			$partFour = mb_substr( $matches[3], 0, 1, "UTF8" );
			$partFive = mb_substr( $matches[3], 1, $textLength, "UTF8" );
			$donePart = $partOne . self::replaceU( $partTwo, $matches[2], $partFour );
			$donePartOne = mb_substr( $donePart, 0, -1, "UTF8");
			$donePartTwo = mb_substr( $donePart, -1, 1, "UTF8" );
			$done = $done . $partOne;
			$tocheck = self::replaceU( $partTwo, $matches[2], $partFour ) . $partFive;
		}
		$tocheck = $done . $tocheck;
		$done = "";
		while ( preg_match( '/[яюеё]/smiu', $tocheck ) ) {
			preg_match( '/([^яюеё]*)([яюеё])(.*)/smiu', $tocheck, $matches );
			$partOne = mb_substr( $matches[1], 0, -1, "UTF8" );
			$partTwo = mb_substr( $matches[1], -1, 1, "UTF8" );
			$done = $done . $partOne;
			$tocheck = self::replaceV( $partTwo, $matches[2] ) . $matches[3];
		}
		$text = preg_replace( '/[ьъ]/umi', '', $done . $tocheck );
		return $text;
	}
}
/**
 * Language class for Karachay language.
 *
 * @ingroup Language
 */
class LanguageKrc extends Language {
	function __construct() {
		parent::__construct();
		$variants = array( 'krc', 'krc-latn' );
		$variantfallbacks = array(
			'krc-latn' => 'krc',
		);
		$this->mConverter = new KrcConverter( $this, 'krc', $variants, $variantfallbacks );
	}
}

/**
 * Language class for Latin variant of Karachay language.
 *
 * @ingroup Language
 */
class LanguageKrcLatn extends LanguageKrc {
	// Redefining getMessage() function so that it returns converted Cyrillic message.
	// Special messages file is not needed for this variant.
	public function getMessage( $key ) {
		$out = self::$dataCache -> getSubitem( $this -> mCode, 'messages', $key );
		return $this->mConverter->translate( $out, 'krc-latn' );
	}

	function __construct() {
		parent::__construct();
		$this -> mCode = 'krc';
		self::getLocalisationCache();
	}
}
