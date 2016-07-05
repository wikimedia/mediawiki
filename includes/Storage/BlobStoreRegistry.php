<?php

namespace MediaWiki\Storage;

use MediaWiki\Services\ContainerDisabledException;
use MediaWiki\Services\NoSuchServiceException;
use MediaWiki\Services\ServiceContainer;
use MediaWiki\Services\ServiceDisabledException;
use Wikimedia\Assert\Assert;

/**
 * Registry for BlobStore services.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class BlobStoreRegistry extends ServiceContainer {

	/**
	 * @param string $name
	 *
	 * @throws NoSuchServiceException if $name is not a known service.
	 * @throws ContainerDisabledException if this container has already been destroyed.
	 * @throws ServiceDisabledException if the requested service has been disabled.
	 *
	 * @return BlobStore
	 */
	public function getBlobStore( $name ) {
		$blobStore = $this->getService( $name );

		Assert::postcondition(
			$blobStore instanceof BlobStore,
			'BlobStore factory did not return a BlobStore!'
		);

		return $blobStore;
	}

}
