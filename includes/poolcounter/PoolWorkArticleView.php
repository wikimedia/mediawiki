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

class PoolWorkArticleView extends PoolCounterWork {
	/** @var Page */
	private $page;

	/** @var string */
	private $cacheKey;

	/** @var int */
	private $revid;

	/** @var ParserOptions */
	private $parserOptions;

	/** @var Content|null */
	private $content = null;

	/** @var ParserOutput|bool */
	private $parserOutput = false;

	/** @var bool */
	private $isDirty = false;

	/** @var Status|bool */
	private $error = false;

	/**
	 * @param Page $page
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param int $revid ID of the revision being parsed.
	 * @param bool $useParserCache Whether to use the parser cache.
	 *   operation.
	 * @param Content|string $content Content to parse or null to load it; may
	 *   also be given as a wikitext string, for BC.
	 */
	public function __construct( Page $page, ParserOptions $parserOptions,
		$revid, $useParserCache, $content = null
	) {
		if ( is_string( $content ) ) { // BC: old style call
			$modelId = $page->getRevision()->getContentModel();
			$format = $page->getRevision()->getContentFormat();
			$content = ContentHandler::makeContent( $content, $page->getTitle(), $modelId, $format );
		}

		$this->page = $page;
		$this->revid = $revid;
		$this->cacheable = $useParserCache;
		$this->parserOptions = $parserOptions;
		$this->content = $content;
		$this->cacheKey = ParserCache::singleton()->getKey( $page, $parserOptions );
		$keyPrefix = $this->cacheKey ?: wfMemcKey( 'articleview', 'missingcachekey' );
		parent::__construct( 'ArticleView', $keyPrefix . ':revid:' . $revid );
	}

	/**
	 * Get the ParserOutput from this object, or false in case of failure
	 *
	 * @return ParserOutput
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

		if ( $this->content !== null ) {
			$content = $this->content;
		} elseif ( $isCurrent ) {
			// XXX: why use RAW audience here, and PUBLIC (default) below?
			$content = $this->page->getContent( Revision::RAW );
		} else {
			$rev = Revision::newFromTitle( $this->page->getTitle(), $this->revid );

			if ( $rev === null ) {
				$content = null;
			} else {
				// XXX: why use PUBLIC audience here (default), and RAW above?
				$content = $rev->getContent();
			}
		}

		if ( $content === null ) {
			return false;
		}

		// Reduce effects of race conditions for slow parses (bug 46014)
		$cacheTime = wfTimestampNow();

		$time = - microtime( true );
		$this->parserOutput = $content->getParserOutput(
			$this->page->getTitle(),
			$this->revid,
			$this->parserOptions
		);
		$time += microtime( true );

		// Timing hack
		if ( $time > 3 ) {
			wfDebugLog( 'slow-parse', sprintf( "%-5.2f %s", $time,
				$this->page->getTitle()->getPrefixedDBkey() ) );
		}

		if ( $this->cacheable && $this->parserOutput->isCacheable() && $isCurrent ) {
			ParserCache::singleton()->save(
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
		$this->parserOutput = ParserCache::singleton()->get( $this->page, $this->parserOptions );

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
		$this->parserOutput = ParserCache::singleton()->getDirty( $this->page, $this->parserOptions );

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
