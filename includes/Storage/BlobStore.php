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
	 * @param string $protoAddress the desired address of the data blob, respectively a seed
	 *        for generating the blob's address. Implementations may use the address if given,
	 *        require it, or discard it. Callers must use the address returned by this method
	 *        to address the blob in the future.
	 * @param string $data binary data to store
	 *
	 * @throws StorageException if a storage level error occurred
	 * @throws InvalidArgumentException if $protoAddress is not supported
	 *
	 * @return string the permanent canonical address of the blob.
	 */
	public function storeData( $protoAddress, $data );

}
