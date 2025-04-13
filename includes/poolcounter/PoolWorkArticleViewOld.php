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
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\RevisionOutputCache;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Status\Status;

/**
 * PoolWorkArticleView for an old revision of a page, using a simple cache.
 *
 * @internal
 */
class PoolWorkArticleViewOld extends PoolWorkArticleView {
	/** @var RevisionOutputCache */
	private $cache;

	/**
	 * @param PoolCounter $poolCounter
	 * @param RevisionOutputCache $cache The cache to store ParserOutput in.
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param LoggerSpi $loggerSpi
	 */
	public function __construct(
		PoolCounter $poolCounter,
		RevisionOutputCache $cache,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		LoggerSpi $loggerSpi
	) {
		parent::__construct(
			$poolCounter,
			$revision,
			$parserOptions,
			$revisionRenderer,
			$loggerSpi
		);

		$this->cache = $cache;

		$this->cacheable = true;
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

		// Reduce effects of race conditions for slow parses (T48014)
		$cacheTime = wfTimestampNow();

		$status = $this->renderRevision(
			null, /* don't attempt Parsoid selective updates on this path */
			$doSample, 'PoolWorkArticleViewOld'
		);
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

/** @deprecated class alias since 1.42 */
class_alias( PoolWorkArticleViewOld::class, 'PoolWorkArticleViewOld' );
