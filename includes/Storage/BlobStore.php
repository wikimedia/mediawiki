<?php

namespace MediaWiki\Storage;

/**
 * Service interface for storing arbitrary binary data.
 *
 * @todo batch storage interface for use during imports
 * @todo discardData() to free blobs? do we need ref counting? usage tracking? transactions?
 * @todo hints for volatility and expiry?
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobStore extends BlobLookup {

	/**
	 * Hint at an address of a blob that is superseded by the new data.
	 * Value must be an address string as returned by BlobStore::storeData() (or null).
	 * BlobStores may use this to discard obsolete data.
	 * @note Implementations have to take care that the replaced data can be restored
	 *       in case the storage operation is rolled back!
	 * @todo How to handle ref counting for content-addressable storage?
	 */
	const HINT_REPLACE = 'replace';

	/**
	 * Hint at an address of a blob that is probably similar.
	 * Value must be an address string as returned by BlobStore::storeData() (or null).
	 * BlobStores may use this to group similar data together, e.g. for compression.
	 */
	const HINT_SIMILAR = 'similar';

	/**
	 * Hint at the parent revision of the revision the data belongs to.
	 * Value must be an int that corresponds to a BlobLookup::HINT_REVISION
	 * on a previously-stored blob (or null). Zero (0) can be used to indicate
	 * that there is no parent revision.
	 * BlobStores may use this to group similar data together, e.g. for compression.
	 */
	const HINT_PARENT = 'parent';

	/**
	 * Stores a binary data blob and returns an address for retrieving it later.
	 *
	 * @param string $data binary data to store
	 * @param array $hints associative array of hint keys to hint values.
	 *        See the BlobLookup::HINT_XXX constants. All hints are optional, and
	 *        implementations are free to ignore any hint. Implementations may
	 *        choose to store some or all hints, and return them when queried
	 *        via BlobLoader::getHints(). A hint being set to null is considered
	 *        equivalent to the hint being absent.
	 *
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string the permanent canonical address of the blob. Can be used
	 *         with BlobLookup::loadData().
	 */
	public function storeData( $data, $hints = [] );

}
