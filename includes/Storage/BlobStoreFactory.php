<?php

namespace MediaWiki\Storage;

use Config;
use WANObjectCache;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Service for instantiating BlobStores
 *
 * This can be used to create BlobStore objects for other wikis.
 *
 * @since 1.31
 */
class BlobStoreFactory {

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var Config
	 */
	private $config;

	public function __construct(
		LoadBalancer $loadBalancer,
		WANObjectCache $cache,
		Config $mainConfig
	) {
		$this->loadBalancer = $loadBalancer;
		$this->cache = $cache;
		$this->config = $mainConfig;
	}

	/**
	 * @since 1.31
	 *
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return BlobStore
	 */
	public function newBlobStore( $wikiId = false ) {
		return $this->newSqlBlobStore( $wikiId );
	}

	/**
	 * @internal Please call newBlobStore and use the BlobStore interface.
	 *
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 *
	 * @return SqlBlobStore
	 */
	public function newSqlBlobStore( $wikiId = false ) {
		global $wgContLang; // TODO: manage $wgContLang as a service

		$store = new SqlBlobStore(
			$this->loadBalancer,
			$this->cache,
			$wikiId
		);

		$store->setCompressBlobs( $this->config->get( 'CompressRevisions' ) );
		$store->setCacheExpiry( $this->config->get( 'RevisionCacheExpiry' ) );
		$store->setUseExternalStore( $this->config->get( 'DefaultExternalStore' ) !== false );

		if ( $this->config->get( 'LegacyEncoding' ) ) {
			$store->setLegacyEncoding( $this->config->get( 'LegacyEncoding' ), $wgContLang );
		}

		return $store;
	}

}
