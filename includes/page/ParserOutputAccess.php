<?php
/**
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
 */
namespace MediaWiki\Page;

use IBufferingStatsdDataFactory;
use InvalidArgumentException;
use MapCacheLRU;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\PoolCounter\PoolCounterWork;
use MediaWiki\PoolCounter\PoolWorkArticleView;
use MediaWiki\PoolCounter\PoolWorkArticleViewCurrent;
use MediaWiki\PoolCounter\PoolWorkArticleViewOld;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleFormatter;
use ParserCache;
use ParserOptions;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ILBFactory;

/**
 * Service for getting rendered output of a given page.
 *
 * This is a high level service, encapsulating concerns like caching
 * and stampede protection via PoolCounter.
 *
 * @since 1.36
 * @ingroup Page
 */
class ParserOutputAccess {

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
	 */
	public const OPT_FOR_ARTICLE_VIEW = 16;

	/** @var string Do not read or write any cache */
	private const CACHE_NONE = 'none';

	/** @var string Use primary cache */
	private const CACHE_PRIMARY = 'primary';

	/** @var string Use secondary cache */
	private const CACHE_SECONDARY = 'secondary';

	private ParserCacheFactory $parserCacheFactory;

	/**
	 * In cases that an extension tries to get the same ParserOutput of
	 * the page right after it was parsed (T301310).
	 * @var MapCacheLRU<string,ParserOutput>
	 */
	private MapCacheLRU $localCache;

	/** @var RevisionLookup */
	private $revisionLookup;

	/** @var RevisionRenderer */
	private $revisionRenderer;

	/** @var IBufferingStatsdDataFactory */
	private $statsDataFactory;

	/** @var ILBFactory */
	private $lbFactory;
	private ChronologyProtector $chronologyProtector;

	/** @var LoggerSpi */
	private $loggerSpi;

	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var TitleFormatter */
	private $titleFormatter;

	/**
	 * @param ParserCacheFactory $parserCacheFactory
	 * @param RevisionLookup $revisionLookup
	 * @param RevisionRenderer $revisionRenderer
	 * @param IBufferingStatsdDataFactory $statsDataFactory
	 * @param ILBFactory $lbFactory
	 * @param ChronologyProtector $chronologyProtector
	 * @param LoggerSpi $loggerSpi
	 * @param WikiPageFactory $wikiPageFactory
	 * @param TitleFormatter $titleFormatter
	 */
	public function __construct(
		ParserCacheFactory $parserCacheFactory,
		RevisionLookup $revisionLookup,
		RevisionRenderer $revisionRenderer,
		IBufferingStatsdDataFactory $statsDataFactory,
		ILBFactory $lbFactory,
		ChronologyProtector $chronologyProtector,
		LoggerSpi $loggerSpi,
		WikiPageFactory $wikiPageFactory,
		TitleFormatter $titleFormatter
	) {
		$this->parserCacheFactory = $parserCacheFactory;
		$this->revisionLookup = $revisionLookup;
		$this->revisionRenderer = $revisionRenderer;
		$this->statsDataFactory = $statsDataFactory;
		$this->lbFactory = $lbFactory;
		$this->chronologyProtector = $chronologyProtector;
		$this->loggerSpi = $loggerSpi;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->titleFormatter = $titleFormatter;

		$this->localCache = new MapCacheLRU( 10 );
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
	 * Returns the rendered output for the given page if it is present in the cache.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $parserOptions
	 * @param RevisionRecord|null $revision
	 * @param int $options Bitfield using the OPT_XXX constants
	 *
	 * @return ParserOutput|null
	 */
	public function getCachedParserOutput(
		PageRecord $page,
		ParserOptions $parserOptions,
		?RevisionRecord $revision = null,
		int $options = 0
	): ?ParserOutput {
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

		if ( $output && !$isOld ) {
			$this->localCache->setField( $classCacheKey, $page->getLatest(), $output );
		}

		if ( $output ) {
			$this->statsDataFactory->increment( "ParserOutputAccess.Cache.$useCache.hit" );
		} else {
			$this->statsDataFactory->increment( "ParserOutputAccess.Cache.$useCache.miss" );
		}

		return $output ?: null; // convert false to null
	}

	/**
	 * Returns the rendered output for the given page.
	 * Caching and concurrency control is applied.
	 *
	 * @param PageRecord $page
	 * @param ParserOptions $parserOptions
	 * @param RevisionRecord|null $revision
	 * @param int $options Bitfield using the OPT_XXX constants
	 *
	 * @return Status containing a ParserOutput if no error occurred.
	 *         Well known errors and warnings include the following messages:
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
		int $options = 0
	): Status {
		$error = $this->checkPreconditions( $page, $revision, $options );
		if ( $error ) {
			$this->statsDataFactory->increment( "ParserOutputAccess.Case.error" );
			return $error;
		}

		$isOld = $revision && $revision->getId() !== $page->getLatest();
		if ( $isOld ) {
			$this->statsDataFactory->increment( 'ParserOutputAccess.Case.old' );
		} else {
			$this->statsDataFactory->increment( 'ParserOutputAccess.Case.current' );
		}

		if ( !( $options & self::OPT_NO_CHECK_CACHE ) ) {
			$output = $this->getCachedParserOutput( $page, $parserOptions, $revision );
			if ( $output ) {
				return Status::newGood( $output );
			}
		}

		if ( !$revision ) {
			$revId = $page->getLatest();
			$revision = $revId ? $this->revisionLookup->getRevisionById( $revId ) : null;

			if ( !$revision ) {
				$this->statsDataFactory->increment( "ParserOutputAccess.Status.norev" );
				return Status::newFatal( 'missing-revision', $revId );
			}
		}

		if ( $options & self::OPT_FOR_ARTICLE_VIEW ) {
			$work = $this->newPoolWorkArticleView( $page, $parserOptions, $revision, $options );
			/** @var Status $status */
			$status = $work->execute();
		} else {
			$status = $this->renderRevision( $page, $parserOptions, $revision, $options );
		}

