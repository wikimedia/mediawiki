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

require_once( __DIR__ . '/../LanguageConverter.php' );

/**
 * @ingroup Language
 */
class UzConverter extends LanguageConverter {
	var $toLatin = array(
		'а' => 'a', 'А' => 'A',
		'б' => 'b', 'Б' => 'B',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'э' => 'e', 'Э' => 'E',
		'в' => 'v', 'В' => 'V',
		'х' => 'x', 'Х' => 'X',
		'ғ' => 'gʻ', 'Ғ' => 'Gʻ',
		'г' => 'g', 'Г' => 'G',
		'ҳ' => 'h', 'Ҳ' => 'H',
		'ж' => 'j', 'Ж' => 'J',
		'з' => 'z', 'З' => 'Z',
		'и' => 'i', 'И' => 'I',
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
		'ц' => 'c', 'Ц' => 'C',
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		'ц' => 'ts', 'Ц' => 'Ts', // note: at the beginning of a word and right after a consonant, only "s" is used
		'қ' => 'q', 'Қ' => 'Q',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ю' => 'yu', 'Ю' => 'Yu',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'й' => 'y', 'Й' => 'Y',
		'я' => 'ya', 'Я' => 'Ya',
		'ъ' => 'ʼ',
	);

	var $toCyrillic = array(
		'a' => 'а', 'A' => 'А',
		'b' => 'б', 'B' => 'Б',
		'd' => 'д', 'D' => 'Д',
		'e' => 'е', 'E' => 'Е',
		' e' => ' э', ' E' => ' Э', // "э" is used at the beginning of a word instead of "e"
		'ye' => 'е', 'Ye' => 'Е',
		'f' => 'ф', 'F' => 'Ф',
		'g' => 'г', 'G' => 'Г',
		'g‘' => 'ғ', 'G‘' => 'Ғ', 'gʻ' => 'ғ', 'Gʻ' => 'Ғ',
		'h'  => 'ҳ', 'H' => 'Ҳ',
		'i' => 'и', 'I' => 'И',
		'k'  => 'к', 'K' => 'К',
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
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'uz-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'uz-latn' => new ReplacementArray( $this->toLatin ),
			'uz'      => new ReplacementArray()
		);
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
		$variantfallbacks = array(
			'uz'    => 'uz-latn',
			'uz-cyrl' => 'uz',
			'uz-latn' => 'uz',
		);

		$this->mConverter = new UzConverter( $this, 'uz', $variants, $variantfallbacks );
		$wgHooks['ArticleSaveComplete'][] = $this->mConverter;
	}
}
