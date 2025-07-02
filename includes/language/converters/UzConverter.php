<?php
/**
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
 */

use MediaWiki\Language\LanguageConverter;
use Wikimedia\ReplacementArray;

/**
 * Uzbek specific code.
 *
 * @ingroup Languages
 */
class UzConverter extends LanguageConverter {
	private const TO_LATIN = [
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
		'ў' => 'oʻ', 'Ў' => 'Oʻ',
		// note: at the beginning of a word and right after a consonant, only "s" is used
		'ц' => 'ts', 'Ц' => 'Ts',
		'қ' => 'q', 'Қ' => 'Q',
		'ё' => 'yo', 'Ё' => 'Yo',
		'ю' => 'yu', 'Ю' => 'Yu',
		'ч' => 'ch', 'Ч' => 'Ch',
		'ш' => 'sh', 'Ш' => 'Sh',
		'й' => 'y', 'Й' => 'Y',
		'я' => 'ya', 'Я' => 'Ya',
		'ъ' => 'ʼ',
	];

	private const TO_CYRILLIC = [
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

	public function getMainCode(): string {
		return 'uz';
	}

	public function getLanguageVariants(): array {
		return [ 'uz', 'uz-latn', 'uz-cyrl' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'uz' => 'uz-latn',
			'uz-cyrl' => 'uz',
			'uz-latn' => 'uz',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'uz-cyrl' => new ReplacementArray( self::TO_CYRILLIC ),
			'uz-latn' => new ReplacementArray( self::TO_LATIN ),
			'uz' => new ReplacementArray()
		];
	}

	/** @inheritDoc */
	public function translate( $text, $toVariant ) {
		if ( $toVariant == 'uz-cyrl' ) {
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
