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

use Wikimedia\ReplacementArray;

/**
 * Serbian (Српски / Srpski) specific code.
 *
 * There are two levels of conversion for Serbian: the script level
 * (Cyrillics <-> Latin), and the variant level (ekavian
 * <->iyekavian). The two are orthogonal. So we really only need two
 * dictionaries: one for Cyrillics and Latin, and one for ekavian and
 * iyekavian.
 *
 * @ingroup Languages
 */
class SrConverter extends LanguageConverterSpecific {

	private const TO_LATIN = [
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
		'ђ' => 'đ', 'е' => 'e', 'ж' => 'ž', 'з' => 'z', 'и' => 'i',
		'ј' => 'j', 'к' => 'k', 'л' => 'l', 'љ' => 'lj', 'м' => 'm',
		'н' => 'n', 'њ' => 'nj', 'о' => 'o', 'п' => 'p', 'р' => 'r',
		'с' => 's', 'т' => 't', 'ћ' => 'ć', 'у' => 'u', 'ф' => 'f',
		'х' => 'h', 'ц' => 'c', 'ч' => 'č', 'џ' => 'dž', 'ш' => 'š',

		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
		'Ђ' => 'Đ', 'Е' => 'E', 'Ж' => 'Ž', 'З' => 'Z', 'И' => 'I',
		'Ј' => 'J', 'К' => 'K', 'Л' => 'L', 'Љ' => 'Lj', 'М' => 'M',
		'Н' => 'N', 'Њ' => 'Nj', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
		'С' => 'S', 'Т' => 'T', 'Ћ' => 'Ć', 'У' => 'U', 'Ф' => 'F',
		'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Č', 'Џ' => 'Dž', 'Ш' => 'Š',
	];

	private const TO_CYRILLICS = [
		'a' => 'а', 'b' => 'б', 'c' => 'ц', 'č' => 'ч', 'ć' => 'ћ',
		'd' => 'д', 'dž' => 'џ', 'đ' => 'ђ', 'e' => 'е', 'f' => 'ф',
		'g' => 'г', 'h' => 'х', 'i' => 'и', 'j' => 'ј', 'k' => 'к',
		'l' => 'л', 'lj' => 'љ', 'm' => 'м', 'n' => 'н', 'nj' => 'њ',
		'o' => 'о', 'p' => 'п', 'r' => 'р', 's' => 'с', 'š' => 'ш',
		't' => 'т', 'u' => 'у', 'v' => 'в', 'z' => 'з', 'ž' => 'ж',

		'A' => 'А', 'B' => 'Б', 'C' => 'Ц', 'Č' => 'Ч', 'Ć' => 'Ћ',
		'D' => 'Д', 'Dž' => 'Џ', 'Đ' => 'Ђ', 'E' => 'Е', 'F' => 'Ф',
		'G' => 'Г', 'H' => 'Х', 'I' => 'И', 'J' => 'Ј', 'K' => 'К',
		'L' => 'Л', 'LJ' => 'Љ', 'M' => 'М', 'N' => 'Н', 'NJ' => 'Њ',
		'O' => 'О', 'P' => 'П', 'R' => 'Р', 'S' => 'С', 'Š' => 'Ш',
		'T' => 'Т', 'U' => 'У', 'V' => 'В', 'Z' => 'З', 'Ž' => 'Ж',

		'DŽ' => 'Џ', 'd!ž' => 'дж', 'D!ž' => 'Дж', 'D!Ž' => 'ДЖ',
		'Lj' => 'Љ', 'l!j' => 'лј', 'L!j' => 'Лј', 'L!J' => 'ЛЈ',
		'Nj' => 'Њ', 'n!j' => 'нј', 'N!j' => 'Нј', 'N!J' => 'НЈ'
	];

	public function getMainCode(): string {
		return 'sr';
	}

	public function getLanguageVariants(): array {
		return [ 'sr', 'sr-ec', 'sr-el' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'sr' => 'sr-ec',
			'sr-ec' => 'sr',
			'sr-el' => 'sr',
		];
	}

	protected function getAdditionalFlags(): array {
		return [
			'S' => 'S',
			'писмо' => 'S',
			'pismo' => 'S',
			'W' => 'W',
			'реч' => 'W',
			'reč' => 'W',
			'ријеч' => 'W',
			'riječ' => 'W'
		];
	}

	protected function loadDefaultTables(): array {
		return [
			'sr-ec' => new ReplacementArray( self::TO_CYRILLICS ),
			'sr-el' => new ReplacementArray( self::TO_LATIN ),
			'sr' => new ReplacementArray()
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

	/** @inheritDoc */
	public function guessVariant( $text, $variant ) {
		$numCyrillic = preg_match_all( "/[шђчћжШЂЧЋЖ]/u", $text, $dummy );
		$numLatin = preg_match_all( "/[šđčćžŠĐČĆŽ]/u", $text, $dummy );

		if ( $variant == 'sr-ec' ) {
			return $numCyrillic > $numLatin;
		} elseif ( $variant == 'sr-el' ) {
			return $numLatin > $numCyrillic;
		} else {
			return false;
		}
	}
}
