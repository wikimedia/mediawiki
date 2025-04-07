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

namespace MediaWiki\Parser;

use InvalidArgumentException;
use JsonException;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Utils\MWTimestamp;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Cache for ParserOutput objects.
 * The cache is split per ParserOptions.
 *
 * @since 1.36
 * @ingroup Cache Parser
 */
class RevisionOutputCache {

	/** @var string The name of this cache. Used as a root of the cache key. */
	private $name;

	/** @var WANObjectCache */
	private $cache;

	/**
	 * Anything cached prior to this is invalidated
	 *
	 * @var string
	 */
	private $cacheEpoch;

	/**
	 * Expiry time for cache entries.
	 *
	 * @var int
	 */
	private $cacheExpiry;

	/** @var JsonCodec */
	private $jsonCodec;

	/** @var StatsFactory */
	private $stats;

	/** @var LoggerInterface */
	private $logger;

	private GlobalIdGenerator $globalIdGenerator;

	/**
	 * @param string $name
	 * @param WANObjectCache $cache
	 * @param int $cacheExpiry Expiry for ParserOutput in $cache.
	 * @param string $cacheEpoch Anything before this timestamp is invalidated
	 * @param JsonCodec $jsonCodec
	 * @param StatsFactory $stats
	 * @param LoggerInterface $logger
	 * @param GlobalIdGenerator $globalIdGenerator
	 */
	public function __construct(
		string $name,
		WANObjectCache $cache,
		int $cacheExpiry,
		string $cacheEpoch,
		JsonCodec $jsonCodec,
		StatsFactory $stats,
		LoggerInterface $logger,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->name = $name;
		$this->cache = $cache;
		$this->cacheExpiry = $cacheExpiry;
		$this->cacheEpoch = $cacheEpoch;
		$this->jsonCodec = $jsonCodec;
		$this->stats = $stats;
		$this->logger = $logger;
		$this->globalIdGenerator = $globalIdGenerator;
	}

	/**
	 * @param RevisionRecord $revision
	 * @return string
	 */
	private function getContentModelFromRevision( RevisionRecord $revision ) {
		if ( !$revision->hasSlot( SlotRecord::MAIN ) ) {
			return 'missing';
		}
		return str_replace( '.', '_', $revision->getMainContentModel() );
	}

	/**
	 * @param RevisionRecord $revision
	 * @param string $status e.g. hit, miss etc.
	 * @param string|null $reason
	 */
	private function incrementStats( RevisionRecord $revision, string $status, ?string $reason = null ) {
		$contentModel = $this->getContentModelFromRevision( $revision );
		$metricSuffix = $reason ? "{$status}_{$reason}" : $status;

		$this->stats->getCounter( 'RevisionOutputCache_operation_total' )
			->setLabel( 'name', $this->name )
			->setLabel( 'contentModel', $contentModel )
			->setLabel( 'status', $status )
			->setLabel( 'reason', $reason ?: 'n/a' )
			->copyToStatsdAt( "RevisionOutputCache.{$this->name}.{$metricSuffix}" )
			->increment();
	}

	/**
	 * @param RevisionRecord $revision
	 * @param string $renderReason
	 */
	private function incrementRenderReasonStats( RevisionRecord $revision, $renderReason ) {
		$contentModel = $this->getContentModelFromRevision( $revision );
		$renderReason = preg_replace( '/\W+/', '_', $renderReason );

		$this->stats->getCounter( 'RevisionOutputCache_render_total' )
			->setLabel( 'name', $this->name )
			->setLabel( 'contentModel', $contentModel )
			->setLabel( 'reason', $renderReason )
			->increment();
	}

	/**
	 * Get a key that will be used by this cache to store the content
	 * for a given page considering the given options and the array of
	 * used options.
	 *
	 * If there is a possibility the revision does not have a revision id, use
	 * makeParserOutputKeyOptionalRevId() instead.
	 *
	 * @warning The exact format of the key is considered internal and is subject
	 * to change, thus should not be used as storage or long-term caching key.
	 * This is intended to be used for logging or keying something transient.
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions $options
	 * @param array|null $usedOptions currently ignored
	 * @return string
	 * @internal
	 */
	public function makeParserOutputKey(
		RevisionRecord $revision,
		ParserOptions $options,
		?array $usedOptions = null
	): string {
		$usedOptions = ParserOptions::allCacheVaryingOptions();

		$revId = $revision->getId();
		if ( !$revId ) {
			// If RevId is null, this would probably be unsafe to use as a cache key.
			throw new InvalidArgumentException( "Revision must have an id number" );
		}
		$hash = $options->optionsHash( $usedOptions );
		return $this->cache->makeKey( $this->name, $revId, $hash );
	}

