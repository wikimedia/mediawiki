<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Page;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\PoolCounter\PoolCounterFactory;
use MediaWiki\PoolCounter\PoolCounterWork;
use MediaWiki\PoolCounter\PoolCounterWorkViaCallback;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Assert\Assert;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\TracerInterface;

/**
 * Service for getting rendered output of a given page.
 *
 * This is a high level service, encapsulating concerns like caching
 * and stampede protection via PoolCounter.
 *
 * @since 1.36
 * @ingroup Page
 */
class ParserOutputAccess implements LoggerAwareInterface {

	/** @internal */
	public const PARSOID_PCACHE_NAME = 'parsoid-' . ParserCacheFactory::DEFAULT_NAME;

	/** @internal */
	public const PARSOID_RCACHE_NAME = 'parsoid-' . ParserCacheFactory::DEFAULT_RCACHE_NAME;

	/**
	 * @var int Do not check the cache before parsing (force parse)
	 */
	public const OPT_NO_CHECK_CACHE = 1;

	/** @var int Alias for NO_CHECK_CACHE */
	public const OPT_FORCE_PARSE = self::OPT_NO_CHECK_CACHE;

	/**
	 * @var int Do not update the cache after parsing.
	 */
	public const OPT_NO_UPDATE_CACHE = 2;

	/**
	 * @var int Bypass audience check for deleted/suppressed revisions.
	 *      The caller is responsible for ensuring that unauthorized access is prevented.
	 *      If not set, output generation will fail if the revision is not public.
	 */
	public const OPT_NO_AUDIENCE_CHECK = 4;

	/**
	 * @var int Do not check the cache before parsing,
	 *      and do not update the cache after parsing (not cacheable).
	 */
	public const OPT_NO_CACHE = self::OPT_NO_UPDATE_CACHE | self::OPT_NO_CHECK_CACHE;

	/**
	 * @var int Do perform an opportunistic LinksUpdate on cache miss
	 * @since 1.41
	 */
	public const OPT_LINKS_UPDATE = 8;

	/**
	 * Apply page view semantics. This relaxes some guarantees, specifically:
	 * - Use PoolCounter for stampede protection, causing the request to
	 *   block until another process has finished rendering the content.
	 * - Allow stale parser output to be returned to prevent long waits for
	 *   slow renders.
	 * - Allow cacheable placeholder output to be returned when PoolCounter
	 *   fails to obtain a lock. See the PoolCounterConf setting for details.
	 *
	 * @see Bug T352837
	 * @since 1.42
	 * @deprecated since 1.45, instead use OPT_POOL_COUNTER => POOL_COUNTER_ARTICLE_VIEW
	 *             and OPT_POOL_COUNTER_FALLBACK => true.
	 */
	public const OPT_FOR_ARTICLE_VIEW = 16;

	/**
	 * @var int Ignore the profile version of the result from the cache.
	 *      Otherwise, if it's not Parsoid's default, it will be invalidated.
	 */
	public const OPT_IGNORE_PROFILE_VERSION = 128;

	/**
	 * Whether to fall back to using stale content when failing to
	 * get a poolcounter lock.
	 */
	public const OPT_POOL_COUNTER_FALLBACK = 'poolcounter-fallback';

	/**
	 * @see MainConfigSchema::PoolCounterConf
	 */
	public const OPT_POOL_COUNTER = 'poolcounter-type';

	/**
	 * @see MainConfigSchema::PoolCounterConf
	 */
	public const POOL_COUNTER_ARTICLE_VIEW = 'ArticleView';

	/**
	 * @see MainConfigSchema::PoolCounterConf
	 */
	public const POOL_COUNTER_REST_API = 'HtmlRestApi';

	/**
	 * Defaults for options that are not covered by initializing
	 * bit-based keys to zero.
	 */
	private const DEFAULT_OPTIONS = [
		self::OPT_POOL_COUNTER => null,
		self::OPT_POOL_COUNTER_FALLBACK => false
	];

