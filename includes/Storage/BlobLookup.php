<?php
/**
 * Service for looking up data blobs.
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

namespace MediaWiki\Storage;

/**
 * Service for looking up data blobs.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 *
 * @since 1.31
 */
interface BlobLookup {

	/**
	 * Retrieve a blob, given an address.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @note Passing an int as $blobAddress is deprecated. For referencing blobs in the text table,
	 *       use the "tt:" prefix, as in "tt:12345".
	 *
	 * @param string $blobAddress The blob address, such as "tt:12345" or "ex:DB://s16/456/9876".
	 * @param int $queryFlags See IDBAccessObject
	 *
	 * @return string|false
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 );

}
