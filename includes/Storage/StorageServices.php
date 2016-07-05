<?php

namespace MediaWiki\Storage;

use InvalidArgumentException;
use MediaWiki\Storage\Sql\TextTableBlobStore;

/**
 * Application level factory/registry for storage services.
 * For now, this has the implementations hardcoded.
 *
 * @todo FIXME integrate with MediaWikiServices / make a ServiceContainer.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class StorageServices {

	public static function getDefaultInstance() {
		static $instance = null;

		if ( $instance === null ) {
			$instance = new self();
		}

		return $instance;
	}

	private $serviceInstances = [];

	private function getFactoryFunction( $serviceName ) {
		$methodName = "new$serviceName";

		if ( method_exists( $this, $methodName ) ) {
			return [ $this, $methodName ];
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

	private function newBlobStoreRegistry() {
		$services = $this;

		return new BlobStoreRegistry( [
			'db' => function() use ( $services ) {
				return $services->getService( 'TextTableBlobStore' );
			}
		] );
	}

	public function getBlobStoreRegistry() {
		return $this->getService( 'BlobStoreRegistry' );
	}

	private function newTextTableBlobStore() {
		return new TextTableBlobStore();
	}

	private function newBlobLookup() {
		//NOTE: the associations between address prefix and associated blob lookup mechanism
		//      MUST NEVER CHANGE, otherwise addresses with such a prefix which are already in
		//      the database will no longer be resolvable!
		return new PrefixRoutingBlobLookup( [
			'db' => $this->getService( 'TextTableBlobStore' ), // we know that TextTableBlobStore implements BlobLookup
		] );
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
		$slotDefinitions = array(
			'main' => array(
				'store' => 'db',
				'type' => 'primary',
			),

			'poid/html' => array(
				'store' => 'rb',
				'type' => 'derived',
			),
		);

		return new SlotMappingRevisionContentStore(
			$slotDefinitions,
			$this->getBlobStoreRegistry(),
			$this->getBlobLookup(),
			$this->getRevisionSlotTable()
		);
	}

	public function getRevisionContentStore() {
		return $this->getService( 'RevisionContentStore' );
	}

}
