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

require_once( __DIR__ . '/../LanguageConverter.php' );

/**
 * Converter for Karachay language. It converts from Cyrillic to Latin. Cyrillic is by default.
 *
 * @ingroup Language
*/
class KrcConverter extends LanguageConverter {

	function loadDefaultTables() {
		$this->mTables = array(
				'krc-cyrl' => new ReplacementArray(),
				'krc-latn' => new ReplacementArray( $this->toLatn ),
				'krc'    => new ReplacementArray()
		);
	}

	// Univocal replacements table.
	private $toLatn = array(
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
	private $toLatnAfterV = array(
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
	private $toLatnAfterC = array(
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
	private $toLatnInB = array(
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
	private function capU( $original, $out ) {
		if ( preg_match( '/[уяюеё]/um', $original ) ) {
			return $out;
		else
			return ucfirst($out);
		}
	}

	// This function replaces series of 'У' depending on context.
	private function replaceU( $left, $text, $right ) {
		$patternOfLC = '/[bvcgğdjzykqlmnñprstfhçşw]/umi';
		$patternOfLV = '/[aeöiouüıяюеё]/umi';
		$patternOfRC = '/[bvcgğdjzykqlmnñprstfhçşwüяюеё]/umi';
		$patternOfRV = '/[aeöiouı]/umi';
		if ( $text === "" ) {
			return $left . $right;
		}
		if ( preg_match( $patternOfLC, $left ) ) {
			return $left . $this->replaceU( $this->capU( mb_substr( $text, 0, 1 ), "u" ), mb_substr( $text, 1 ), $right );
		} elseif ( preg_match( $patternOfLV, $left ) ) {
			return $left . $this->replaceU( $this->capU( mb_substr( $text, 0, 1 ), "w" ), mb_substr( $text, 1 ), $right );
		} elseif ( preg_match( $patternOfRC, $right ) ) {
			return $this->replaceU( $left, mb_substr( $text, 0, -1 ), $this->capU( mb_substr( $text, -1 ), "u" ) ) . $right;
		} elseif ( preg_match( $patternOfRV, $right ) ) {
			return $this->replaceU( $left, mb_substr( $text, 0, -1 ), $this->capU( mb_substr( $text, -1 ), "w" ) ) . $right;
		} else {
			return $left . $this->replaceU( $this->capU( mb_substr( $text, 0, 1 ), "u" ), mb_substr( $text, 1 ), $right );
		}
	}

	// This function replaces vowels.
	private function replaceV( $left, $v ) {
		if ( preg_match('/[bvcgğdjzykqlmnñprstfhçşw]/umi', $left) ) {
			return $left . $this->capU($v, strtr($v, $this->toLatnAfterC));
		} elseif ( preg_match('/[aouıieаюеёъьü]/umi', $left) ) {
			return $left . $this->capU($v, strtr($v, $this->toLatnAfterV));
		} else
			return $left . $this->capU($v, strtr( $v, $this->toLatnInB ) );
	}

	// Translation of text.
	public function translate( $text, $toVariant='cyrl' ) {
		if ( $toVariant !== 'krc-latn' ){
			return $text;
		}
		$text = strtr($text, $this->toLatn);
		while ( preg_match( '/([^у]?)(у+)([^у]?)/miu', $text, $matches ) )
			$text = preg_replace_callback( '/([^у]?)(у+)([^у]?)/miu', function ( $match ) {
			return $this->replaceU( $match[1], $match[2], $match[3] );
		}, $text, 1 );
		while ( preg_match( '/(.?)([яюеё])/emiu', $text, $matches ) ) {
			$text = preg_replace_callback( '/(.?)([яюеё])/miu', function ( $match ) {
				return $this->replaceV( $match[1], $match[2] );
			}, $text, 1 );
		}
		$text = preg_replace( '/[ьъ]/umi', '', $text );
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
class LanguageKrc_latn extends LanguageKrc {
	// Redefining getMessage() function so that it returns converted Cyrillic message.
	// Special messages file is not needed for this variant.
	public function getMessage( $key ) {
		$out = self::$dataCache -> getSubitem( $this -> mCode, 'messages', $key );
		return $this->mConverter->translate( $out,'krc-latn' );
	}

	function __construct() {
		parent::__construct();
		$this -> mCode = 'krc';
		self::getLocalisationCache();
	}
}