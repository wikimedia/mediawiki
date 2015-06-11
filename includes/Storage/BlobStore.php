<?php

namespace MediaWiki\Storage;
use InvalidArgumentException;

/**
 * Service interface for storing arbitrary binary data.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobStore {

	/**
	 * Stores a binary data blob and returns an address for retrieving it later.
	 * Optionally, an address may provided to be replaced. Implementations may
	 * or may not remove the previous content, and may or may not return the
	 * same address again. Content addressable stores shall, by nature,
	 * ignore $replaceAddress completely.
	 *
	 * @param string $data binary data to store
	 * @param string|null $replaceAddress the address of a blob to replace
	 *
	 * @throws StorageException if a storage level error occurred
	 * @throws InvalidArgumentException if $protoAddress is not supported
	 *
	 * @return string the permanent canonical address of the blob. Can be used
	 *         with BlobLookup::loadData() to retrieve the data.
	 */
	public function storeData( $data, $replaceAddress = null );

}
