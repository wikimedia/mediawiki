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

namespace MediaWiki\PoolCounter;

use InvalidArgumentException;
use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageRecord;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\ChronologyProtector;

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
	/** @var WikiPageFactory */
	private $wikiPageFactory;
	/** @var bool Whether it should trigger an opportunistic LinksUpdate or not */
	private bool $triggerLinksUpdate;
	private ChronologyProtector $chronologyProtector;

	/**
	 * @param PoolCounter $poolCounter
	 * @param PageRecord $page
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param ParserCache $parserCache
	 * @param ChronologyProtector $chronologyProtector
	 * @param LoggerSpi $loggerSpi
	 * @param WikiPageFactory $wikiPageFactory
	 * @param bool $cacheable Whether it should store the result in cache or not
	 * @param bool $triggerLinksUpdate Whether it should trigger an opportunistic LinksUpdate or not
	 */
	public function __construct(
		PoolCounter $poolCounter,
		PageRecord $page,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		ParserCache $parserCache,
		ChronologyProtector $chronologyProtector,
		LoggerSpi $loggerSpi,
		WikiPageFactory $wikiPageFactory,
		bool $cacheable = true,
		bool $triggerLinksUpdate = false
	) {
		// TODO: Remove support for partially initialized RevisionRecord instances once
		//       Article no longer uses fake revisions.
		if ( $revision->getPageId() && $revision->getPageId() !== $page->getId() ) {
			throw new InvalidArgumentException( '$page parameter mismatches $revision parameter' );
		}

		parent::__construct(
			$poolCounter,
			$revision,
			$parserOptions,
			$revisionRenderer,
			$loggerSpi
		);

		$this->workKey = $poolCounter->getKey();
		$this->page = $page;
		$this->parserCache = $parserCache;
		$this->chronologyProtector = $chronologyProtector;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->cacheable = $cacheable;
		$this->triggerLinksUpdate = $triggerLinksUpdate;
	}

	/**
	 * @return Status
	 */
	public function doWork() {
		// T371713: Temporary statistics collection code to determine
		// feasibility of Parsoid selective update
		$sampleRate = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::ParsoidSelectiveUpdateSampleRate
		);
		$doSample = ( $sampleRate && mt_rand( 1, $sampleRate ) === 1 );

		$previousOutput = null;
		if ( $this->parserOptions->getUseParsoid() || $doSample ) {
			// Parsoid can do selective updates, so it is worth checking the
			// cache for an existing entry.  Not worth it for the legacy
			// parser, though.
			$previousOutput = $this->parserCache->getDirty( $this->page, $this->parserOptions ) ?: null;
		}
		$status = $this->renderRevision( $previousOutput, $doSample, 'PoolWorkArticleViewCurrent' );
		/** @var ParserOutput|null $output */
		$output = $status->getValue();

		if ( $output ) {
			if ( $this->cacheable && $output->isCacheable() ) {
				$this->parserCache->save(
					$output,
					$this->page,
					$this->parserOptions
				);
			}

			if ( $this->triggerLinksUpdate ) {
				$this->wikiPageFactory->newFromTitle( $this->page )->triggerOpportunisticLinksUpdate( $output );
			}
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
				$logger->info(
					'declining fast-fallback to stale output since ChronologyProtector ' .
					'reports the client recently made changes',
					[
						'workKey' => $this->workKey,
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

/** @deprecated class alias since 1.42 */
class_alias( PoolWorkArticleViewCurrent::class, 'PoolWorkArticleViewCurrent' );
