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

/**
 * Converts Serbo-Croatian from Latin script to Cyrillic script
 *
 * @ingroup Languages
 */
class ShConverter extends LanguageConverter {

	/**
	 * @var string[]
	 */
	private $mToCyrillic = [
		'dž' => 'џ',
		'lj' => 'љ',
		'nj' => 'њ',
		'Dž' => 'Џ',
		'DŽ' => 'Џ',
		'Lj' => 'Љ',
		'LJ' => 'Љ',
		'Nj' => 'Њ',
		'NЈ' => 'Њ',

		'a' => 'а',
		'b' => 'б',
		'c' => 'ц',
		'č' => 'ч',
		'ć' => 'ћ',
		'd' => 'д',
		'đ' => 'ђ',
		'e' => 'е',
		'f' => 'ф',
		'g' => 'г',
		'h' => 'х',
		'i' => 'и',
		'j' => 'ј',
		'k' => 'к',
		'l' => 'л',
		'm' => 'м',
		'n' => 'н',
		'o' => 'о',
		'p' => 'п',
		'r' => 'р',
		's' => 'с',
		'š' => 'ш',
		't' => 'т',
		'u' => 'у',
		'v' => 'в',
		'z' => 'з',
		'ž' => 'ж',

		'A' => 'А',
		'B' => 'Б',
		'C' => 'Ц',
		'Č' => 'Ч',
		'Ć' => 'Ћ',
		'D' => 'Д',
		'Đ' => 'Ђ',
		'E' => 'Е',
		'F' => 'Ф',
		'G' => 'Г',
		'H' => 'Х',
		'I' => 'И',
		'J' => 'Ј',
		'K' => 'К',
		'L' => 'Л',
		'M' => 'М',
		'N' => 'Н',
		'O' => 'О',
		'P' => 'П',
		'R' => 'Р',
		'S' => 'С',
		'Š' => 'Ш',
		'T' => 'Т',
		'U' => 'У',
		'V' => 'В',
		'Z' => 'З',
		'Ž' => 'Ж',
	];

	public function getMainCode(): string {
		return 'sh';
	}

	public function getLanguageVariants(): array {
		return [ 'sh-latn', 'sh-cyrl' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'sh-cyrl' => 'sh-latn',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'sh-cyrl' => new ReplacementArray( $this->mToCyrillic ),
			'sh-latn' => new ReplacementArray(),
		];
	}

	/**
	 * Omits roman numbers
	 *
	 * @inheritDoc
	 */
	public function translate( $text, $variant ) {
		return $this->translateWithoutRomanNumbers( $text, $variant );
	}
}