		$output = $status->getValue();
		Assert::postcondition( $output || !$status->isOK(), 'Inconsistent status' );

		if ( $output && !$isOld ) {
			$primaryCache = $this->getPrimaryCache( $parserOptions );
			$classCacheKey = $primaryCache->makeParserOutputKey( $page, $parserOptions );
			$this->localCache->setField( $classCacheKey, $page->getLatest(), $output );
		}

		if ( $status->isGood() ) {
			$this->statsDataFactory->increment( 'ParserOutputAccess.Status.good' );
		} elseif ( $status->isOK() ) {
			$this->statsDataFactory->increment( 'ParserOutputAccess.Status.ok' );
		} else {
			$this->statsDataFactory->increment( 'ParserOutputAccess.Status.error' );
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
	 * @see PoolWorkArticleView::renderRevision
	 */
	private function renderRevision(
		PageRecord $page,
		ParserOptions $parserOptions,
		RevisionRecord $revision,
		int $options
	): Status {
		$this->statsDataFactory->increment( 'ParserOutputAccess.PoolWork.None' );

		$renderedRev = $this->revisionRenderer->getRenderedRevision(
			$revision,
			$parserOptions,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		$output = $renderedRev->getRevisionParserOutput();

		if ( !( $options & self::OPT_NO_UPDATE_CACHE ) && $output->isCacheable() ) {
			$useCache = $this->shouldUseCache( $page, $revision );

			if ( $useCache === self::CACHE_PRIMARY ) {
				$primaryCache = $this->getPrimaryCache( $parserOptions );
				$primaryCache->save( $output, $page, $parserOptions );
			} elseif ( $useCache === self::CACHE_SECONDARY ) {
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$secondaryCache->save( $output, $revision, $parserOptions );
			}
		}

		if ( $options & self::OPT_LINKS_UPDATE ) {
			$this->wikiPageFactory->newFromTitle( $page )
				->triggerOpportunisticLinksUpdate( $output );
		}

		return Status::newGood( $output );
	}

	/**
	 * @param PageRecord $page
	 * @param RevisionRecord|null $revision
	 * @param int $options
	 *
	 * @return Status|null
	 */
	private function checkPreconditions(
		PageRecord $page,
		?RevisionRecord $revision = null,
		int $options = 0
	): ?Status {
		if ( !$page->exists() ) {
			return Status::newFatal( 'nopagetext' );
		}

		if ( !( $options & self::OPT_NO_UPDATE_CACHE ) && $revision && !$revision->getId() ) {
			throw new InvalidArgumentException(
				'The revision does not have a known ID. Use OPT_NO_CACHE.'
			);
		}

		if ( $revision && $revision->getPageId() !== $page->getId() ) {
			throw new InvalidArgumentException(
				'The revision does not belong to the given page.'
			);
		}

		if ( $revision && !( $options & self::OPT_NO_AUDIENCE_CHECK ) ) {
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

	/**
	 * @param PageRecord $page
	 * @param ParserOptions $parserOptions
	 * @param RevisionRecord $revision
	 * @param int $options
	 *
	 * @return PoolCounterWork
	 */
	protected function newPoolWorkArticleView(
		PageRecord $page,
		ParserOptions $parserOptions,
		RevisionRecord $revision,
		int $options
	): PoolCounterWork {
		$useCache = $this->shouldUseCache( $page, $revision );

		switch ( $useCache ) {
			case self::CACHE_PRIMARY:
				$this->statsDataFactory->increment( 'ParserOutputAccess.PoolWork.Current' );
				$primaryCache = $this->getPrimaryCache( $parserOptions );
				$parserCacheMetadata = $primaryCache->getMetadata( $page );
				$cacheKey = $primaryCache->makeParserOutputKey( $page, $parserOptions,
					$parserCacheMetadata ? $parserCacheMetadata->getUsedOptions() : null
				);

				$workKey = $cacheKey . ':revid:' . $revision->getId();

				return new PoolWorkArticleViewCurrent(
					$workKey,
					$page,
					$revision,
					$parserOptions,
					$this->revisionRenderer,
					$primaryCache,
					$this->lbFactory,
					$this->chronologyProtector,
					$this->loggerSpi,
					$this->wikiPageFactory,
					!( $options & self::OPT_NO_UPDATE_CACHE ),
					(bool)( $options & self::OPT_LINKS_UPDATE )
				);

			case self::CACHE_SECONDARY:
				$this->statsDataFactory->increment( 'ParserOutputAccess.PoolWork.Old' );
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$workKey = $secondaryCache->makeParserOutputKey( $revision, $parserOptions );
				return new PoolWorkArticleViewOld(
					$workKey,
					$secondaryCache,
					$revision,
					$parserOptions,
					$this->revisionRenderer,
					$this->loggerSpi
				);

			default:
				$this->statsDataFactory->increment( 'ParserOutputAccess.PoolWork.Uncached' );
				$secondaryCache = $this->getSecondaryCache( $parserOptions );
				$workKey = $secondaryCache->makeParserOutputKeyOptionalRevId( $revision, $parserOptions );
				return new PoolWorkArticleView(
					$workKey,
					$revision,
					$parserOptions,
					$this->revisionRenderer,
					$this->loggerSpi
				);
		}

		// unreachable
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

}
