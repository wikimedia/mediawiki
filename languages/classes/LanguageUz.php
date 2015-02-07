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
		// note: at the beginning of a word and right after a consonant, only "s" is used
		'ц' => 'ts', 'Ц' => 'Ts',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'ъ' => 'ʼ',
		'ь' => '',
		'э' => 'e', 'Э' => 'E',
		'ю' => 'yu', 'Ю' => 'Yu',
		'я' => 'ya', 'Я' => 'Ya',
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		'қ' => 'q', 'Қ' => 'Q',
		'ғ' => 'gʻ', 'Ғ' => 'Gʻ',
		'ҳ' => 'h', 'Ҳ' => 'H',
	);

	public $toCyrillic = array(
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
	);

	function loadDefaultTables() {
		$this->mTables = array(
			'uz-cyrl' => new ReplacementArray( $this->toCyrillic ),
			'uz-latn' => new ReplacementArray( $this->toLatin ),
			'uz' => new ReplacementArray()
		);
	}

	function translate( $text, $toVariant ) {
		if ( $toVariant == 'uz-cyrl' ) {
			$exceptions = array(
				// e = е at the beginning of a word or after consonants
				// otherwise e = э (see above)
				'e'=> 'е', 'E' => 'Е',
				// s = ц at the beginning of a word and right after a consonant
				// otherwise s = с ( see above)
				's'=> 'ц', 'S' => 'Ц',
			);
			$text = str_replace( 'ye', 'е', $text );
			$text = str_replace( 'Ye', 'Е', $text );
			$text = str_replace( 'YE', 'Е', $text );
			// After consonant rules - replace exceptions matching the case
			$text = preg_replace_callback(
				'/([bvgdjzyklmnprstfxcwqh‘])([es])/ui',
				function( $matches ) {
					global $exceptions;
					if ( ctype_upper( $matches[2] ) ) {
						$result = strtoupper( $matches[2] );
					} else {
						$result = $matches[2];
					}
					return $matches[1] . $exceptions[ $result ];
				},
				$text
			);
			// Beginning of word rules - replace exceptions matching the case
			$text = preg_replace_callback(
				'/\b([es])/ui',
				function( $matches ) {
					global $exceptions;
					if ( ctype_upper( $matches[1] ) ) {
						$result = strtoupper( $matches[1] );
					} else {
						$result = $matches[1];
					}
					return $exceptions[ $result ];
				},
				$text
			);
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
		$variantfallbacks = array(
			'uz' => 'uz-latn',
			'uz-cyrl' => 'uz',
			'uz-latn' => 'uz',
		);

		$this->mConverter = new UzConverter( $this, 'uz', $variants, $variantfallbacks );
		$wgHooks['PageContentSaveComplete'][] = $this->mConverter;
	}
}
