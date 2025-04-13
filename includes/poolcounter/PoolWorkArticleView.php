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
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\WikiMap\WikiMap;

/**
 * PoolCounter protected work wrapping RenderedRevision->getRevisionParserOutput.
 * Caching behavior may be defined by subclasses.
 *
 * @note No audience checks are applied.
 *
 * @internal
 */
class PoolWorkArticleView extends PoolCounterWork {
	/** @var ParserOptions */
	protected $parserOptions;
	/** @var RevisionRecord */
	protected $revision;
	/** @var RevisionRenderer */
	private $renderer;
	/** @var LoggerSpi */
	protected $loggerSpi;

	/**
	 * @param PoolCounter $poolCounter
	 * @param RevisionRecord $revision Revision to render
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse
	 * @param RevisionRenderer $revisionRenderer
	 * @param LoggerSpi $loggerSpi
	 */
	public function __construct(
		PoolCounter $poolCounter,
		RevisionRecord $revision,
		ParserOptions $parserOptions,
		RevisionRenderer $revisionRenderer,
		LoggerSpi $loggerSpi
	) {
		parent::__construct(
			$poolCounter->getType(),
			$poolCounter->getKey(),
			$poolCounter
		);
		$this->revision = $revision;
		$this->parserOptions = $parserOptions;
		$this->renderer = $revisionRenderer;
		$this->loggerSpi = $loggerSpi;
	}

	/**
	 * @return Status
	 */
	public function doWork() {
		return $this->renderRevision();
	}

	/**
	 * Render the given revision.
	 *
	 * @see ParserOutputAccess::renderRevision
	 *
	 * @param ?ParserOutput $previousOutput previously-cached output for this
	 *   page (used by Parsoid for selective updates)
	 * @param bool $doSample Whether to collect statistics on this render
	 * @param string $sourceLabel the source label to use on the statistics
	 * @return Status with the value being a ParserOutput or null
	 */
	public function renderRevision(
		?ParserOutput $previousOutput = null,
		bool $doSample = false,
		string $sourceLabel = ''
	): Status {
		$renderedRevision = $this->renderer->getRenderedRevision(
			$this->revision,
			$this->parserOptions,
			null,
			[
				'audience' => RevisionRecord::RAW,
				'previous-output' => $previousOutput,
			]
		);

		$parserOutput = $renderedRevision->getRevisionParserOutput();

		if ( $doSample ) {
			$stats = MediaWikiServices::getInstance()->getStatsFactory();
			$content = $this->revision->getContent( SlotRecord::MAIN );
			$labels = [
				'source' => $sourceLabel,
				'type' => $previousOutput === null ? 'full' : 'selective',
				'reason' => $this->parserOptions->getRenderReason(),
				'parser' => $this->parserOptions->getUseParsoid() ? 'parsoid' : 'legacy',
				'opportunistic' => 'false',
				'wiki' => WikiMap::getCurrentWikiId(),
				'model' => $content ? $content->getModel() : 'unknown',
			];
			$stats
				->getCounter( 'ParserCache_selective_total' )
				->setLabels( $labels )
				->increment();
			$stats
				->getCounter( 'ParserCache_selective_cpu_seconds' )
				->setLabels( $labels )
				->incrementBy( $parserOutput->getTimeProfile( 'cpu' ) );
		}

		return Status::newGood( $parserOutput );
	}

	/**
	 * @param Status $status
	 * @return Status
	 */
	public function error( $status ) {
		return $status;
	}

}

/** @deprecated class alias since 1.42 */
class_alias( PoolWorkArticleView::class, 'PoolWorkArticleView' );
