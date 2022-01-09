<?php
/**
 * Cache for outputs of the PHP parser
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Cache Parser
 */

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheMetadata;
use Psr\Log\LoggerInterface;

/**
 * Cache for ParserOutput objects corresponding to the latest page revisions.
 *
 * The ParserCache is a two-tiered cache backed by BagOStuff which supports
 * varying the stored content on the values of ParserOptions used during
 * a page parse.
 *
 * First tier is keyed by the page ID and stores ParserCacheMetadata, which
 * contains information about cache expiration and the list of ParserOptions
 * used during the parse of the page. For example, if only 'dateformat' and
 * 'userlang' options were accessed by the parser when producing output for the
 * page, array [ 'dateformat', 'userlang' ] will be stored in the metadata cache.
 * This means none of the other existing options had any effect on the output.
 *
 * The second tier of the cache contains ParserOutput objects. The key for the
 * second tier is constructed from the page ID and values of those ParserOptions
 * used during a page parse which affected the output. Upon cache lookup, the list
 * of used option names is retrieved from tier 1 cache, and only the values of
 * those options are hashed together with the page ID to produce a key, while
 * the rest of the options are ignored. Following the example above where
 * only [ 'dateformat', 'userlang' ] options changed the parser output for a
 * page, the key will look like 'page_id!dateformat=default:userlang=ru'.
 * Thus any cache lookup with dateformat=default and userlang=ru will hit the
 * same cache entry regardless of the values of the rest of the options, since they
 * were not accessed during a parse and thus did not change the output.
 *
 * @see ParserOutput::recordOption()
 * @see ParserOutput::getUsedOptions()
 * @see ParserOptions::allCacheVaryingOptions()
 * @ingroup Cache Parser
 */
class ParserCache {
	/**
	 * Constants for self::getKey()
	 * @since 1.30
	 * @since 1.36 the constants were made public
	 */

	/** Use only current data */
	public const USE_CURRENT_ONLY = 0;

	/** Use expired data if current data is unavailable */
	public const USE_EXPIRED = 1;

	/** Use expired data or data from different revisions if current data is unavailable */
	public const USE_OUTDATED = 2;

	/**
	 * Use expired data and data from different revisions, and if all else
	 * fails vary on all variable options
	 */
	private const USE_ANYTHING = 3;

	/** @var string The name of this ParserCache. Used as a root of the cache key. */
	private $name;

	/** @var BagOStuff */
	private $cache;

	/**
	 * Anything cached prior to this is invalidated
	 *
	 * @var string
	 */
	private $cacheEpoch;

	/** @var HookRunner */
	private $hookRunner;

	/** @var JsonCodec */
	private $jsonCodec;

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var LoggerInterface */
	private $logger;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/**
	 * @var BagOStuff small in-process cache to store metadata.
	 * It's needed multiple times during the request, for example
	 * to build a PoolWorkArticleView key, and then to fetch the
	 * actual ParserCache entry.
	 */
	private $metadataProcCache;

	/**
	 * @note Temporary feature flag, remove before 1.36 is released.
	 * @var bool
	 */
	private $writeJson = false;

	/**
	 * @note Temporary feature flag, remove before 1.36 is released.
	 * @var bool
	 */
	private $readJson = false;

