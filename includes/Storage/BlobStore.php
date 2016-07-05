<?php

namespace MediaWiki\Storage;
use InvalidArgumentException;

/**
 * Service interface for storing arbitrary binary data.
 *
 * @todo batch storage interface for use during imports
 * @todo discardData() to free blobs? do we need ref counting? usage tracking?
 * @todo hints for volatility and expiry?
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobStore extends BlobLookup {

	/**
	 * Hin at an address of a blob that becomes is superseded by the new data.
	 * Value must be an address string as returned by BlobStore::storeData()
	 * BlobStores may use this to discard obsolete data.
	 * @todo How to handle ref counting for content-adressable storage?
	 */
	const HINT_REPLACE = 'replace';

	/**
	 * Hin at an address of a blob that is probably similar to the given one.
	 * Value must be an address string as returned by BlobStore::storeData()
	 * BlobStores may use this to group similar data together, e.g. for compression.
	 */
	const HINT_SIMILAR = 'similar';

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
	 * Hin at the SHA1 hash of the data.
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
	 * Hin at the parent revision pf the revision the data belongs to.
	 * Value must be an int that corresponds to a rev_id as used in rev_parent_id.
	 * BlobStores may use this to group similar data together, e.g. for compression.
	 */
	const HINT_PARENT = 'parent';

	/**
	 * Stores a binary data blob and returns an address for retrieving it later.
	 *
	 * @param string $data binary data to store
	 * @param array $hints associative array of hint keys t o hin values.
	 *        See the HINT_XXX constants. All hints are optional, and
	 *        implementations are free to ignire any hint.
	 *
	 * @throws StorageException if a storage level error occurred
	 * @throws InvalidArgumentException if $protoAddress is not supported
	 *
	 * @return string the permanent canonical address of the blob. Can be used
	 *         with BlobLookup::loadData().
	 */
	public function storeData( $data, $hints = [] );

}
