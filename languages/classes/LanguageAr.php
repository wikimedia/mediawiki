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
	 * Temporary hack for T11413: replace Arabic presentation forms with their
	 * standard equivalents.
	 *
	 * @todo FIXME: This is language-specific for now only to avoid the negative
	 * performance impact of enabling it for all languages.
	 *
	 * @param string $s
	 *
	 * @return string
	 */
	public function normalize( $s ) {
		global $IP;
		$s = parent::normalize( $s );
		$s = $this->transformUsingPairFile( 'normalize-ar.php', $s, $IP );
		return $s;
	}
}
