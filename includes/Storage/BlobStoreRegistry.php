<?php

namespace MediaWiki\Storage;

use InvalidArgumentException;

/**
 * Registry for BlobStore services.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class BlobStoreRegistry {

	private $stores = array();

	private $factories = array();

	/**
	 * @param callable[] $factories
	 */
	public function __construct( array $factories ) {
		$this->factories = $factories;
	}

	public function getBlobStore( $name ) {
		if ( isset( $this->stores[$name] ) ) {
			return $this->stores[$name];
		}

		if ( !isset( $this->factories[$name] ) ) {
			throw new InvalidArgumentException( "Unknown BlobStore: $name" );
		}

		$this->stores[$name] = $this->instantiate( $this->factories[$name] );

		return $this->stores[$name];
	}

	private function instantiate( $factory ) {
		$store = call_user_func( $factory );
		return $store;
	}

}