	/** @var string Do not read or write any cache */
	private const CACHE_NONE = 'none';

	/** @var string Use primary cache */
	private const CACHE_PRIMARY = 'primary';

	/** @var string Use secondary cache */
	private const CACHE_SECONDARY = 'secondary';

	/**
	 * In cases that an extension tries to get the same ParserOutput of
	 * the page right after it was parsed (T301310).
	 * @var MapCacheLRU<string,ParserOutput>
	 */
	private MapCacheLRU $localCache;

	private ParserCacheFactory $parserCacheFactory;
	private RevisionLookup $revisionLookup;
	private RevisionRenderer $revisionRenderer;
	private StatsFactory $statsFactory;
	private ChronologyProtector $chronologyProtector;
	private WikiPageFactory $wikiPageFactory;
	private TitleFormatter $titleFormatter;
	private TracerInterface $tracer;
	private PoolCounterFactory $poolCounterFactory;
	private LoggerInterface $logger;

	public function __construct(
		ParserCacheFactory $parserCacheFactory,
		RevisionLookup $revisionLookup,
		RevisionRenderer $revisionRenderer,
		StatsFactory $statsFactory,
		ChronologyProtector $chronologyProtector,
		WikiPageFactory $wikiPageFactory,
		TitleFormatter $titleFormatter,
		TracerInterface $tracer,
		PoolCounterFactory $poolCounterFactory
	) {
		$this->parserCacheFactory = $parserCacheFactory;
		$this->revisionLookup = $revisionLookup;
		$this->revisionRenderer = $revisionRenderer;
		$this->statsFactory = $statsFactory;
		$this->chronologyProtector = $chronologyProtector;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->titleFormatter = $titleFormatter;
		$this->tracer = $tracer;
		$this->poolCounterFactory = $poolCounterFactory;

		$this->localCache = new MapCacheLRU( 10 );
		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Converts bitfield options to an associative array.
	 *
	 * If the input is an array, any integer key that has multiple bits set will
	 * be split into separate keys for each bit. String keys remain unchanged.
	 *
	 * This method supports up to 32 bits.
	 *
	 * @param int|array $options
	 *
	 * @return array An associative array with one key for each bit,
	 *         plus any keys already present in the input.
	 */
	private static function normalizeOptions( $options ): array {
		$bits = 0;

		// TODO: Starting in 1.46, emit deprecation warnings when getting an int.

		if ( is_array( $options ) ) {
			if ( $options['_normalized_'] ?? false ) {
				// already normalized.
				return $options;
			}

			// Collect all bits from array keys, in case one of the keys
			// sets multiple bits.
			foreach ( $options as $key => $value ) {
				if ( is_int( $key ) ) {
					$bits |= $key;
				}
			}
		} else {
			$bits = $options;
			$options = [];
		}

		$b = 1;
		for ( $i = 0; $i < 32; $i++ ) {
			$options[$b] = ( $bits & $b ) > 0;
			$b <<= 1;
		}

		if ( $options[ self::OPT_FOR_ARTICLE_VIEW ] ) {
			$options[ self::OPT_POOL_COUNTER ] = self::POOL_COUNTER_ARTICLE_VIEW;
			$options[ self::OPT_POOL_COUNTER_FALLBACK ] = true;
		}

		$options += self::DEFAULT_OPTIONS;

		$options['_normalized_'] = true;
		return $options;
	}

	/**
	 * Use a cache?
	 *
	 * @param PageRecord $page
	 * @param RevisionRecord|null $rev
	 *
	 * @return string One of the CACHE_XXX constants.
	 */
	private function shouldUseCache(
		PageRecord $page,
		?RevisionRecord $rev
	) {
		if ( $rev && !$rev->getId() ) {
			// The revision isn't from the database, so the output can't safely be cached.
			return self::CACHE_NONE;
		}

		// NOTE: Keep in sync with ParserWikiPage::shouldCheckParserCache().
		// NOTE: when we allow caching of old revisions in the future,
		//       we must not allow caching of deleted revisions.

		$wikiPage = $this->wikiPageFactory->newFromTitle( $page );
		if ( !$page->exists() || !$wikiPage->getContentHandler()->isParserCacheSupported() ) {
			return self::CACHE_NONE;
		}

		$isOld = $rev && $rev->getId() !== $page->getLatest();
		if ( !$isOld ) {
			return self::CACHE_PRIMARY;
		}

		if ( !$rev->audienceCan( RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_PUBLIC ) ) {
			// deleted/suppressed revision
			return self::CACHE_NONE;
		}

		return self::CACHE_SECONDARY;
	}

	/**
	 * Get the rendered output for the given page if it is present in the cache.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $parserOptions
	 * @param RevisionRecord|null $revision
	 * @param int|array $options Bitfield or associative array using the OPT_XXX constants.
	 *        Passing an int is deprecated and will trigger deprecation warnings
	 *        in the future.
	 * @return ParserOutput|null
	 */
	public function getCachedParserOutput(
		PageRecord $page,
		ParserOptions $parserOptions,
		?RevisionRecord $revision = null,
		$options = []
	): ?ParserOutput {
		$options = self::normalizeOptions( $options );

		$span = $this->startOperationSpan( __FUNCTION__, $page, $revision );
		$isOld = $revision && $revision->getId() !== $page->getLatest();
		$useCache = $this->shouldUseCache( $page, $revision );
		$primaryCache = $this->getPrimaryCache( $parserOptions );
		$classCacheKey = $primaryCache->makeParserOutputKey( $page, $parserOptions );

		if ( $useCache === self::CACHE_PRIMARY ) {
			if ( $this->localCache->hasField( $classCacheKey, $page->getLatest() ) && !$isOld ) {
				return $this->localCache->getField( $classCacheKey, $page->getLatest() );
			}
			$output = $primaryCache->get( $page, $parserOptions );
		} elseif ( $useCache === self::CACHE_SECONDARY && $revision ) {
			$secondaryCache = $this->getSecondaryCache( $parserOptions );
			$output = $secondaryCache->get( $revision, $parserOptions );
		} else {
			$output = null;
		}

		$statType = $statReason = $output ? 'hit' : 'miss';

		if (
			$output && !$options[ self::OPT_IGNORE_PROFILE_VERSION ] &&
			$output->getContentHolder()->isParsoidContent()
		) {
			$pageBundle = $output->getContentHolder()->getBasePageBundle();
			// T333606: Force a reparse if the version coming from cache is not the default
			$cachedVersion = $pageBundle->version ?? null;
			if (
				$cachedVersion !== null && // T325137: BadContentModel, no sense in reparsing
				$cachedVersion !== Parsoid::defaultHTMLVersion()
			) {
				$statType = 'miss';
				$statReason = 'obsolete';
				$output = null;
			}
		}

		if ( $output && !$isOld ) {
			$this->localCache->setField( $classCacheKey, $page->getLatest(), $output );
		}

		$this->statsFactory
			->getCounter( 'parseroutputaccess_cache_total' )
			->setLabel( 'cache', $useCache )
			->setLabel( 'reason', $statReason )
			->setLabel( 'type', $statType )
			->copyToStatsdAt( "ParserOutputAccess.Cache.$useCache.$statReason" )
			->increment();

		return $output ?: null; // convert false to null
	}

	/**
	 * Fallback for use with PoolCounterWork.
	 * Returns stale cached output if appropriate.
	 *
	 * @return Status<ParserOutput>|false
	 */
	private function getFallbackOutputForLatest(
		PageRecord $page,
		ParserOptions $parserOptions,
		string $workKey,
		bool $fast
	) {
		$parserOutput = $this->getPrimaryCache( $parserOptions )
			->getDirty( $page, $parserOptions );

		if ( !$parserOutput ) {
			$this->logger->info( 'dirty missing' );
			return false;
		}

		if ( $fast ) {
			// If this user recently made DB changes, then don't eagerly serve stale output,
			// so that users generally see their own edits after page save.
			//
			// If PoolCounter is overloaded, we may end up here a second time (with fast=false),
			// in which case we will serve a stale fallback then.
			//
			// Note that CP reports anything in the last 10 seconds from the same client,
			// including to other pages and other databases, so we bias towards avoiding
			// fast-stale responses for several seconds after saving an edit.
			if ( $this->chronologyProtector->getTouched() ) {
				$this->logger->info(
					'declining fast-fallback to stale output since ChronologyProtector ' .
					'reports the client recently made changes',
					[ 'workKey' => $workKey ]
				);
				// Forget this ParserOutput -- we will request it again if
				// necessary in slow mode. There might be a newer entry
				// available by that time.
				return false;
			}
		}

		$this->logger->info( $fast ? 'fast dirty output' : 'dirty output', [ 'workKey' => $workKey ] );

		$status = Status::newGood( $parserOutput );
		$status->warning( 'view-pool-dirty-output' );
		$status->warning( $fast ? 'view-pool-contention' : 'view-pool-overload' );
		return $status;
	}

	/**
	 * Returns the rendered output for the given page.
	 * Caching and concurrency control is applied.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $parserOptions
	 * @param RevisionRecord|null $revision
	 * @param int|array $options Bitfield or associative array using the OPT_XXX constants.
	 *        Passing an int is deprecated and will trigger deprecation warnings
	 *        in the future.
	 *
	 * @return Status<ParserOutput> containing a ParserOutput if no error occurred.
	 *         Well-known errors and warnings include the following messages:
	 *         - 'view-pool-dirty-output' (warning) The output is dirty (from a stale cache entry).
	 *         - 'view-pool-contention' (warning) Dirty output was returned immediately instead of
	 *           waiting to acquire a work lock (when "fast stale" mode is enabled in PoolCounter).
	 *         - 'view-pool-timeout' (warning) Dirty output was returned after failing to acquire
	 *           a work lock (got QUEUE_FULL or TIMEOUT from PoolCounter).
	 *         - 'pool-queuefull' (error) unable to acquire work lock, and no cached content found.
	 *         - 'pool-timeout' (error) unable to acquire work lock, and no cached content found.
	 *         - 'pool-servererror' (error) PoolCounterWork failed due to a lock service error.
	 *         - 'pool-unknownerror' (error) PoolCounterWork failed for an unknown reason.
	 *         - 'nopagetext' (error) The page does not exist
	 */
	public function getParserOutput(
		PageRecord $page,
		ParserOptions $parserOptions,
		?RevisionRecord $revision = null,
		$options = []
	): Status {
		$options = self::normalizeOptions( $options );

		$span = $this->startOperationSpan( __FUNCTION__, $page, $revision );
		$error = $this->checkPreconditions( $page, $revision, $options );
		if ( $error ) {
			$this->statsFactory
				->getCounter( 'parseroutputaccess_case' )
				->setLabel( 'case', 'error' )
				->copyToStatsdAt( 'ParserOutputAccess.Case.error' )
				->increment();
			return $error;
		}

		$isOld = $revision && $revision->getId() !== $page->getLatest();
		if ( $isOld ) {
			$this->statsFactory
				->getCounter( 'parseroutputaccess_case' )
				->setLabel( 'case', 'old' )
				->copyToStatsdAt( 'ParserOutputAccess.Case.old' )
				->increment();
		} else {
			$this->statsFactory
				->getCounter( 'parseroutputaccess_case' )
				->setLabel( 'case', 'current' )
				->copyToStatsdAt( 'ParserOutputAccess.Case.current' )
				->increment();
		}

		if ( !$options[ self::OPT_NO_CHECK_CACHE ] ) {
			$output = $this->getCachedParserOutput( $page, $parserOptions, $revision );
			if ( $output ) {
				return Status::newGood( $output );
			}
		}

		if ( !$revision ) {
			$revId = $page->getLatest();
			$revision = $revId ? $this->revisionLookup->getRevisionById( $revId ) : null;

			if ( !$revision ) {
				$this->statsFactory
					->getCounter( 'parseroutputaccess_status' )
					->setLabel( 'status', 'norev' )
					->copyToStatsdAt( "ParserOutputAccess.Status.norev" )
					->increment();
				return Status::newFatal( 'missing-revision', $revId );
			}
		}

		if ( $options[ self::OPT_POOL_COUNTER ] ) {
			$work = $this->newPoolWork( $page, $parserOptions, $revision, $options );
			/** @var Status $status */
			$status = $work->execute();
		} else {
			// XXX: we could try harder to reuse a cache lookup above to
			// provide the $previous argument here
			$this->statsFactory->getCounter( 'parseroutputaccess_render_total' )
				->setLabel( 'pool', 'none' )
				->setLabel( 'cache', self::CACHE_NONE )
				->copyToStatsdAt( 'ParserOutputAccess.PoolWork.None' )
				->increment();

			$status = $this->renderRevision( $page, $parserOptions, $revision, $options, null );
		}

		$output = $status->getValue();
		Assert::postcondition( $output || !$status->isOK(), 'Inconsistent status' );

		if ( $output && !$isOld ) {
			$primaryCache = $this->getPrimaryCache( $parserOptions );
			$classCacheKey = $primaryCache->makeParserOutputKey( $page, $parserOptions );
			$this->localCache->setField( $classCacheKey, $page->getLatest(), $output );
		}

		if ( $status->isGood() ) {
			$this->statsFactory->getCounter( 'parseroutputaccess_status' )
				->setLabel( 'status', 'good' )
				->copyToStatsdAt( 'ParserOutputAccess.Status.good' )
				->increment();
		} elseif ( $status->isOK() ) {
			$this->statsFactory->getCounter( 'parseroutputaccess_status' )
				->setLabel( 'status', 'ok' )
				->copyToStatsdAt( 'ParserOutputAccess.Status.ok' )
				->increment();
		} else {
			$this->statsFactory->getCounter( 'parseroutputaccess_status' )
				->setLabel( 'status', 'error' )
				->copyToStatsdAt( 'ParserOutputAccess.Status.error' )
				->increment();
		}

		return $status;
	}

	/**
	 * Render the given revision.
	 *
	 * This method will update the parser cache if appropriate, and will
	 * trigger a links update if OPT_LINKS_UPDATE is set.
	 *
	 * This method does not perform access checks, and will not load content
	 * from caches. The caller is assumed to have taken care of that.
	 *
	 * Where possible, pass in a $previousOutput, which will prevent an
	 * unnecessary double-lookup in the cache.
	 *
	 * @see PoolWorkArticleView::renderRevision
	 * @return Status<ParserOutput>
	 */
	private function renderRevision(
		PageRecord $page,
		ParserOptions $parserOptions,
		RevisionRecord $revision,
		array $options,
		?ParserOutput $previousOutput = null
	): Status {
		$span = $this->startOperationSpan( __FUNCTION__, $page, $revision );

		$isCurrent = $revision->getId() === $page->getLatest();
		$useCache = $this->shouldUseCache( $page, $revision );

		// T371713: Temporary statistics collection code to determine
		// feasibility of Parsoid selective update
		$sampleRate = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParsoidSelectiveUpdateSampleRate
		);
		$doSample = ( $sampleRate && mt_rand( 1, $sampleRate ) === 1 );

		if ( $previousOutput === null && ( $doSample || $parserOptions->getUseParsoid() ) ) {
			// If $useCache === self::CACHE_SECONDARY we could potentially
			// try to reuse the parse of $revision-1 from the secondary cache,
			// but it is likely those template transclusions are out of date.
			// Try to reuse the template transclusions from the most recent
			// parse, which are more likely to reflect the current template.
			if ( !$options[ self::OPT_NO_CHECK_CACHE ] ) {
				$previousOutput = $this->getPrimaryCache( $parserOptions )->getDirty( $page, $parserOptions ) ?: null;
			}
		}

		$renderedRev = $this->revisionRenderer->getRenderedRevision(
			$revision,
			$parserOptions,
			null,
			[
				'audience' => RevisionRecord::RAW,
				'previous-output' => $previousOutput,
			]
		);

		$output = $renderedRev->getRevisionParserOutput();

		if ( $doSample ) {
			$labels = [
				'source' => 'ParserOutputAccess',
				'type' => $previousOutput === null ? 'full' : 'selective',
				'reason' => $parserOptions->getRenderReason(),
				'parser' => $parserOptions->getUseParsoid() ? 'parsoid' : 'legacy',
				'opportunistic' => 'false',
				'wiki' => WikiMap::getCurrentWikiId(),
				'model' => $revision->getMainContentModel(),
			];
			$this->statsFactory
				->getCounter( 'ParserCache_selective_total' )
				->setLabels( $labels )
				->increment();
			$this->statsFactory
				->getCounter( 'ParserCache_selective_cpu_seconds' )
				->setLabels( $labels )
				->incrementBy( $output->getTimeProfile( 'cpu' ) );
		}

		if ( !$options[ self::OPT_NO_UPDATE_CACHE ] && $output->isCacheable() ) {
			if ( $useCache === self::CACHE_PRIMARY ) {
				$primaryCache = $this->getPrimaryCache( $parserOptions );
				$primaryCache->save( $output, $page, $parserOptions );
			} elseif ( $useCache === self::CACHE_SECONDARY ) {
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$secondaryCache->save( $output, $revision, $parserOptions );
			}
		}

		if ( $options[ self::OPT_LINKS_UPDATE ] ) {
			$this->wikiPageFactory->newFromTitle( $page )
				->triggerOpportunisticLinksUpdate( $output );
		}

		return Status::newGood( $output );
	}

