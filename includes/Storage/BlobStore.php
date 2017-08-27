<?php
/**
 * Service for loading and storing data blobs.
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
 * Service for loading and storing data blobs.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 *
 * @since 1.31
 */
interface BlobStore {

	/**
	 * Retrieve a blob, given an address.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @note Passing an int as $blobAddress is deprecated. For referencing blobs in the text table,
	 *       use the "tt:" prefix, as in "tt:12345".
	 *
	 * @param string|int $blobAddress The blob address as returned by storeBlob(),
	 *        such as "tt:12345" or "ex:DB://s16/456/9876". If an integer is given,
	 *        the "tt:" prefix is assumed. This behavior is deprecated.
	 * @param int $queryFlags See IDBAccessObject.
	 *
	 * @throws BlobAccessException
	 * @return string binary blob data
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 );

	/**
	 * Stores an arbitrary blob of data and returns an address that can be used with
	 * getBlob() to retrieve the same blob of data,
	 *
	 * @param string $data raw binary data
	 * @param array $hints An array of hints. Implementations may use the hints to optimize storage.
	 * All hints are optional, supported hints depend on the implementation. Hint names by
	 * convention correspond to the names of fields in the database. Callers are encouraged to
	 * provide the following well known hints: 'designation', 'page_id', 'page_namespace',
	 * 'role_name', 'rev_id', 'rev_parent_id', 'cont_sha1', 'cont_model', 'cont_format'.
	 *
	 * @throws BlobAccessException
	 * @return string an address that can be used with getBlob() to retrieve the data.
	 */
	public function storeBlob( $data, $hints = [] );

}
