<?php
/**
 * Bulgarian (Български) specific code.
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
 * Bulgarian (Български)
 *
 * @ingroup Language
 */
class LanguageBg extends Language {
	/**
	 * ISO number formatting: 123 456 789,99.
	 * Avoid tripple grouping by numbers with whole part up to 4 digits.
	 *
	 * @param string $_
	 *
	 * @return string
	 */
	function commafy( $_ ) {
		if ( !preg_match( '/^\d{1,4}$/', $_ ) ) {
			return strrev( (string)preg_replace( '/(\d{3})(?=\d)(?!\d*\.)/', '$1,', strrev( $_ ) ) );
		} else {
			return $_;
		}
	}
}
