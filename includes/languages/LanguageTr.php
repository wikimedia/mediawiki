<?php
/**
 * Turkish (Türkçe) specific code.
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
 * Turkish (Türkçe)
 *
 * The Turkish language, like other Turkic languages, distinguishes
 * a dotted letter 'i' from a dotless letter 'ı' (U+0131 LATIN SMALL LETTER DOTLESS I).
 * In these languages, each has an equivalent uppercase mapping:
 * ı (U+0131 LATIN SMALL LETTER DOTLESS I) -> I (U+0049 LATIN CAPITAL LETTER I),
 * i (U+0069 LATIN SMALL LETTER I) -> İ (U+0130 LATIN CAPITAL LETTER I WITH DOT ABOVE).
 *
 * Unicode CaseFolding.txt defines this case as type 'T', a special case for Turkic languages:
 * tr and az. PHP 7.3 parser ignores this special cases. so we have to override the
 * ucfirst and lcfirst methods.
 *
 * See https://en.wikipedia.org/wiki/Dotted_and_dotless_I and T30040
 * @ingroup Language
 */
class LanguageTr extends Language {

	private $uc = [ 'I', 'İ' ];
	private $lc = [ 'ı', 'i' ];

	/**
	 * @param string $string
	 * @return string
	 */
	public function ucfirst( $string ) {
		$first = mb_substr( $string, 0, 1 );
		if ( in_array( $first, $this->lc ) ) {
			$first = str_replace( $this->lc, $this->uc, $first );
			return $first . mb_substr( $string, 1 );
		}
		return parent::ucfirst( $string );
	}

	/**
	 * @param string $string
	 * @return mixed|string
	 */
	public function lcfirst( $string ) {
		$first = mb_substr( $string, 0, 1 );
		if ( in_array( $first, $this->uc ) ) {
			$first = str_replace( $this->uc, $this->lc, $first );
			return $first . mb_substr( $string, 1 );
		}
		return parent::lcfirst( $string );
	}

}
