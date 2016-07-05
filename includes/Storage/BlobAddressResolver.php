<?php

namespace MediaWiki\Storage;

use MediaWiki\Storage\BlobLookup;
use MediaWiki\Storage\BlobStoreRegistry;
use MediaWiki\Storage\NotFoundException;
use MediaWiki\Storage\StorageException;
use Wikimedia\Assert\Assert;

/**
 * Blob lookup service that will forward lookups to other blob stores based on
 * address prefixes.
 *
 * @todo: XXX: make this an interface, and merge the implementation into BlobStoreRegistry?
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class BlobAddressResolver {

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
	 * @param string[] $addresses The desired blobs' addressees.
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string[] the binary data, keyed by addess
	 */
	public function getBlobs( array $addresses, $flags = 0 ) {
		$results = [];

		// TODO: group by prefix, use BlobBatchLookup interface if supported
		// by the respective blob store.
		foreach ( $addresses as $address ) {
			$results[$address] = $this->loadData( $address, $flags );
		}

		return $results;
	}

	private function loadData( $address, $flags = 0 ) {
		$parts = explode( ':', $address, 2 );

		if ( count( $parts ) < 2 ) {
			throw new NotFoundException( 'Address can not be solved via prefix routing (no prefix found): ' . $address );
		}

		list( $prefix, $subAddress ) = $address;

		$lookup = $this->blobStoreRegistry->getBlobStore( $prefix );

		return $lookup->loadData( $subAddress, $flags );
	}

}
