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
 * Class to replace regex matches with a string similar to that used in preg_replace()
 *
 * @deprecated since 1.32, use a Closure instead
 */
class RegexlikeReplacer extends Replacer {
	private $r;

	/**
	 * @param string $r
	 */
	public function __construct( $r ) {
		wfDeprecated( __METHOD__, '1.32' );
		$this->r = $r;
	}

	/**
	 * @param array $matches
	 * @return string
	 */
	public function replace( array $matches ) {
		$pairs = [];
		foreach ( $matches as $i => $match ) {
			$pairs["\$$i"] = $match;
		}

		return strtr( $this->r, $pairs );
	}
}
