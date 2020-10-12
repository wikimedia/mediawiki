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
use MediaWiki\Parser\ParserCacheMetadata;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;

/**
 * @ingroup Cache Parser
 * @todo document
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

	/** @var IBufferingStatsdDataFactory */
	private $stats;

	/** @var LoggerInterface */
	private $logger;

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
	 * @param IBufferingStatsdDataFactory $stats
	 * @param LoggerInterface $logger
	 * @param bool $useJson Temporary feature flag, remove before 1.36 is released.
	 */
	public function __construct(
		string $name,
		BagOStuff $cache,
		string $cacheEpoch,
		HookContainer $hookContainer,
		IBufferingStatsdDataFactory $stats,
		LoggerInterface $logger,
		$useJson = false
	) {
		$this->name = $name;
		$this->cache = $cache;
		$this->cacheEpoch = $cacheEpoch;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->stats = $stats;
		$this->logger = $logger;
		$this->readJson = $useJson;
		$this->writeJson = $useJson;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @since 1.28
	 */
	public function deleteOptionsKey( WikiPage $wikiPage ) {
		$this->cache->delete( $this->makeMetadataKey( $wikiPage ) );
	}

	/**
	 * Provides an E-Tag suitable for the whole page. Note that $wikiPage
	 * is just the main wikitext. The E-Tag has to be unique to the whole
	 * page, even if the article itself is the same, so it uses the
	 * complete set of user options. We don't want to use the preference
	 * of a different user on a message just because it wasn't used in
	 * $wikiPage. For example give a Chinese interface to a user with
	 * English preferences. That's why we take into account *all* user
	 * options. (r70809 CR)
	 *
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @return string
	 */
	public function getETag( WikiPage $wikiPage, $popts ) {
		return 'W/"' . $this->makeParserOutputKey( $wikiPage, $popts )
			. "--" . $wikiPage->getTouched() . '"';
	}

	/**
	 * Retrieve the ParserOutput from ParserCache, even if it's outdated.
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @return ParserOutput|bool False on failure
	 */
	public function getDirty( WikiPage $wikiPage, $popts ) {
		$value = $this->get( $wikiPage, $popts, true );
		return is_object( $value ) ? $value : false;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @param string $metricSuffix
	 */
	private function incrementStats( WikiPage $wikiPage, $metricSuffix ) {
		$contentModel = str_replace( '.', '_', $wikiPage->getContentModel() );
		$metricSuffix = str_replace( '.', '_', $metricSuffix );
		$this->stats->increment( "{$this->name}.{$contentModel}.{$metricSuffix}" );
	}

	/**
	 * Generates a key for caching the given page considering
	 * the given parser options.
	 *
	 * @note Which parser options influence the cache key
	 * is controlled via ParserOutput::recordOption() or
	 * ParserOptions::addExtraKey().
	 *
	 * @note Used by Article to provide a unique id for the PoolCounter.
	 * It would be preferable to have this code in get()
	 * instead of having Article looking in our internals.
	 *
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @param int|bool $useOutdated One of the USE constants. For backwards
	 *  compatibility, boolean false is treated as USE_CURRENT_ONLY and
	 *  boolean true is treated as USE_ANYTHING.
	 * @return bool|mixed|string
	 * @since 1.30 Changed $useOutdated to an int and added the non-boolean values
	 * @deprecated 1.36 Use ::getMetadata and ::makeParserOutputKey methods instead.
	 */
	public function getKey( WikiPage $wikiPage, $popts, $useOutdated = self::USE_ANYTHING ) {
		wfDeprecated( __METHOD__, '1.36' );
		if ( is_bool( $useOutdated ) ) {
			$useOutdated = $useOutdated ? self::USE_ANYTHING : self::USE_CURRENT_ONLY;
		}

		if ( $popts instanceof User ) {
			$this->logger->warning(
				"Use of outdated prototype ParserCache::getKey( &\$wikiPage, &\$user )\n"
			);
			$popts = ParserOptions::newFromUser( $popts );
		}

		$metadata = $this->getMetadata( $wikiPage, $useOutdated );
		if ( !$metadata ) {
			if ( $useOutdated < self::USE_ANYTHING ) {
				return false;
			}
			$usedOptions = ParserOptions::allCacheVaryingOptions();
		} else {
			$usedOptions = $metadata->getUsedOptions();
		}

		return $this->makeParserOutputKey( $wikiPage, $popts, $usedOptions );
	}

	/**
	 * Returns the ParserCache metadata about the given page
	 * considering the given options.
	 *
	 * @note Which parser options influence the cache key
	 * is controlled via ParserOutput::recordOption() or
	 * ParserOptions::addExtraKey().
	 *
	 * @param WikiPage $wikiPage
	 * @param int $staleConstraint one of the self::USE_ constants
	 * @return ParserCacheMetadata|null
	 * @since 1.36
	 */
	public function getMetadata(
		WikiPage $wikiPage,
		int $staleConstraint = self::USE_ANYTHING
	): ?ParserCacheMetadata {
		$pageKey = $this->makeMetadataKey( $wikiPage );
		$metadata = $this->cache->get(
			$pageKey,
			BagOStuff::READ_VERIFIED
		);

		// NOTE: If the value wasn't serialized to JSON when being stored,
		//       we may already have a ParserOutput object here. This used
		//       to be the default behavior before 1.36. We need to retain
		//       support so we can handle cached objects after an update
		//       from an earlier revision.
		// NOTE: Support for reading string values from the cache must be
		//       deployed a while before starting to write JSON to the cache,
		//       in case we have to revert either change.
		if ( is_string( $metadata ) && $this->readJson ) {
			$metadata = $this->restoreFromJson( $metadata, $pageKey );
		}

		if ( $metadata instanceof CacheTime ) {
			if (
				$staleConstraint < self::USE_EXPIRED
				&& $metadata->expired( $wikiPage->getTouched() )
			) {
				$this->incrementStats( $wikiPage, "miss.expired" );
				$this->logger->debug( 'Parser options key expired', [
					'name' => $this->name,
					'touched' => $wikiPage->getTouched(),
					'epoch' => $this->cacheEpoch,
					'cache_time' => $metadata->getCacheTime()
				] );
				return null;
			} elseif ( $staleConstraint < self::USE_OUTDATED &&
				$metadata->isDifferentRevision( $wikiPage->getLatest() )
			) {
				$this->incrementStats( $wikiPage, "miss.revid" );
				$this->logger->debug( 'ParserOutput key is for an old revision', [
					'name' => $this->name,
					'rev_id' => $wikiPage->getLatest(),
					'cached_rev_id' => $metadata->getCacheRevisionId()
				] );
				return null;
			}

			// HACK: The property 'mUsedOptions' was made private in the initial
			// deployment of mediawiki 1.36.0-wmf.11. Thus anything it stored is
			// broken and incompatible with wmf.10. We can't use RejectParserCacheValue
			// because that hook does not run until later.
			// See https://phabricator.wikimedia.org/T264257
			if ( !isset( $metadata->mUsedOptions ) ) {
				$this->logger->debug( 'Bad ParserOutput key from wmf.11 T264257', [
					'name' => $this->name
				] );
				return null;
			}

			$this->logger->debug( 'Parser cache options found', [
				'name' => $this->name
			] );
			return $metadata;
		}
		return null;
	}

	/**
	 * @param WikiPage $wikiPage
	 * @return string
	 */
	private function makeMetadataKey( WikiPage $wikiPage ): string {
		return $this->cache->makeKey( $this->name, 'idoptions', $wikiPage->getId() );
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
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $options
	 * @param array|null $usedOptions Defaults to all cache verying options.
	 * @return string
	 * @internal
	 * @since 1.36
	 */
	public function makeParserOutputKey(
		WikiPage $wikiPage,
		ParserOptions $options,
		array $usedOptions = null
	): string {
		global $wgRequest;
		$usedOptions = $usedOptions ?? ParserOptions::allCacheVaryingOptions();

		// idhash seem to mean 'page id' + 'rendering hash' (r3710)
		$pageid = $wikiPage->getId();
		// TODO: remove the split T263581
		$renderkey = (int)( $wgRequest->getVal( 'action' ) == 'render' );
		$hash = $options->optionsHash( $usedOptions, $wikiPage->getTitle() );

		return $this->cache->makeKey( $this->name, 'idhash', "{$pageid}-{$renderkey}!{$hash}" );
	}

	/**
	 * Retrieve the ParserOutput from ParserCache.
	 * false if not found or outdated.
	 *
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @param bool $useOutdated (default false)
	 *
	 * @return ParserOutput|bool False on failure
	 */
	public function get( WikiPage $wikiPage, $popts, $useOutdated = false ) {
		$canCache = $wikiPage->checkTouched();
		if ( !$canCache ) {
			// It's a redirect now
			return false;
		}

		$touched = $wikiPage->getTouched();

		$parserOutputMetadata = $this->getMetadata(
			$wikiPage,
			$useOutdated ? self::USE_OUTDATED : self::USE_CURRENT_ONLY
		);
		if ( !$parserOutputMetadata ) {
			$this->incrementStats( $wikiPage, 'miss.absent' );
			return false;
		}

		$parserOutputKey = $this->makeParserOutputKey(
			$wikiPage,
			$popts,
			$parserOutputMetadata->getUsedOptions()
		);

		$value = $this->cache->get( $parserOutputKey, BagOStuff::READ_VERIFIED );
		if ( $value === false ) {
			$this->incrementStats( $wikiPage, "miss.absent" );
			$this->logger->debug( 'ParserOutput cache miss', [
				'name' => $this->name
			] );
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
			$value = $this->restoreFromJson( $value, $parserOutputKey );
		}

		if ( !$value instanceof ParserOutput ) {
			$this->logger->debug( "ParserOutput bad endtry.", [ 'name' => $this->name ] );
			return false;
		}

		/** @var ParserOutput $value */
		$this->logger->debug( 'ParserOutput cache found', [
			'name' => $this->name
		] );

		if ( !$useOutdated && $value->expired( $touched ) ) {
			$this->incrementStats( $wikiPage, "miss.expired" );
			$this->logger->debug( 'ParserOutput key expired', [
				'name' => $this->name,
				'touched' => $touched,
				'epoch' => $this->cacheEpoch,
				'cache_time' => $value->getCacheTime()
			] );
			$value = false;
		} elseif (
			!$useOutdated
			&& $value->isDifferentRevision( $wikiPage->getLatest() )
		) {
			$this->incrementStats( $wikiPage, "miss.revid" );
			$this->logger->debug( 'ParserOutput key is for an old revision', [
				'name' => $this->name,
				'rev_id' => $wikiPage->getLatest(),
				'cached_rev_id' => $value->getCacheRevisionId()
			] );
			$value = false;
		} elseif (
			$this->hookRunner->onRejectParserCacheValue( $value, $wikiPage, $popts ) === false
		) {
			$this->incrementStats( $wikiPage, 'miss.rejected' );
			$this->logger->debug( 'key valid, but rejected by RejectParserCacheValue hook handler',
				[ 'name' => $this->name ] );
			$value = false;
		} else {
			$this->incrementStats( $wikiPage, "hit" );
		}

		return $value;
	}

	/**
	 * @param ParserOutput $parserOutput
	 * @param WikiPage $wikiPage
	 * @param ParserOptions $popts
	 * @param string|null $cacheTime TS_MW timestamp when the cache was generated
	 * @param int|null $revId Revision ID that was parsed
	 */
	public function save(
		ParserOutput $parserOutput,
		WikiPage $wikiPage,
		$popts,
		$cacheTime = null,
		$revId = null
	) {
		if ( !$parserOutput->hasText() ) {
			throw new InvalidArgumentException( 'Attempt to cache a ParserOutput with no text set!' );
		}

		$expire = $parserOutput->getCacheExpiry();
		if ( $expire > 0 && !$this->cache instanceof EmptyBagOStuff ) {
			$cacheTime = $cacheTime ?: wfTimestampNow();
			if ( !$revId ) {
				$revision = $wikiPage->getRevisionRecord();
				$revId = $revision ? $revision->getId() : null;
			}

			$metadata = new CacheTime;
			$metadata->mUsedOptions = $parserOutput->getUsedOptions();
			$metadata->updateCacheExpiry( $expire );

			$metadata->setCacheTime( $cacheTime );
			$parserOutput->setCacheTime( $cacheTime );
			$metadata->setCacheRevisionId( $revId );
			$parserOutput->setCacheRevisionId( $revId );

			$parserOutputKey = $this->makeParserOutputKey(
				$wikiPage,
				$popts,
				$metadata->getUsedOptions()
			);

			// Save the timestamp so that we don't have to load the revision row on view
			$parserOutput->setTimestamp( $wikiPage->getTimestamp() );

			$msg = "Saved in parser cache with key $parserOutputKey" .
				" and timestamp $cacheTime" .
				" and revision id $revId";

			$parserOutput->addCacheMessage( $msg );

			$this->logger->debug( 'Saved in parser cache', [
				'name' => $this->name,
				'key' => $parserOutputKey,
				'cache_time' => $cacheTime,
				'rev_id' => $revId
			] );

			$pageKey = $this->makeMetadataKey( $wikiPage );

			if ( $this->writeJson ) {
				$parserOutputData = $this->encodeAsJson( $parserOutput, $parserOutputKey );
				$metadataData = $this->encodeAsJson( $metadata, $pageKey );
			} else {
				// rely on implicit PHP serialization in the cache
				$parserOutputData = $parserOutput;
				$metadataData = $metadata;
			}

			if ( $parserOutputData && $metadataData ) {
				// Save the parser output
				$this->cache->set(
					$parserOutputKey,
					$parserOutputData,
					$expire,
					BagOStuff::WRITE_ALLOW_SEGMENTS
				);

				// ...and its pointer
				$this->cache->set( $pageKey, $metadataData, $expire );

				$this->hookRunner->onParserCacheSaveComplete(
					$this, $parserOutput, $wikiPage->getTitle(), $popts, $revId );
			}
		} elseif ( $expire <= 0 ) {
			$this->logger->debug(
				'Parser output was marked as uncacheable and has not been saved',
				[ 'name' => $this->name ]
			);
		}
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
	 *
	 * @return CacheTime|null
	 */
	private function restoreFromJson( string $jsonData, string $key ) {
		$jsonData = FormatJson::decode( $jsonData, true );
		if ( !$jsonData ) {
			$this->logger->error(
				"Invalid JSON",
				[ 'cache_key' => $key, 'json_error' => json_last_error(), ] );
			return null;
		}

		if ( !isset( $jsonData['_type_'] ) ) {
			$this->logger->error( "No _type_ field in JSON data",
				[ 'cache_key' => $key ]
			);
			return null;
		}

		// NOTE: Allowing the factory method to be specified directly in the data is tempting,
		//       but would be insecure. Information in $jsonData must be considered unsafe,
		//       since an attacker gaining access to the network could write to e.g. memcache.
		$type = $jsonData['_type_'];
		$factoryMapping = [
			'CacheTime' => [ CacheTime::class, 'newFromJson' ],
			'ParserOutput' => [ ParserOutput::class, 'newFromJson' ],
		];

		if ( !isset( $factoryMapping[$type] ) ) {
			$this->logger->error( "Unknown value in _type_ field in JSON data",
				[ 'cache_key' => $key, 'json_type' => $type ]
			);
			return null;
		}

		$factory = $factoryMapping[$type];
		$obj = $factory( $jsonData );

		Assert::postcondition(
			$obj instanceof CacheTime,
			'Factory method must return a CacheTime instance'
		);

		return $obj;
	}

	/**
	 * @param CacheTime $obj
	 * @param string $key
	 *
	 * @return string|null
	 */
	private function encodeAsJson( CacheTime $obj, string $key ) {
		$data = $obj->jsonSerialize();
		$json = FormatJson::encode( $data );

		if ( !$json ) {
			$this->logger->error(
				"JSON encoding failed!",
				[ 'cache_key' => $key, 'json_error' => json_last_error(), ]
			);
		}

		return $json ?: null;
	}
}
