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
	 * @param string $data binary data to store
	 *
	 * @throws StorageException if a storage level error occurred
	 * @throws InvalidArgumentException if $protoAddress is not supported
	 *
	 * @return string the permanent canonical address of the blob. Can be used
	 *         with BlobLookup::loadData() to retrieve the data.
	 */
	public function storeData( $protoAddress, $data );

}
