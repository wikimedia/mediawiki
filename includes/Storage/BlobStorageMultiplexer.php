<?php

namespace MediaWiki\Storage;

/**
 * Service interface for storing arbitrary binary data.
 *
 * @todo just implement it, we probably don't even need a pretty interface
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobStorageMultiplexer extends BlobLookup {

	/**
	 * Stores a binary data blob in an underlying BlobStore and returns an
	 * address for retrieving it later.
	 *
	 * @param string $store The name of the store to write the data to.
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
	 *         with BlobLookup::loadData() on this BlobStorageMultiplexer.
	 *         The $store parameter will be encoded in the address in a way that
	 *         allows loadData() to access the correct underlying store to retrieve
	 *         the data.
	 */
	public function storeDataTo( $store, $data, $hints = [] );

}
