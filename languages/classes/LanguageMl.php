<?php
/**
 * Malayalam (മലയാളം) specific code.
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
 * Malayalam (മലയാളം)
 *
 * @ingroup Language
 */
class LanguageMl extends Language {
	/**
	 * Temporary hack for the issue described at
	 * http://permalink.gmane.org/gmane.science.linguistics.wikipedia.technical/46396
	 * Convert Unicode 5.0 style Malayalam input to Unicode 5.1. Similar to
	 * bug 9413. Also fixes miscellaneous problems due to mishandling of ZWJ,
	 * e.g. bug 11162.
	 *
	 * @todo FIXME: This is language-specific for now only to avoid the negative
	 * performance impact of enabling it for all languages.
	 *
	 * @param $s string
	 *
	 * @return string
	 */
	function normalize( $s ) {
		global $wgFixMalayalamUnicode;
		$s = parent::normalize( $s );
		if ( $wgFixMalayalamUnicode ) {
			$s = $this->transformUsingPairFile( 'normalize-ml.ser', $s );
		}
		return $s;
	}
}
