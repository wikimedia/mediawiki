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
 * @ingroup Testing
 */

/**
 * Initialize and detect the DjVu files support
 */
class DjVuSupport {

	/**
	 * Initialises DjVu tools global with default values
	 */
	public function __construct() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgDjvuToXML, $wgFileExtensions, $wgDjvuTxt;

		$wgDjvuRenderer = $wgDjvuRenderer ?: '/usr/bin/ddjvu';
		$wgDjvuDump = $wgDjvuDump ?: '/usr/bin/djvudump';
		$wgDjvuToXML = $wgDjvuToXML ?: '/usr/bin/djvutoxml';
		$wgDjvuTxt = $wgDjvuTxt ?: '/usr/bin/djvutxt';

		if ( !in_array( 'djvu', $wgFileExtensions ) ) {
			$wgFileExtensions[] = 'djvu';
		}
	}

	/**
	 * Returns true if the DjVu tools are usable
	 *
	 * @return bool
	 */
	public function isEnabled() {
		global $wgDjvuRenderer, $wgDjvuDump, $wgDjvuToXML, $wgDjvuTxt;

		return is_executable( $wgDjvuRenderer )
			&& is_executable( $wgDjvuDump )
			&& is_executable( $wgDjvuToXML )
			&& is_executable( $wgDjvuTxt );
	}
}
