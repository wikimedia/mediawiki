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
 * @ingroup Language
 */

require_once __DIR__ . '/../LanguageConverter.php';

/**
 * @ingroup Language
 */
class TtConverter extends LanguageConverter {
	public $toLatin = array(
		'а' => 'a', 'А' => 'A',
		'ә' => 'ə', 'Ә' => 'Ə',
		'б' => 'b', 'Б' => 'B',
		'в' => 'v', 'В' => 'V',
		'г' => 'g', 'Г' => 'G',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ж' => 'j', 'Ж' => 'J',
		'җ' => 'c', 'Җ' => 'C',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'İ',
		'й' => 'y', 'Й' => 'Y',
		'к' => 'k', 'К' => 'K',
		'л' => 'l', 'Л' => 'L',
		'м' => 'm', 'М' => 'M',
		'н' => 'n', 'Н' => 'N',
		'ң' => 'ñ', 'Ң' => 'Ñ',
		'о' => 'o', 'О' => 'O',
		'ө' => 'ɵ', 'Ө' => 'Ɵ',
		'п' => 'p', 'П' => 'P',
		'р' => 'r', 'Р' => 'R',
		'с' => 's', 'С' => 'S',
		'т' => 't', 'Т' => 'T',
		'у' => 'u', 'У' => 'U',
		'ү' => 'ü', 'Ү' => 'Ü',
		'ф' => 'f', 'Ф' => 'F',
		'х' => 'x', 'Х' => 'X',
		'һ' => 'h', 'Һ' => 'H',
		'ц' => 'ts', 'Ц' => 'Ts',
		'ч' => 'ç', 'Ч' => 'Ç',
		'ш' => 'ş', 'Ш' => 'Ş',
		'щ' => 'şç', 'Щ' => 'Şç',
		'ъ' => '', 'Ъ' => '',
		'ы' => 'ı', 'Ы' => 'I',
		'ь' => '', 'Ь' => '',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'я' => 'ya', 'Я' => 'Ya',
	);

	public $toCyrillic = array(
		'a' => 'а', 'A' => 'А',
		'ə' => 'ә', 'Ə' => 'Ә',
		'ä' => 'ә', 'Ä' => 'Ә',
		'b' => 'б', 'B' => 'Б',
		'c' => 'җ', 'C' => 'Җ',
		'ç' => 'ч', 'Ç' => 'Ч',
		'd' => 'д', 'D' => 'Д',
		'e' => 'э', 'E' => 'Э',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'ğ' => 'г', 'Ğ' => 'Г',
		'h' => 'һ', 'H' => 'Һ',
		'i' => 'и', 'İ' => 'И',
		'ı' => 'ы', 'I' => 'Ы',
		'j' => 'ж', 'J' => 'Ж',
		'k' => 'к', 'K' => 'К',
		'l' => 'л', 'L' => 'Л',
		'm' => 'м', 'M' => 'М',
		'n' => 'н', 'N' => 'Н',
		'ñ' => 'ң', 'Ñ' => 'Ң',
		'o' => 'о', 'O' => 'О',
		'ɵ' => 'ө', 'Ɵ' => 'Ө',
		'ö' => 'ө', 'Ö' => 'Ө',
		'p' => 'п', 'P' => 'П',
		'q' => 'к', 'Q' => 'К',
		'r' => 'р', 'R' => 'Р',
		's' => 'с', 'S' => 'С',
		'ş' => 'ш', 'Ş' => 'Ш',
		't' => 'т', 'T' => 'Т',
		'u' => 'у', 'U' => 'У',
		'ü' => 'ү', 'Ü' => 'Ү',
		'v' => 'в', 'V' => 'В',
		'w' => 'в', 'W' => 'В',
		'x' => 'х', 'X' => 'Х',
		'y' => 'й', 'Y' => 'Й',
		'z' => 'з', 'Z' => 'З',
		'\'' => 'э',
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'tt-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'tt-latn' => new ReplacementArray( $this->toLatin ),
			'tt' => new ReplacementArray()
		);
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'tt-cyrl' ) {
			$text = str_replace( 'ye', 'е', $text );
			$text = str_replace( 'Ye', 'Е', $text );
			$text = str_replace( 'YE', 'Е', $text );
			// "е" after consonants, otherwise "э" (see above)
			$text = preg_replace( '/([BVGDJZYKLMNPRSTFXCWQH])E/u', '$1Е', $text );
			$text = preg_replace( '/([bvgdjzyklmnprstfxcwqh])e/ui', '$1е', $text );
		}
		return parent::translate( $text, $toVariant );
	}

}

/**
 * Uzbek
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
