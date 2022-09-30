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

use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use Wikimedia\Rdbms\ILBFactory;

/**
 * PoolWorkArticleView for the current revision of a page, using ParserCache.
 *
 * @internal
 */
class PoolWorkArticleViewCurrent extends PoolWorkArticleView {
	/** @var string */
	private $workKey;
	/** @var PageRecord */
	private $page;
	/** @var ParserCache */
	private $parserCache;
	/** @var ILBFactory */
	private $lbFactory;
	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/**
	 * @param string $workKey
	 * @param PageRecord $page
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param ParserCache $parserCache
	 * @param ILBFactory $lbFactory
	 * @param LoggerSpi $loggerSpi
	 * @param WikiPageFactory $wikiPageFactory
	 * @param bool $cacheable Whether it should store the result in cache or not
	 */
	public function __construct(
		string $workKey,
		PageRecord $page,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		ParserCache $parserCache,
		ILBFactory $lbFactory,
		LoggerSpi $loggerSpi,
		WikiPageFactory $wikiPageFactory,
		bool $cacheable = true
	) {
		// TODO: Remove support for partially initialized RevisionRecord instances once
		//       Article no longer uses fake revisions.
		if ( $revision->getPageId() && $revision->getPageId() !== $page->getId() ) {
			throw new InvalidArgumentException( '$page parameter mismatches $revision parameter' );
		}

		parent::__construct( $workKey, $revision, $parserOptions, $revisionRenderer, $loggerSpi );

		$this->workKey = $workKey;
		$this->page = $page;
		$this->parserCache = $parserCache;
		$this->lbFactory = $lbFactory;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->cacheable = $cacheable;
	}

	/**
	 * @return Status
	 */
	public function doWork() {
		// Reduce effects of race conditions for slow parses (T48014)
		$cacheTime = wfTimestampNow();

		$status = $this->renderRevision();
		/** @var ParserOutput|null $output */
		$output = $status->getValue();

		if ( $output ) {
			if ( $this->cacheable && $output->isCacheable() ) {
				$this->parserCache->save(
					$output,
					$this->page,
					$this->parserOptions,
					$cacheTime,
					$this->revision->getId()
				);
			}

			$this->wikiPageFactory->newFromTitle( $this->page )
				->triggerOpportunisticLinksUpdate( $output );
		}

		return $status;
	}

	/**
	 * @return Status|false
	 */
	public function getCachedWork() {
		$parserOutput = $this->parserCache->get( $this->page, $this->parserOptions );

		$logger = $this->loggerSpi->getLogger( 'PoolWorkArticleView' );
		$logger->debug( $parserOutput ? 'parser cache hit' : 'parser cache miss' );

		return $parserOutput ? Status::newGood( $parserOutput ) : false;
	}

	/**
	 * @param bool $fast Fast stale request
	 * @return Status|false
	 */
	public function fallback( $fast ) {
		$parserOutput = $this->parserCache->getDirty( $this->page, $this->parserOptions );

		$logger = $this->loggerSpi->getLogger( 'dirty' );

		if ( !$parserOutput ) {
			$logger->info( 'dirty missing' );
			return false;
		}

		if ( $fast ) {
			/* Check if the stale response is from before the last write to the
			 * DB by this user. Declining to return a stale response in this
			 * case ensures that the user will see their own edit after page
			 * save.
			 *
			 * Note that the CP touch time is the timestamp of the shutdown of
			 * the save request, so there is a bias towards avoiding fast stale
			 * responses of potentially several seconds.
			 */
			$lastWriteTime = $this->lbFactory->getChronologyProtectorTouched();
			$cacheTime = MWTimestamp::convert( TS_UNIX, $parserOutput->getCacheTime() );
			if ( $lastWriteTime && $cacheTime <= $lastWriteTime ) {
				$logger->info(
					'declining to send dirty output since cache time ' .
						'{cacheTime} is before last write time {lastWriteTime}',
					[
						'workKey' => $this->workKey,
						'cacheTime' => $cacheTime,
						'lastWriteTime' => $lastWriteTime,
					]
				);
				// Forget this ParserOutput -- we will request it again if
				// necessary in slow mode. There might be a newer entry
				// available by that time.
				return false;
			}
		}

		$logger->info( $fast ? 'fast dirty output' : 'dirty output', [ 'workKey' => $this->workKey ] );

		$status = Status::newGood( $parserOutput );
		$status->warning( 'view-pool-dirty-output' );
		$status->warning( $fast ? 'view-pool-contention' : 'view-pool-overload' );
		return $status;
	}

}
