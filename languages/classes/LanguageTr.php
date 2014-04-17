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
 * Turkish has two different i, one with a dot and another without a dot. They
 * are totally different letters in this language, so we have to override the
 * ucfirst and lcfirst methods.
 * See http://en.wikipedia.org/wiki/Dotted_and_dotless_I
 * and @bug 28040
 * @ingroup Language
 */
class LanguageTr extends Language {

	/**
	 * @param string $string
	 * @return string
	 */
	function ucfirst( $string ) {
		if ( strlen( $string ) && $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	/**
	 * @param string $string
	 * @return mixed|string
	 */
	function lcfirst( $string ) {
		if ( strlen( $string ) && $string[0] == 'I' ) {
			return 'ı' . substr( $string, 1 );
		} else {
			return parent::lcfirst( $string );
		}
	}

}
