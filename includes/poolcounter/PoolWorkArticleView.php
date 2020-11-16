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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;

/**
 * PoolCounter protected work wrapping RenderedRevision->getRevisionParserOutput.
 * @note No audience checks are applied.
 *
 * @internal
 */
class PoolWorkArticleView extends PoolCounterWork {
	/** @var WikiPage */
	private $page;

	/** @var string */
	private $cacheKey;

	/** @var ParserCache */
	private $parserCache;

	/** @var ParserOptions */
	private $parserOptions;

	/** @var RevisionRecord|null */
	private $revision = null;

	/** @var RevisionRenderer */
	private $renderer = null;

	/** @var ParserOutput|bool */
	private $parserOutput = false;

	/** @var bool */
	private $isDirty = false;

	/** @var bool */
	private $isFast = false;

	/** @var Status|bool */
	private $error = false;

	/**
	 * @param WikiPage $page
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param bool $useParserCache Whether to use the parser cache.
	 * @param RevisionRenderer $revisionRenderer
	 * @param ParserCache $parserCache
	 */
	public function __construct(
		WikiPage $page,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		bool $useParserCache,
		RevisionRenderer $revisionRenderer,
		ParserCache $parserCache
	) {
		// TODO: Remove support for partially initialized RevisionRecord instances once
		//       Article no longer uses fake revisions.
		if ( $revision->getPageId() && $revision->getPageId() !== $page->getTitle()->getArticleID() ) {
			throw new InvalidArgumentException( '$page parameter mismatches $revision parameter' );
		}

		$this->page = $page;
		$this->revision = $revision;
		$this->parserOptions = $parserOptions;
		$this->cacheable = $useParserCache;
		$this->renderer = $revisionRenderer;
		$this->parserCache = $parserCache;

		$parserCacheMetadata = $this->parserCache->getMetadata( $page );
		$this->cacheKey = $this->parserCache->makeParserOutputKey( $page, $parserOptions,
			$parserCacheMetadata ? $parserCacheMetadata->getUsedOptions() : null
		);
		parent::__construct( 'ArticleView', $this->cacheKey . ':revid:' . $revision->getId() );
	}

	/**
	 * Get the ParserOutput from this object, or false in case of failure
	 *
	 * @return ParserOutput|bool
	 */
	public function getParserOutput() {
		return $this->parserOutput;
	}

	/**
	 * Get whether the ParserOutput is a dirty one (i.e. expired)
	 *
	 * @return bool
	 */
	public function getIsDirty() {
		return $this->isDirty;
	}

	/**
	 * Get whether the ParserOutput was retrieved in fast stale mode
	 *
	 * @return bool
	 */
	public function getIsFastStale() {
		return $this->isFast;
	}

	/**
	 * Get a Status object in case of error or false otherwise
	 *
	 * @return Status|bool
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function doWork() {
		$isCurrent = $this->revision->getId() === $this->page->getLatest();

		$renderedRevision = $this->renderer->getRenderedRevision(
			$this->revision,
			$this->parserOptions,
			null,
			[ 'audience' => RevisionRecord::RAW ]
		);

		if ( !$renderedRevision ) {
			// audience check failed
			return false;
		}

		// Reduce effects of race conditions for slow parses (T48014)
		$cacheTime = wfTimestampNow();

		$time = -microtime( true );
		$this->parserOutput = $renderedRevision->getRevisionParserOutput();
		$time += microtime( true );

		// Timing hack
		if ( $time > 3 ) {
			// TODO: Use Parser's logger (once it has one)
			$logger = MediaWiki\Logger\LoggerFactory::getInstance( 'slow-parse' );
			$logger->info( 'Parsing {title} was slow, took {time} seconds', [
				'time' => number_format( $time, 2 ),
				'title' => $this->page->getTitle()->getPrefixedDBkey(),
				'ns' => $this->page->getTitle()->getNamespace(),
				'trigger' => 'view',
			] );
		}

		if ( $this->cacheable && $this->parserOutput->isCacheable() && $isCurrent ) {
			$this->parserCache->save(
				$this->parserOutput,
				$this->page,
				$this->parserOptions,
				$cacheTime,
				$this->revision->getId()
			);
		}

		if ( $isCurrent ) {
			$this->page->triggerOpportunisticLinksUpdate( $this->parserOutput );
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function getCachedWork() {
		$this->parserOutput = $this->parserCache->get( $this->page, $this->parserOptions );

		if ( $this->parserOutput === false ) {
			wfDebug( __METHOD__ . ": parser cache miss" );
			return false;
		} else {
			wfDebug( __METHOD__ . ": parser cache hit" );
			return true;
		}
	}

	/**
	 * @param bool $fast Fast stale request
	 * @return bool
	 */
	public function fallback( $fast ) {
		$this->parserOutput = $this->parserCache->getDirty( $this->page, $this->parserOptions );

		$fastMsg = '';
		if ( $this->parserOutput && $fast ) {
			/* Check if the stale response is from before the last write to the
			 * DB by this user. Declining to return a stale response in this
			 * case ensures that the user will see their own edit after page
			 * save.
			 *
			 * Note that the CP touch time is the timestamp of the shutdown of
			 * the save request, so there is a bias towards avoiding fast stale
			 * responses of potentially several seconds.
			 */
			$lastWriteTime = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
				->getChronologyProtectorTouched();
			$cacheTime = MWTimestamp::convert( TS_UNIX, $this->parserOutput->getCacheTime() );
			if ( $lastWriteTime && $cacheTime <= $lastWriteTime ) {
				wfDebugLog( 'dirty', "declining to send dirty output since cache time " .
					$cacheTime . " is before last write time $lastWriteTime" );
				// Forget this ParserOutput -- we will request it again if
				// necessary in slow mode. There might be a newer entry
				// available by that time.
				$this->parserOutput = false;
				return false;
			}
			$this->isFast = true;
			$fastMsg = 'fast ';
		}

		if ( $this->parserOutput === false ) {
			wfDebugLog( 'dirty', 'dirty missing' );
			return false;
		} else {
			wfDebugLog( 'dirty', "{$fastMsg}dirty output {$this->cacheKey}" );
			$this->isDirty = true;
			return true;
		}
	}

	/**
	 * @param Status $status
	 * @return bool
	 */
	public function error( $status ) {
		$this->error = $status;
		return false;
	}
}