	/**
	 * Setup a cache pathway with a given back-end storage mechanism.
	 *
	 * This class use an invalidation strategy that is compatible with
	 * MultiWriteBagOStuff in async replication mode.
	 *
	 * @param string $name
	 * @param BagOStuff $cache
	 * @param string $cacheEpoch Anything before this timestamp is invalidated
	 * @param HookContainer $hookContainer
	 * @param JsonCodec $jsonCodec
	 * @param IBufferingStatsdDataFactory $stats
	 * @param LoggerInterface $logger
	 * @param TitleFactory $titleFactory
	 * @param WikiPageFactory $wikiPageFactory
	 * @param bool $useJson Temporary feature flag, remove before 1.36 is released.
	 */
	public function __construct(
		string $name,
		BagOStuff $cache,
		string $cacheEpoch,
		HookContainer $hookContainer,
		JsonCodec $jsonCodec,
		IBufferingStatsdDataFactory $stats,
		LoggerInterface $logger,
		TitleFactory $titleFactory,
		WikiPageFactory $wikiPageFactory,
		$useJson = false
	) {
		$this->name = $name;
		$this->cache = $cache;
		$this->cacheEpoch = $cacheEpoch;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->jsonCodec = $jsonCodec;
		$this->stats = $stats;
		$this->logger = $logger;
		$this->titleFactory = $titleFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->readJson = $useJson;
		$this->writeJson = $useJson;
		$this->metadataProcCache = new HashBagOStuff( [ 'maxKeys' => 2 ] );
	}

