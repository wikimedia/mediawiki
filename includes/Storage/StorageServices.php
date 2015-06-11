<?php

namespace MediaWiki\Storage;
use InvalidArgumentException;

/**
 * Application level factory/registry for storage services.
 * For now, this has the implementations hardcoded.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class StorageServices {

	private $serviceInstances = array();

	private function getFactoryFunction( $serviceName ) {
		$methodName = "new$serviceName";

		if ( method_exists( $this, $methodName ) ) {
			return array( $this, $methodName );
		}

		throw new InvalidArgumentException( "No factory method for service `$serviceName`" );
	}

	private function getService( $serviceName ) {
		if ( !isset( $this->serviceInstances[$serviceName] ) ) {
			$factoryFunction = $this->getFactoryFunction( $serviceName );
			$this->serviceInstances[$serviceName] = call_user_func( $factoryFunction );
		}

		return $this->serviceInstances[$serviceName];
	}

	private function newTextTableBlobStore() {
		return new TextTableBlobStore();
	}

	private function newBlobStore() {
		return new PrefixRoutingBlobStore( array(
			RevisionContentLookup::MAIN_CONTENT => $this->getService( 'TextTableBlobStore' ),
		) );
	}

	public function getBlobStore() {
		return $this->getService( 'BlobStore' );
	}

	private function newBlobLookup() {
		//NOTE: the associations between address prefix and associated blob lookup mechanism
		//      MUST NEVER CHANGE, otherwise addresses with such a prefix which are already in
		//      the database will no longer be resolvable!
		return new PrefixRoutingBlobLookup( array(
			'db' => $this->getService( 'TextTableBlobStore' ), // we know that TextTableBlobStore implements BlobLookup
		) );
	}

	public function getBlobLookup() {
		return $this->getService( 'BlobLookup' );
	}

	public function getRevisionContentLookup() {
		// we know that LegacyRevisionContentStore implements RevisionContentLookup
		return $this->getService( 'RevisionContentStore' );
	}

	private function newRevisionContentStore() {
		//NOTE: the associations between slot name and address prefix can be changed at will.

		// @todo: make configurable
		$slotMapping = array(
			'main' => 'db',
			'poid/html' => 'rb'
		);

		$mutableSlots = array( 'poid/html' );

		//FIXME: looks like we need a slot spec object or at least associative arrays.
		$protoAddresses = array(
			'main' => '',
			'poid/html' => 'poid/html:'
		);

		// @todo: provide legacy suppor separate from the new "clean" storage method.
		return new SlotMappingRevisionContentStore(
			$slotMapping,
			$mutableSlots,
			$protoAddresses
		);
	}

	public function getRevisionContentStore() {
		return $this->getService( 'RevisionContentStore' );
	}

}
