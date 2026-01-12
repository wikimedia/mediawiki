<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Storage;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\ExternalStore\ExternalStoreAccess;
use MediaWiki\MainConfigNames;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Service for instantiating BlobStores
 *
 * This can be used to create BlobStore objects for other wikis.
 *
 * @since 1.31
 */
class BlobStoreFactory {

	/**
	 * @var ILBFactory
	 */
	private $lbFactory;

	/**
	 * @var ExternalStoreAccess
	 */
	private $extStoreAccess;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var ServiceOptions
	 */
	private $options;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::CompressRevisions,
		MainConfigNames::DefaultExternalStore,
		MainConfigNames::LegacyEncoding,
		MainConfigNames::RevisionCacheExpiry,
	];

	public function __construct(
		ILBFactory $lbFactory,
		ExternalStoreAccess $extStoreAccess,
		WANObjectCache $cache,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->lbFactory = $lbFactory;
		$this->extStoreAccess = $extStoreAccess;
		$this->cache = $cache;
		$this->options = $options;
	}

	/**
	 * @since 1.31
	 *
	 * @param bool|string $dbDomain The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return BlobStore
	 */
	public function newBlobStore( $dbDomain = false ) {
		return $this->newSqlBlobStore( $dbDomain );
	}

	/**
	 * @internal Please call newBlobStore and use the BlobStore interface.
	 *
	 * @param bool|string $dbDomain The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return SqlBlobStore
	 */
	public function newSqlBlobStore( $dbDomain = false ) {
		$lb = $this->lbFactory->getMainLB( $dbDomain );
		$store = new SqlBlobStore(
			$lb,
			$this->extStoreAccess,
			$this->cache,
			$dbDomain
		);

		$store->setCompressBlobs( $this->options->get( MainConfigNames::CompressRevisions ) );
		$store->setCacheExpiry( $this->options->get( MainConfigNames::RevisionCacheExpiry ) );
		$store->setUseExternalStore(
			$this->options->get( MainConfigNames::DefaultExternalStore ) !== false );

		if ( $this->options->get( MainConfigNames::LegacyEncoding ) ) {
			$store->setLegacyEncoding( $this->options->get( MainConfigNames::LegacyEncoding ) );
		}

		return $store;
	}

}
