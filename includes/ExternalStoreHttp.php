<?php
/**
 * External storage using HTTP requests.
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
 * Example class for HTTP accessable external objects.
 * Only supports reading, not storing.
 *
 * @ingroup ExternalStorage
 */
class ExternalStoreHttp {

	/**
	 * Fetch data from given URL
	 *
	 * @param $url String: the URL
	 * @return String: the content at $url
	 */
	function fetchFromURL( $url ) {
		$ret = Http::get( $url );
		return $ret;
	}

	/* XXX: may require other methods, for store, delete,
	 * whatever, for initial ext storage
	 */
}
