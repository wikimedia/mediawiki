<?php
/**
 * Crimean Tatar specific code.
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

/**
 * @ingroup Language
 */
class CrhConverter extends LanguageConverter {
	public $toLatin = [
		'а' => 'a', 'А' => 'A',
		'б' => 'b', 'Б' => 'B',
		'в' => 'v', 'В' => 'V',
		'гъ' => 'ğ', 'Гъ' => 'Ğ', 'ГЪ' => 'Ğ',
		'г' => 'g', 'Г' => 'G',
		'дж' => 'c', 'Дж' => 'C', 'ДЖ' => 'C',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ж' => 'j', 'Ж' => 'J',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'I',
		'й' => 'y', 'Й' => 'Y',
		'к' => 'k', 'К' => 'K',
		'къ' => 'q', 'Къ' => 'Q', 'КЪ' => 'Q',
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
		'ҳ' => 'h', 'Ҳ' => 'H',
		'ц' => 'c', 'Ц' => 'C',
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		// note: at the beginning of a word and right after a consonant, only "s" is used
		'ц' => 'ts', 'Ц' => 'Ts',
		'қ' => 'q', 'Қ' => 'Q',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'я' => 'ya', 'Я' => 'Ya',
		'ъ' => 'ʼ',
	];

	public $toCyrillic = [
		'a' => 'а', 'A' => 'А',
		'b' => 'б', 'B' => 'Б',
		'd' => 'д', 'D' => 'Д',
		// at the beginning of a word and after a vowel, "э" is used instead of "e"
		// (see regex below)
		'e' => 'э', 'E' => 'Э',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'g‘' => 'ғ', 'G‘' => 'Ғ', 'gʻ' => 'ғ', 'Gʻ' => 'Ғ',
		'h' => 'ҳ', 'H' => 'Ҳ',
		'i' => 'и', 'I' => 'И',
		'k' => 'к', 'K' => 'К',
		'l' => 'л', 'L' => 'Л',
		'm' => 'м', 'M' => 'М',
		'n' => 'н', 'N' => 'Н',
		'o' => 'о', 'O' => 'О',
		'p' => 'п', 'P' => 'П',
		'r' => 'р', 'R' => 'Р',
		's' => 'с', 'S' => 'С',
		't' => 'т', 'T' => 'Т',
		'u' => 'у', 'U' => 'У',
		'v' => 'в', 'V' => 'В',
		'x' => 'х', 'X' => 'Х',
		'z' => 'з', 'Z' => 'З',
		'j' => 'ж', 'J' => 'Ж',
		'o‘' => 'ў', 'O‘' => 'Ў', 'oʻ' => 'ў', 'Oʻ' => 'Ў',
		'yo‘' => 'йў', 'Yo‘' => 'Йў', 'yoʻ' => 'йў', 'Yoʻ' => 'Йў',
		'ts' => 'ц', 'Ts' => 'Ц',
		'q' => 'қ', 'Q' => 'Қ',
		'yo' => 'ё', 'Yo' => 'Ё',
		'yu' => 'ю', 'Yu' => 'Ю',
		'ch' => 'ч', 'Ch' => 'Ч',
		'sh' => 'ш', 'Sh' => 'Ш',
		'y' => 'й', 'Y' => 'Й',
		'ya' => 'я', 'Ya' => 'Я',
		'ʼ' => 'ъ',
	];

	function loadDefaultTables() {
		$this->mTables = [
			'crh-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'crh-latn' => new ReplacementArray( $this->toLatin ),
			'crh' => new ReplacementArray()
		];
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'crh-cyrl' ) {
			$text = str_replace( 'ye', 'е', $text );
			$text = str_replace( 'Ye', 'Е', $text );
			$text = str_replace( 'YE', 'Е', $text );
			// "е" after consonants, otherwise "э" (see above)
			$text = preg_replace( '/([BVGDJZYKLMNPRSTFXCWQʻ‘H])E/u', '$1Е', $text );
			$text = preg_replace( '/([bvgdjzyklmnprstfxcwqʻ‘h])e/ui', '$1е', $text );
		}
		return parent::translate( $text, $toVariant );
	}

}

/**
 * Crimean Tatar
 *
 * @ingroup Language
 */
class LanguageCrh extends Language {
	function __construct() {
		parent::__construct();

		$variants = [ 'crh', 'crh-latn', 'crh-cyrl' ];
		$variantfallbacks = [
			'crh' => 'crh-latn',
			'crh-cyrl' => 'crh',
			'crh-latn' => 'crh',
		];

		$this->mConverter = new CrhConverter( $this, 'crh', $variants, $variantfallbacks );
	}
}