	private function checkPreconditions(
		PageRecord $page,
		?RevisionRecord $revision = null,
		array $options = []
	): ?Status {
		if ( !$page->exists() ) {
			return Status::newFatal( 'nopagetext' );
		}

		if ( !$options[ self::OPT_NO_UPDATE_CACHE ] && $revision && !$revision->getId() ) {
			throw new InvalidArgumentException(
				'The revision does not have a known ID. Use OPT_NO_CACHE.'
			);
		}

		if ( $revision && $revision->getPageId() !== $page->getId() ) {
			throw new InvalidArgumentException(
				'The revision does not belong to the given page.'
			);
		}

		if ( $revision && !$options[ self::OPT_NO_AUDIENCE_CHECK ] ) {
			// NOTE: If per-user checks are desired, the caller should perform them and
			//       then set OPT_NO_AUDIENCE_CHECK if they passed.
			if ( !$revision->audienceCan( RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_PUBLIC ) ) {
				return Status::newFatal(
					'missing-revision-permission',
					$revision->getId(),
					$revision->getTimestamp(),
					$this->titleFormatter->getPrefixedDBkey( $page )
				);
			}
		}

		return null;
	}

	protected function newPoolWork(
		PageRecord $page,
		ParserOptions $parserOptions,
		RevisionRecord $revision,
		array $options
	): PoolCounterWork {
		// Default behavior (no caching)
		$callbacks = [
			'doWork' => function () use ( $page, $parserOptions, $revision, $options ) {
				return $this->renderRevision(
					$page,
					$parserOptions,
					$revision,
					$options
				);
			},
			'doCachedWork' => static function () {
				// uncached
				return false;
			},
			'fallback' => static function ( $fast ) {
				// no fallback
				return false;
			},
			'error' => static function ( $status ) {
				return $status;
			}
		];

		$useCache = $this->shouldUseCache( $page, $revision );

		$statCacheLabelLegacy = match ( $useCache ) {
			self::CACHE_PRIMARY => 'Current',
			self::CACHE_SECONDARY => 'Old',
			default => 'Uncached',
		};

		$this->statsFactory->getCounter( 'parseroutputaccess_render_total' )
			->setLabel( 'pool', 'articleview' )
			->setLabel( 'cache', $useCache )
			->copyToStatsdAt( "ParserOutputAccess.PoolWork.$statCacheLabelLegacy" )
			->increment();

		switch ( $useCache ) {
			case self::CACHE_PRIMARY:
				$primaryCache = $this->getPrimaryCache( $parserOptions );
				$parserCacheMetadata = $primaryCache->getMetadata( $page );
				$cacheKey = $primaryCache->makeParserOutputKey( $page, $parserOptions,
					$parserCacheMetadata ? $parserCacheMetadata->getUsedOptions() : null
				);

				$workKey = $cacheKey . ':revid:' . $revision->getId();

				$callbacks['doCachedWork'] =
					static function () use ( $primaryCache, $page, $parserOptions ) {
						$parserOutput = $primaryCache->get( $page, $parserOptions );
						return $parserOutput ? Status::newGood( $parserOutput ) : false;
					};

				$callbacks['fallback'] =
					function ( $fast ) use ( $page, $parserOptions, $workKey, $options ) {
						if ( $options[ self::OPT_POOL_COUNTER_FALLBACK ] ) {
							return $this->getFallbackOutputForLatest(
								$page, $parserOptions, $workKey, $fast
							);
						} else {
							return false;
						}
					};

				break;

			case self::CACHE_SECONDARY:
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$workKey = $secondaryCache->makeParserOutputKey( $revision, $parserOptions );

				$callbacks['doCachedWork'] =
					static function () use ( $secondaryCache, $revision, $parserOptions ) {
						$parserOutput = $secondaryCache->get( $revision, $parserOptions );

						return $parserOutput ? Status::newGood( $parserOutput ) : false;
					};

				break;

			default:
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$workKey = $secondaryCache->makeParserOutputKeyOptionalRevId( $revision, $parserOptions );
		}

		$profile = $options[ self::OPT_POOL_COUNTER ];
		$pool = $this->poolCounterFactory->create( $profile, $workKey );
		return new PoolCounterWorkViaCallback(
			$pool,
			$workKey,
			$callbacks
		);
	}

