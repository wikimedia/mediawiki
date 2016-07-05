<?php

namespace MediaWiki\Storage;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobBatchLookup extends BlobLookup {

	/**
	 * @param string[] $addresses The desired blobs' addresses, as returned by BlobStore::storeData().
	 * @param int $queryFlags Bitfield, see the IDBAccessObject::READ_XXX constants.
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] the binary data, keyed by address
	 */
	public function loadDataBatch( array $addresses, $queryFlags = 0 );

}
