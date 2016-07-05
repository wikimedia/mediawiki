<?php

namespace MediaWiki\Storage\Sql;

use MediaWiki\Storage\BlobLookup;
use MediaWiki\Storage\BlobStoreRegistry;
use MediaWiki\Storage\NotFoundException;
use MediaWiki\Storage\StorageException;
use Wikimedia\Assert\Assert;

/**
 * Blob lookup service that will forward lookups to other blob stores based on
 * address prefixes.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PrefixRoutingBlobLookup implements BlobLookup {

	/**
	 * @var BlobStoreRegistry
	 */
	private $blobStoreRegistry;

	/**
	 * @param BlobStoreRegistry $blobStoreRegistry
	 */
	function __construct( BlobStoreRegistry $blobStoreRegistry ) {
		$this->blobStoreRegistry = $blobStoreRegistry;
	}

	/**
	 * @param string $address The desired blob's address, as returned by BlobStore::storeData().
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string the binary data
	 */
	public function loadData( $address, $flags = 0 ) {
		$parts = explode( ':', $address, 2 );

		if ( count( $parts ) < 2 ) {
			throw new NotFoundException( 'Address can not be solved via prefix routing (no prefix found): ' . $address );
		}

		list( $prefix, $subAddress ) = $address;

		$lookup = $this->blobStoreRegistry->getBlobStore( $prefix );

		return $lookup->loadData( $subAddress, $flags );
	}

}