	private function getPrimaryCache( ParserOptions $pOpts ): ParserCache {
		if ( $pOpts->getUseParsoid() ) {
			return $this->parserCacheFactory->getParserCache(
				self::PARSOID_PCACHE_NAME
			);
		}

		return $this->parserCacheFactory->getParserCache(
			ParserCacheFactory::DEFAULT_NAME
		);
	}

	private function getSecondaryCache( ParserOptions $pOpts ): RevisionOutputCache {
		if ( $pOpts->getUseParsoid() ) {
			return $this->parserCacheFactory->getRevisionOutputCache(
				self::PARSOID_RCACHE_NAME
			);
		}

		return $this->parserCacheFactory->getRevisionOutputCache(
			ParserCacheFactory::DEFAULT_RCACHE_NAME
		);
	}

	private function startOperationSpan(
		string $opName,
		PageRecord $page,
		?RevisionRecord $revision = null
	): SpanInterface {
		$span = $this->tracer->createSpan( "ParserOutputAccess::$opName" );
		if ( $span->getContext()->isSampled() ) {
			$span->setAttributes( [
				'org.wikimedia.parser.page' => $page->__toString(),
				'org.wikimedia.parser.page.id' => $page->getId(),
				'org.wikimedia.parser.page.wiki' => $page->getWikiId(),
			] );
			if ( $revision ) {
				$span->setAttributes( [
					'org.wikimedia.parser.revision.id' => $revision->getId(),
					'org.wikimedia.parser.revision.parent_id' => $revision->getParentId(),
				] );
			}
		}
		return $span->start()->activate();
	}

	/**
	 * Clear the local cache
	 * @since 1.45
	 */
	public function clearLocalCache() {
		$this->localCache->clear();
	}
}