	/**
	 * Get a key that will be used for locks or pool counter
	 *
	 * Similar to makeParserOutputKey except the revision id might be null,
	 * in which case it is unsafe to cache, but still needs a key for things like
	 * poolcounter.
	 *
	 * @warning The exact format of the key is considered internal and is subject
	 * to change, thus should not be used as storage or long-term caching key.
	 * This is intended to be used for logging or keying something transient.
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions $options
	 * @param array|null $usedOptions currently ignored
	 * @return string
	 * @internal
	 */
	public function makeParserOutputKeyOptionalRevId(
		RevisionRecord $revision,
		ParserOptions $options,
		?array $usedOptions = null
	): string {
		$usedOptions = ParserOptions::allCacheVaryingOptions();

		// revId may be null.
		$revId = (string)$revision->getId();
		$hash = $options->optionsHash( $usedOptions );
		return $this->cache->makeKey( $this->name, $revId, $hash );
	}

	/**
	 * Retrieve the ParserOutput from cache.
	 * false if not found or outdated.
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions $parserOptions
	 *
	 * @return ParserOutput|false False on failure
	 */
	public function get( RevisionRecord $revision, ParserOptions $parserOptions ) {
		if ( $this->cacheExpiry <= 0 ) {
			// disabled
			return false;
		}

		if ( !$parserOptions->isSafeToCache() ) {
			$this->incrementStats( $revision, 'miss', 'unsafe' );
			return false;
		}

		$cacheKey = $this->makeParserOutputKey( $revision, $parserOptions );
		$json = $this->cache->get( $cacheKey );

		if ( $json === false ) {
			$this->incrementStats( $revision, 'miss', 'absent' );
			return false;
		}

		$output = $this->restoreFromJson( $json, $cacheKey, ParserOutput::class );
		if ( $output === null ) {
			$this->incrementStats( $revision, 'miss', 'unserialize' );
			return false;
		}

		$cacheTime = (int)MWTimestamp::convert( TS_UNIX, $output->getCacheTime() );
		$expiryTime = (int)MWTimestamp::convert( TS_UNIX, $this->cacheEpoch );
		$expiryTime = max( $expiryTime, (int)MWTimestamp::now( TS_UNIX ) - $this->cacheExpiry );

		if ( $cacheTime < $expiryTime ) {
			$this->incrementStats( $revision, 'miss', 'expired' );
			return false;
		}

		$this->logger->debug( 'old-revision cache hit' );
		$this->incrementStats( $revision, 'hit' );
		return $output;
	}

	/**
	 * @param ParserOutput $output
	 * @param RevisionRecord $revision
	 * @param ParserOptions $parserOptions
	 * @param string|null $cacheTime TS_MW timestamp when the output was generated
	 */
	public function save(
		ParserOutput $output,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		?string $cacheTime = null
	) {
		if ( !$output->hasText() ) {
			throw new InvalidArgumentException( 'Attempt to cache a ParserOutput with no text set!' );
		}

		if ( $this->cacheExpiry <= 0 ) {
			// disabled
			return;
		}

		$cacheKey = $this->makeParserOutputKey( $revision, $parserOptions );

		// Ensure cache properties are set in the ParserOutput
		// T350538: These should be turned into assertions that the
		// properties are already present (and the $cacheTime argument
		// removed).
		if ( $cacheTime ) {
			$output->setCacheTime( $cacheTime );
		} else {
			$cacheTime = $output->getCacheTime();
		}
		if ( !$output->getCacheRevisionId() ) {
			$output->setCacheRevisionId( $revision->getId() );
		}
		if ( !$output->getRenderId() ) {
			$output->setRenderId( $this->globalIdGenerator->newUUIDv1() );
		}
		if ( !$output->getRevisionTimestamp() ) {
			$output->setRevisionTimestamp( $revision->getTimestamp() );
		}

		$msg = "Saved in RevisionOutputCache with key $cacheKey" .
			" and timestamp $cacheTime" .
			" and revision id {$revision->getId()}.";

		$output->addCacheMessage( $msg );

		// The ParserOutput might be dynamic and have been marked uncacheable by the parser.
		$output->updateCacheExpiry( $this->cacheExpiry );

		$expiry = $output->getCacheExpiry();
		if ( $expiry <= 0 ) {
			$this->incrementStats( $revision, 'save', 'uncacheable' );
			return;
		}

		if ( !$parserOptions->isSafeToCache() ) {
			$this->incrementStats( $revision, 'save', 'unsafe' );
			return;
		}

		$json = $this->encodeAsJson( $output, $cacheKey );
		if ( $json === null ) {
			$this->incrementStats( $revision, 'save', 'nonserializable' );
			return;
		}

		$this->cache->set( $cacheKey, $json, $expiry );
		$this->incrementStats( $revision, 'save', 'success' );
		$this->incrementRenderReasonStats( $revision, $parserOptions->getRenderReason() );
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
			$obj = $this->jsonCodec->deserialize( $jsonData, $expectedClass );
			return $obj;
		} catch ( JsonException $e ) {
			$this->logger->error( 'Unable to deserialize JSON', [
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
		} catch ( JsonException $e ) {
			$this->logger->error( 'Unable to serialize JSON', [
				'name' => $this->name,
				'cache_key' => $key,
				'message' => $e->getMessage(),
			] );
			return null;
		}
	}
}
