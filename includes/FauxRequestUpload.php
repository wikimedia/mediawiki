<?php
/**
 * Object to access a fake $_FILES array for testing purpose
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
 */

/**
 * Object to fake the $_FILES array
 *
 * @ingroup HTTP
 * @since 1.37
 */
class FauxRequestUpload extends WebRequestUpload {

	/**
	 * Constructor. Should only be called by FauxRequest
	 *
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake (whole) FILES values
	 * @param FauxRequest $request The associated faux request
	 * @param string $key name of upload param
	 */
	public function __construct( $data, $request, $key ) {
		$this->request = $request;
		$this->doesExist = isset( $data[$key] );
		if ( $this->doesExist ) {
			$this->fileInfo = $data[$key];
		}
	}

}
