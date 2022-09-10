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

namespace MediaWiki\Tests;

/**
 * Terminal that supports ANSI escape sequences.
 *
 * @ingroup Testing
 */
class AnsiTermColorer {
	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param string|int $color Semicolon-separated list of attribute/color codes
	 * @return string
	 */
	public function color( $color ) {
		global $wgCommandLineDarkBg;

		$light = $wgCommandLineDarkBg ? "1;" : "0;";

		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return string
	 */
	public function reset() {
		return $this->color( '0' );
	}
}