	/**
	 * @param PageRecord $page
	 * @since 1.28
	 */
	public function deleteOptionsKey( PageRecord $page ) {
		$page->assertWiki( PageRecord::LOCAL );
		$key = $this->makeMetadataKey( $page );
		$this->metadataProcCache->delete( $key );
		$this->cache->delete( $key );
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 * @param PageRecord $page
	 * @param ParserOptions $popts
	 * @return ParserOutput|false
	 */
	public function getDirty( PageRecord $page, $popts ) {
		$page->assertWiki( PageRecord::LOCAL );
		$value = $this->get( $page, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * @param PageRecord $page
	 * @param string $metricSuffix
	 */
	private function incrementStats( PageRecord $page, $metricSuffix ) {
		$wikiPage = $this->wikiPageFactory->newFromTitle( $page );
		$contentModel = str_replace( '.', '_', $wikiPage->getContentModel() );
		$metricSuffix = str_replace( '.', '_', $metricSuffix );
		$this->stats->increment( "{$this->name}.{$contentModel}.{$metricSuffix}" );
	}

	/**
	 * Returns the ParserCache metadata about the given page
	 * considering the given options.
	 *
	 * @note Which parser options influence the cache key
	 * is controlled via ParserOutput::recordOption() or
	 * ParserOptions::addExtraKey().
	 *
	 * @param PageRecord $page
	 * @param int $staleConstraint one of the self::USE_ constants
	 * @return ParserCacheMetadata|null
	 * @since 1.36
	 */
	public function getMetadata(
		PageRecord $page,
		int $staleConstraint = self::USE_ANYTHING
	): ?ParserCacheMetadata {
		$page->assertWiki( PageRecord::LOCAL );

		$pageKey = $this->makeMetadataKey( $page );
		$metadata = $this->metadataProcCache->get( $pageKey );
		if ( !$metadata ) {
			$metadata = $this->cache->get(
				$pageKey,
				BagOStuff::READ_VERIFIED
			);
		}

		if ( $metadata === false ) {
			$this->incrementStats( $page, "miss.absent.metadata" );
			$this->logger->debug( 'ParserOutput metadata cache miss', [ 'name' => $this->name ] );
			return null;
		}

		// NOTE: If the value wasn't serialized to JSON when being stored,
		//       we may already have a ParserOutput object here. This used
		//       to be the default behavior before 1.36. We need to retain
		//       support so we can handle cached objects after an update
		//       from an earlier revision.
		// NOTE: Support for reading string values from the cache must be
		//       deployed a while before starting to write JSON to the cache,
		//       in case we have to revert either change.
		if ( is_string( $metadata ) && $this->readJson ) {
			$metadata = $this->restoreFromJson( $metadata, $pageKey, CacheTime::class );
		}

		if ( !$metadata instanceof CacheTime ) {
			$this->incrementStats( $page, 'miss.unserialize' );
			return null;
		}

		if ( $this->checkExpired( $metadata, $page, $staleConstraint, 'metadata' ) ) {
			return null;
		}

		if ( $this->checkOutdated( $metadata, $page, $staleConstraint, 'metadata' ) ) {
			return null;
		}

		$this->logger->debug( 'Parser cache options found', [ 'name' => $this->name ] );
		return $metadata;
	}

	/**
	 * @param PageRecord $page
	 * @return string
	 */
	private function makeMetadataKey( PageRecord $page ): string {
		return $this->cache->makeKey( $this->name, 'idoptions', $page->getId( PageRecord::LOCAL ) );
	}

	/**
	 * Get a key that will be used by the ParserCache to store the content
	 * for a given page considering the given options and the array of
	 * used options.
	 *
	 * @warning The exact format of the key is considered internal and is subject
	 * to change, thus should not be used as storage or long-term caching key.
	 * This is intended to be used for logging or keying something transient.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $options
	 * @param array|null $usedOptions Defaults to all cache varying options.
	 * @return string
	 * @internal
	 * @since 1.36
	 */
	public function makeParserOutputKey(
		PageRecord $page,
		ParserOptions $options,
		array $usedOptions = null
	): string {
		$usedOptions = $usedOptions ?? ParserOptions::allCacheVaryingOptions();
		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $page->getId( PageRecord::LOCAL );
		$title = $this->titleFactory->castFromPageIdentity( $page );
		$hash = $options->optionsHash( $usedOptions, $title );
		// Before T263581 ParserCache was split between normal page views
		// and action=parse. -0 is left in the key to avoid invalidating the entire
		// cache when removing the cache split.
		return $this->cache->makeKey( $this->name, 'idhash', "{$pageid}-0!{$hash}" );
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $popts
	 * @param bool $useOutdated (default false)
	 *
	 * @return ParserOutput|false
	 */
	public function get( PageRecord $page, $popts, $useOutdated = false ) {
		$page->assertWiki( PageRecord::LOCAL );

		if ( !$page->exists() ) {
			$this->incrementStats( $page, 'miss.nonexistent' );
			return false;
		}

		if ( $page->isRedirect() ) {
			// It's a redirect now
			$this->incrementStats( $page, 'miss.redirect' );
			return false;
		}

		$staleConstraint = $useOutdated ? self::USE_OUTDATED : self::USE_CURRENT_ONLY;
		$parserOutputMetadata = $this->getMetadata( $page, $staleConstraint );
		if ( !$parserOutputMetadata ) {
			return false;
		}

		if ( !$popts->isSafeToCache( $parserOutputMetadata->getUsedOptions() ) ) {
			$this->incrementStats( $page, 'miss.unsafe' );
			return false;
		}

		$parserOutputKey = $this->makeParserOutputKey(
			$page,
			$popts,
			$parserOutputMetadata->getUsedOptions()
		);

		$value = $this->cache->get( $parserOutputKey, BagOStuff::READ_VERIFIED );
		if ( $value === false ) {
			$this->incrementStats( $page, "miss.absent" );
			$this->logger->debug( 'ParserOutput cache miss', [ 'name' => $this->name ] );
			return false;
		}

		// NOTE: If the value wasn't serialized to JSON when being stored,
		//       we may already have a ParserOutput object here. This used
		//       to be the default behavior before 1.36. We need to retain
		//       support so we can handle cached objects after an update
		//       from an earlier revision.
		// NOTE: Support for reading string values from the cache must be
		//       deployed a while before starting to write JSON to the cache,
		//       in case we have to revert either change.
		if ( is_string( $value ) && $this->readJson ) {
			$value = $this->restoreFromJson( $value, $parserOutputKey, ParserOutput::class );
		}

		if ( !$value instanceof ParserOutput ) {
			$this->incrementStats( $page, 'miss.unserialize' );
			return false;
		}

		if ( $this->checkExpired( $value, $page, $staleConstraint, 'output' ) ) {
			return false;
		}

		if ( $this->checkOutdated( $value, $page, $staleConstraint, 'output' ) ) {
			return false;
		}

		$wikiPage = $this->wikiPageFactory->newFromTitle( $page );
		if ( $this->hookRunner->onRejectParserCacheValue( $value, $wikiPage, $popts ) === false ) {
			$this->incrementStats( $page, 'miss.rejected' );
			$this->logger->debug( 'key valid, but rejected by RejectParserCacheValue hook handler',
				[ 'name' => $this->name ] );
			return false;
		}

		$this->logger->debug( 'ParserOutput cache found', [ 'name' => $this->name ] );
		$this->incrementStats( $page, 'hit' );
		return $value;
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @param PageRecord $page
	 * @param ParserOptions $popts
	 * @param string|null $cacheTime TS_MW timestamp when the cache was generated
	 * @param int|null $revId Revision ID that was parsed
	 */
	public function save(
		ParserOutput $parserOutput,
		PageRecord $page,
		$popts,
		$cacheTime = null,
		$revId = null
	) {
		$page->assertWiki( PageRecord::LOCAL );

		if ( !$parserOutput->hasText() ) {
			throw new InvalidArgumentException( 'Attempt to cache a ParserOutput with no text set!' );
		}

		$expire = $parserOutput->getCacheExpiry();

		if ( !$popts->isSafeToCache( $parserOutput->getUsedOptions() ) ) {
			$this->logger->debug(
				'Parser options are not safe to cache and has not been saved',
				[ 'name' => $this->name ]
			);
			$this->incrementStats( $page, 'save.unsafe' );
			return;
		}

		if ( $expire <= 0 ) {
			$this->logger->debug(
				'Parser output was marked as uncacheable and has not been saved',
				[ 'name' => $this->name ]
			);
			$this->incrementStats( $page, 'save.uncacheable' );
			return;
		}

		if ( $this->cache instanceof EmptyBagOStuff ) {
			return;
		}

		$cacheTime = $cacheTime ?: wfTimestampNow();
		$revId = $revId ?: $page->getLatest( PageRecord::LOCAL );

		$metadata = new CacheTime;
		$metadata->recordOptions( $parserOutput->getUsedOptions() );
		$metadata->updateCacheExpiry( $expire );

		$metadata->setCacheTime( $cacheTime );
		$parserOutput->setCacheTime( $cacheTime );
		$metadata->setCacheRevisionId( $revId );
		$parserOutput->setCacheRevisionId( $revId );

		$parserOutputKey = $this->makeParserOutputKey(
			$page,
			$popts,
			$metadata->getUsedOptions()
		);

		$msg = "Saved in parser cache with key $parserOutputKey" .
			" and timestamp $cacheTime" .
			" and revision id $revId.";
		if ( $this->writeJson ) {
			$msg .= " Serialized with JSON.";
		} else {
			$msg .= " Serialized with PHP.";
		}
		$parserOutput->addCacheMessage( $msg );

		$pageKey = $this->makeMetadataKey( $page );

		if ( $this->writeJson ) {
			$parserOutputData = $this->encodeAsJson( $parserOutput, $parserOutputKey );
			$metadataData = $this->encodeAsJson( $metadata, $pageKey );
		} else {
			// rely on implicit PHP serialization in the cache
			$parserOutputData = $parserOutput;
			$metadataData = $metadata;
		}

		if ( !$parserOutputData || !$metadataData ) {
			$this->logger->warning(
				'Parser output failed to serialize and was not saved',
				[ 'name' => $this->name ]
			);
			$this->incrementStats( $page, 'save.nonserializable' );
			return;
		}

		// Save the parser output
		$this->cache->set(
			$parserOutputKey,
			$parserOutputData,
			$expire,
			BagOStuff::WRITE_ALLOW_SEGMENTS
		);

		// ...and its pointer to the local cache.
		$this->metadataProcCache->set( $pageKey, $metadataData, $expire );
		// ...and to the global cache.
		$this->cache->set( $pageKey, $metadataData, $expire );

		$title = $this->titleFactory->castFromPageIdentity( $page );
		$this->hookRunner->onParserCacheSaveComplete( $this, $parserOutput, $title, $popts, $revId );

		$this->logger->debug( 'Saved in parser cache', [
			'name' => $this->name,
			'key' => $parserOutputKey,
			'cache_time' => $cacheTime,
			'rev_id' => $revId
		] );
		$this->incrementStats( $page, 'save.success' );
	}

	/**
	 * Get the backend BagOStuff instance that
	 * powers the parser cache
	 *
	 * @since 1.30
	 * @internal
	 * @return BagOStuff
	 */
	public function getCacheStorage() {
		return $this->cache;
	}

	/**
	 * Check if $entry expired for $page given the $staleConstraint
	 * when fetching from $cacheTier.
	 * @param CacheTime $entry
	 * @param PageRecord $page
	 * @param int $staleConstraint One of USE_* constants.
	 * @param string $cacheTier
	 * @return bool
	 */
	private function checkExpired(
		CacheTime $entry,
		PageRecord $page,
		int $staleConstraint,
		string $cacheTier
	): bool {
		if ( $staleConstraint < self::USE_EXPIRED && $entry->expired( $page->getTouched() ) ) {
			$this->incrementStats( $page, "miss.expired" );
			$this->logger->debug( "{$cacheTier} key expired", [
				'name' => $this->name,
				'touched' => $page->getTouched(),
				'epoch' => $this->cacheEpoch,
				'cache_time' => $entry->getCacheTime()
			] );
			return true;
		}
		return false;
	}

	/**
	 * Check if $entry belongs to the latest revision of $page
	 * given $staleConstraint when fetched from $cacheTier.
	 * @param CacheTime $entry
	 * @param PageRecord $page
	 * @param int $staleConstraint One of USE_* constants.
	 * @param string $cacheTier
	 * @return bool
	 */
	private function checkOutdated(
		CacheTime $entry,
		PageRecord $page,
		int $staleConstraint,
		string $cacheTier
	): bool {
		$latestRevId = $page->getLatest( PageRecord::LOCAL );
		if ( $staleConstraint < self::USE_OUTDATED && $entry->isDifferentRevision( $latestRevId ) ) {
			$this->incrementStats( $page, "miss.revid" );
			$this->logger->debug( "{$cacheTier} key is for an old revision", [
				'name' => $this->name,
				'rev_id' => $latestRevId,
				'cached_rev_id' => $entry->getCacheRevisionId()
			] );
			return true;
		}
		return false;
	}

	/**
	 * @note setter for temporary feature flags, for use in testing.
	 * @internal
	 * @param bool $readJson
	 * @param bool $writeJson
	 */
	public function setJsonSupport( bool $readJson, bool $writeJson ): void {
		$this->readJson = $readJson;
		$this->writeJson = $writeJson;
	}

	/**
	 * @param string $jsonData
	 * @param string $key
	 * @param string $expectedClass
	 * @return CacheTime|ParserOutput|null
	 */
	private function restoreFromJson( string $jsonData, string $key, string $expectedClass ) {
		try {
			/** @var CacheTime $obj */
			$obj = $this->jsonCodec->unserialize( $jsonData, $expectedClass );
			return $obj;
		} catch ( InvalidArgumentException $e ) {
			$this->logger->error( "Unable to unserialize JSON", [
				'name' => $this->name,
				'cache_key' => $key,
				'message' => $e->getMessage()
			] );
			return null;
		}
	}

	/**
	 * @param CacheTime $obj
	 * @param string $key
	 * @return string|null
	 */
	private function encodeAsJson( CacheTime $obj, string $key ) {
		try {
			return $this->jsonCodec->serialize( $obj );
		} catch ( InvalidArgumentException $e ) {
			$this->logger->error( "Unable to serialize JSON", [
				'name' => $this->name,
				'cache_key' => $key,
				'message' => $e->getMessage(),
			] );
			return null;
		}
	}
}
