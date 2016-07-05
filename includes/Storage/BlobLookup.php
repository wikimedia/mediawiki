<?php

namespace MediaWiki\Storage;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface BlobLookup {

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

}
