<?php
/**
 * Arabic (العربية) specific code.
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
 * @author Niklas Laxström
 * @ingroup Language
 */

/**
 * Arabic (العربية)
 *
 * @ingroup Language
 */
class LanguageAr extends Language {

	/**
	 * @param $count int
	 * @param $forms array
	 * @return string
	 */
	function convertPlural( $count, $forms ) {
		if ( !count( $forms ) ) { return ''; }
		$forms = $this->preConvertPlural( $forms, 6 );

		if ( $count == 0 ) {
			$index = 0;
		} elseif ( $count == 1 ) {
			$index = 1;
		} elseif ( $count == 2 ) {
			$index = 2;
		} elseif ( $count % 100 >= 3 && $count % 100 <= 10 ) {
			$index = 3;
		} elseif ( $count % 100 >= 11 && $count % 100 <= 99 ) {
			$index = 4;
		} else {
			$index = 5;
		}
		return $forms[$index];
	}

	/**
	 * Temporary hack for bug 9413: replace Arabic presentation forms with their
	 * standard equivalents.
	 *
	 * @todo FIXME: This is language-specific for now only to avoid the negative
	 * performance impact of enabling it for all languages.
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	function normalize( $s ) {
		global $wgFixArabicUnicode;
		$s = parent::normalize( $s );
		if ( $wgFixArabicUnicode ) {
			$s = $this->transformUsingPairFile( 'normalize-ar.ser', $s );
		}
		return $s;
	}
}
