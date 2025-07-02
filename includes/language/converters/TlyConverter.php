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
 * @author Amir E. Aharoni
 */

use MediaWiki\Language\LanguageConverter;
use Wikimedia\ReplacementArray;

/**
 * Talysh specific code.
 *
 * @ingroup Languages
 */
class TlyConverter extends LanguageConverter {
	/**
	 * The conversion table.
	 * The Cyrillic sequence is based on Pireyko's 1976 Talysh—Russian dictionary,
	 * with practical additions.
	 * The toCyrillic table is built by flipping this one.
	 */
	private const TO_LATIN = [
		'а' => 'a', 'А' => 'A',
		'б' => 'b', 'Б' => 'B',
		'в' => 'v', 'В' => 'V',
		'г' => 'q', 'Г' => 'Q', // Not G!
		'ғ' => 'ğ', 'Ғ' => 'Ğ',
		'д' => 'd', 'Д' => 'D',
		'е' => 'e', 'Е' => 'E',
		'ә' => 'ə', 'Ә' => 'Ə',
		'ж' => 'j', 'Ж' => 'J',
		'з' => 'z', 'З' => 'Z',

		'и' => 'i', 'И' => 'İ', // NB Dotted capital I
		'ы' => 'ı', 'Ы' => 'I', // NB Dotless small I
		'ј' => 'y', 'Ј' => 'Y',
		'к' => 'k', 'К' => 'K',
		'л' => 'l', 'Л' => 'L',
		'м' => 'm', 'М' => 'M',
		'н' => 'n', 'Н' => 'N',
		'о' => 'o', 'О' => 'O',
		'п' => 'p', 'П' => 'P',
		'р' => 'r', 'Р' => 'R',

		'с' => 's', 'С' => 'S',
		'т' => 't', 'Т' => 'T',
		'у' => 'u', 'У' => 'U', // NB Not in the standard dictionary, but used in practice
		'ү' => 'ü', 'Ү' => 'Ü',
		'ф' => 'f', 'Ф' => 'F',
		'х' => 'x', 'Х' => 'X',
		'һ' => 'h', 'Һ' => 'H',
		'ч' => 'ç', 'Ч' => 'Ç',
		'ҹ' => 'c', 'Ҹ' => 'C',
		'ш' => 'ş', 'Ш' => 'Ş',
	];

	public function getMainCode(): string {
		return 'tly';
	}

	public function getLanguageVariants(): array {
		return [ 'tly', 'tly-cyrl' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'tly-cyrl' => 'tly',
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'tly-cyrl' => new ReplacementArray( array_flip( self::TO_LATIN ) ),
			'tly' => new ReplacementArray( self::TO_LATIN ),
		];
	}
}
