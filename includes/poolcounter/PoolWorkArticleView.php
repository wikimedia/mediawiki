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
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;

class PoolWorkArticleView extends PoolCounterWork {
	/** @var WikiPage */
	private $page;

	/** @var string */
	private $cacheKey;

	/** @var int */
	private $revid;

	/** @var ParserCache */
	private $parserCache;

	/** @var ParserOptions */
	private $parserOptions;

	/** @var RevisionRecord|null */
	private $revision = null;

	/** @var int */
	private $audience;

	/** @var RevisionStore */
	private $revisionStore = null;

	/** @var RevisionRenderer */
	private $renderer = null;

	/** @var ParserOutput|bool */
	private $parserOutput = false;

	/** @var bool */
	private $isDirty = false;

	/** @var Status|bool */
	private $error = false;

	/**
	 * @param WikiPage $page
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param int $revid ID of the revision being parsed.
	 * @param bool $useParserCache Whether to use the parser cache.
	 *   operation.
	 * @param RevisionRecord|Content|string|null $revision Revision to render, or null to load it;
	 *        may also be given as a wikitext string, or a Content object, for BC.
	 * @param int $audience One of the RevisionRecord audience constants
	 */
	public function __construct( WikiPage $page, ParserOptions $parserOptions,
		$revid, $useParserCache, $revision = null, $audience = RevisionRecord::FOR_PUBLIC
	) {
		if ( is_string( $revision ) ) { // BC: very old style call
			$modelId = $page->getRevision()->getContentModel();
			$format = $page->getRevision()->getContentFormat();
			$revision = ContentHandler::makeContent( $revision, $page->getTitle(), $modelId, $format );
		}

		if ( $revision instanceof Content ) { // BC: old style call
			$content = $revision;
			$revision = new MutableRevisionRecord( $page->getTitle() );
			$revision->setId( $revid );
			$revision->setPageId( $page->getId() );
			$revision->setContent( SlotRecord::MAIN, $content );
		}

		if ( $revision ) {
			// Check that the RevisionRecord matches $revid and $page, but still allow
			// fake RevisionRecords coming from errors or hooks in Article to be rendered.
			if ( $revision->getId() && $revision->getId() !== $revid ) {
				throw new InvalidArgumentException( '$revid parameter mismatches $revision parameter' );
			}
			if ( $revision->getPageId()
				&& $revision->getPageId() !== $page->getTitle()->getArticleID()
			) {
				throw new InvalidArgumentException( '$page parameter mismatches $revision parameter' );
			}
		}

		// TODO: DI: inject services
		$this->renderer = MediaWikiServices::getInstance()->getRevisionRenderer();
		$this->revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$this->parserCache = MediaWikiServices::getInstance()->getParserCache();

		$this->page = $page;
		$this->revid = $revid;
		$this->cacheable = $useParserCache;
		$this->parserOptions = $parserOptions;
		$this->revision = $revision;
		$this->audience = $audience;
		$this->cacheKey = $this->parserCache->getKey( $page, $parserOptions );
		$keyPrefix = $this->cacheKey ?: ObjectCache::getLocalClusterInstance()->makeKey(
			'articleview', 'missingcachekey'
		);

		parent::__construct( 'ArticleView', $keyPrefix . ':revid:' . $revid );
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
		global $wgUseFileCache;

		// @todo several of the methods called on $this->page are not declared in Page, but present
		//        in WikiPage and delegated by Article.

		$isCurrent = $this->revid === $this->page->getLatest();

		// The current revision cannot be hidden so we can skip some checks.
		$audience = $isCurrent ? RevisionRecord::RAW : $this->audience;

		if ( $this->revision !== null ) {
			$rev = $this->revision;
		} elseif ( $isCurrent ) {
			$rev = $this->page->getRevision()
				? $this->page->getRevision()->getRevisionRecord()
				: null;
		} else {
			$rev = $this->revisionStore->getRevisionByTitle( $this->page->getTitle(), $this->revid );
		}

		if ( !$rev ) {
			// couldn't load
			return false;
		}

		$renderedRevision = $this->renderer->getRenderedRevision(
			$rev,
			$this->parserOptions,
			null,
			[ 'audience' => $audience ]
		);

		if ( !$renderedRevision ) {
			// audience check failed
			return false;
		}

		// Reduce effects of race conditions for slow parses (T48014)
		$cacheTime = wfTimestampNow();

		$time = - microtime( true );
		$this->parserOutput = $renderedRevision->getRevisionParserOutput();
		$time += microtime( true );

		// Timing hack
		if ( $time > 3 ) {
			// TODO: Use Parser's logger (once it has one)
			$logger = MediaWiki\Logger\LoggerFactory::getInstance( 'slow-parse' );
			$logger->info( '{time} {title}', [
				'time' => number_format( $time, 2 ),
				'title' => $this->page->getTitle()->getPrefixedDBkey(),
				'ns' => $this->page->getTitle()->getNamespace(),
				'trigger' => 'view',
			] );
		}

		if ( $this->cacheable && $this->parserOutput->isCacheable() && $isCurrent ) {
			$this->parserCache->save(
				$this->parserOutput, $this->page, $this->parserOptions, $cacheTime, $this->revid );
		}

		// Make sure file cache is not used on uncacheable content.
		// Output that has magic words in it can still use the parser cache
		// (if enabled), though it will generally expire sooner.
		if ( !$this->parserOutput->isCacheable() ) {
			$wgUseFileCache = false;
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
			wfDebug( __METHOD__ . ": parser cache miss\n" );
			return false;
		} else {
			wfDebug( __METHOD__ . ": parser cache hit\n" );
			return true;
		}
	}

	/**
	 * @return bool
	 */
	public function fallback() {
		$this->parserOutput = $this->parserCache->getDirty( $this->page, $this->parserOptions );

		if ( $this->parserOutput === false ) {
			wfDebugLog( 'dirty', 'dirty missing' );
			wfDebug( __METHOD__ . ": no dirty cache\n" );
			return false;
		} else {
			wfDebug( __METHOD__ . ": sending dirty output\n" );
			wfDebugLog( 'dirty', "dirty output {$this->cacheKey}" );
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
