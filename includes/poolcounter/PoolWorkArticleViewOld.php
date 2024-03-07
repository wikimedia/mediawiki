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

use MediaWiki\Logger\Spi as LoggerSpi;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;
use ParserOptions;

/**
 * PoolWorkArticleView for an old revision of a page, using a simple cache.
 *
 * @internal
 */
class PoolWorkArticleViewOld extends PoolWorkArticleView {
	/** @var RevisionOutputCache */
	private $cache;

	/**
	 * @param string $workKey PoolCounter key.
	 * @param RevisionOutputCache $cache The cache to store ParserOutput in.
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param LoggerSpi $loggerSpi
	 */
	public function __construct(
		string $workKey,
		RevisionOutputCache $cache,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		LoggerSpi $loggerSpi
	) {
		parent::__construct( $workKey, $revision, $parserOptions, $revisionRenderer, $loggerSpi );

		$this->cache = $cache;

		$this->cacheable = true;
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

		if ( $output && $output->isCacheable() ) {
			$this->cache->save( $output, $this->revision, $this->parserOptions, $cacheTime );
		}

		return $status;
	}

	/**
	 * @return Status|false
	 */
	public function getCachedWork() {
		$parserOutput = $this->cache->get( $this->revision, $this->parserOptions );

		return $parserOutput ? Status::newGood( $parserOutput ) : false;
	}

}

/** @deprecated class alias since 1.41 */
class_alias( PoolWorkArticleViewOld::class, 'PoolWorkArticleViewOld' );
