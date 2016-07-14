<?php

namespace MediaWiki\Storage;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobLookup {

	/**
	 * Hin at the content model of the data.
	 * Value must be a content model ID as used by ContentHandler.
	 * BlobStores may use this to optimize storage for well known content models.
	 */
	const HINT_MODEL = 'model';

	/**
	 * Hin at the serialization format of the data.
	 * Value must be a MIME type.
	 * BlobStores may use this to optimize storage for well known data formats.
	 */
	const HINT_FORMAT = 'format';

	/**
	 * Hin at the hash of the data.
	 * Value must be encoded as a string.
	 * BlobStores that need a content hash may use this instead of re-calculating the hash.
	 */
	const HINT_HASH = 'hash';

	/**
	 * Hin at the page the data belongs to.
	 * Value must be an int that corresponds to a page_id.
	 * BlobStores may use this to group related data together, e.g. for prefetching.
	 */
	const HINT_PAGE = 'page';

	/**
	 * Hin at the revision the data belongs to.
	 * Value must be an int that corresponds to a rev_id.
	 * BlobStores may use this to group related data together, e.g. for prefetching.
	 */
	const HINT_REVISION = 'revision';

	/**
	 * @param string $address The desired blob's address, as returned by BlobStore::storeData().
	 * @param int $queryFlags Bitfield, see the IDBAccessObject::READ_XXX constants.
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string the binary data
	 */
	public function loadData( $address, $queryFlags = 0 );

	/**
	 * Returns any hints the lookup has readily available for the given address.
	 * Callers may use such hints for optimization purposes. See the HINT_XXX constants
	 * for well known hint keys.
	 *
	 * All hints are optional, implementations are free to return an empty array[].
	 *
	 * @note This is intended to be a lightweight operation, like a quick
	 *       database lookup. Implementations should not attempt to provide
	 *       hints if doing so causes significant work, like calculating a
	 *       content hash.
	 *
	 * @param string $address The address of a blob, as returned by BlobStore::storeData().
	 * @param string $fetchBlob set to "fetch" to indicate that a
	 *        loadData() call for the same address is likely to follow.
	 *        Implementations may use this information to optimize caching.
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return array an associative array of hints. See the HINT_XXX constants
	 *         for well known keys.
	 */
	public function getHints( $address, $fetchBlob = 'no' );

}
