<?php

namespace MediaWiki\Storage;

use Iterator;
use Traversable;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobBatchLookup extends BlobLookup {

	/**
	 * @param Traversable|array $addresses The desired blobs' addresses, as returned by BlobStore::storeData().
	 * @param int $queryFlags Bitfield, see the IDBAccessObject::READ_XXX constants.
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return Iterator the binary data, keyed by address
	 */
	public function loadDataBatch( $addresses, $queryFlags = 0 );

}
