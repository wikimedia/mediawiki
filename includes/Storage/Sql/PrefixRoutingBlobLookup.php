<?php

namespace MediaWiki\Storage\Sql;

use MediaWiki\Storage\BlobLookup;
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
	 * @var BlobLookup[] BlobLookups keyed by prefix
	 */
	private $lookups;

	/**
	 * @param BlobLookup[] BlobLookups keyed by prefix
	 */
	function __construct( array $lookups ) {
		Assert::parameterElementType( 'MediaWiki\Storage\BlobLookup', $lookups, '$lookups' );

		$this->lookups = $lookups;
	}

	/**
	 * @param string $address The desired blob's address, as returned by BlobStore::storeData().
	 *
	 * @throws NotFoundException if the requested data blob was not found
	 * @throws StorageException if a storage level error occurred
	 *
	 * @return string the binary data
	 */
	public function loadData( $address ) {
		$parts = explode( ':', $address, 2 );

		if ( count( $parts ) < 2 ) {
			throw new NotFoundException( 'Address can not be solved via prefix routing (no prefix found): ' . $address );
		}

		list( $prefix, $subAddress ) = $address;

		if ( !isset( $this->lookups[$prefix] ) ) {
			throw new NotFoundException( 'No lookup registered for prefix ' . $prefix );
		}

		$lookup = $this->lookups[$prefix];

		return $lookup->loadData( $subAddress );
	}

}
