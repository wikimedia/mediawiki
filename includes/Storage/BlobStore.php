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
	 * Hint key for use with storeBlob, indicating the general role the block
	 * takes in the application. For instance, it should be "page-content" if
	 * the blob represents a Content object.
	 */
	const DESIGNATION_HINT = 'designation';

	/**
	 * Hint key for use with storeBlob, indicating the page the blob is associated with.
	 * This may be used for sharding.
	 */
	const PAGE_HINT = 'page_id';

	/**
	 * Hint key for use with storeBlob, indicating the slot the blob is associated with.
	 * May be relevant for reference counting.
	 */
	const ROLE_HINT = 'role_name';

	/**
	 * Hint key for use with storeBlob, indicating the revision the blob is associated with.
	 * This may be used for differential storage and reference counting.
	 */
	const REVISION_HINT = 'rev_id';

	/**
	 * Hint key for use with storeBlob, indicating the parent revision of the revision
	 * the blob is associated with. This may be used for differential storage.
	 */
	const PARENT_HINT = 'rev_parent_id';

	/**
	 * Hint key for use with storeBlob, providing the SHA1 hash of the blob as passed to the
	 * method. This can be used to avoid re-calculating the hash if it is needed by the BlobStore.
	 */
	const SHA1_HINT = 'cont_sha1';

	/**
	 * Hint key for use with storeBlob, indicating the model of the content encoded in the
	 * given blob. May be used to implement optimized storage for some well known models.
	 */
	const MODEL_HINT = 'cont_model';

	/**
	 * Hint key for use with storeBlob, indicating the serialization format used to create
	 * the blob, as a MIME type. May be used for optimized storage in the underlying database.
	 */
	const FORMAT_HINT = 'cont_format';

	/**
	 * Retrieve a blob, given an address.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @param string $blobAddress The blob address as returned by storeBlob(),
	 *        such as "tt:12345" or "ex:DB://s16/456/9876".
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
	 * provide the well known hints as defined by the XXX_HINT constants.
	 *
	 * @throws BlobAccessException
	 * @return string an address that can be used with getBlob() to retrieve the data.
	 */
	public function storeBlob( $data, $hints = [] );

	/**
	 * Check if the blob metadata or backing blob data store is read-only
	 *
	 * @return bool
	 */
	public function isReadOnly();
}
